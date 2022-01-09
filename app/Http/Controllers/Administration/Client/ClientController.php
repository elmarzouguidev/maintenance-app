<?php

namespace App\Http\Controllers\Administration\Client;

use App\Domain\Support\SaveModel\SaveModel;
use App\Http\Controllers\Controller;
use App\Http\Requests\Application\Client\ClientFormRequest;
use App\Models\Client;
use App\Repositories\Client\ClientInterface;
use Illuminate\Http\Request;

class ClientController extends Controller
{


    public function index()
    {
        $clients = app(ClientInterface::class)->getClients();
        return view('theme.pages.Client.index', compact('clients'));
    }

    public function create()
    {
        return view('theme.pages.Client.__create.index');
    }

    public function store(ClientFormRequest $request)
    {
        $data = $request->withoutHoneypot();

        $client = (new SaveModel(new Client(), $data))->ignoreFields(['category'])->execute();

        if ($request->hasFile('photo')) {

            $client->addMediaFromRequest('photo')
                ->toMediaCollection('clients-logo');
        }

        $request->whenFilled('category', function ($input) use ($client) {
            dd('Oui its has ');
            $client->category()->associate($input)->save();
        });

        return redirect()->back()->with('success', "L'ajoute a éte effectuer avec success");
    }
}
