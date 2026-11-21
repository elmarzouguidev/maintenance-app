<?php

namespace App\Http\Controllers\API\V1\Application;

use App\Http\Controllers\Controller;
use App\Http\Resources\Application\Client\ClientResource;
use App\Models\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Return a paginated, searchable list of clients for the invoice autocomplete.
     */
    public function index(Request $request): JsonResponse
    {
        $search = trim((string) $request->query('q', ''));

        $clients = Client::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('entreprise', 'like', "%{$search}%")
                        ->orWhere('contact', 'like', "%{$search}%")
                        ->orWhere('telephone', 'like', "%{$search}%")
                        ->orWhere('code', 'like', "%{$search}%");
                });
            })
            ->orderBy('entreprise')
            ->paginate(30);

        return response()->json(
            [
                'payload' => ClientResource::collection($clients->items()),
                'total_count' => $clients->total(),
                'current_page' => $clients->currentPage(),
                'last_page' => $clients->lastPage(),
                '_response' => ['msg' => 'successfully retrieved clients'],
            ],
            200
        );
    }
}
