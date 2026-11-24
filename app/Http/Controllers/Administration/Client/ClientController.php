<?php

namespace App\Http\Controllers\Administration\Client;

use App\Exports\ClientsListExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Application\Client\ClientFormRequest;
use App\Http\Requests\Application\Client\ClientUpdateFormRequest;
use App\Http\Requests\Application\Client\EmailsFormRequest;
use App\Http\Requests\Application\Client\PhonesFormRequest;
use App\Models\Client;
use App\Models\Utilities\Email;
use App\Models\Utilities\Telephone;
use App\Repositories\Category\CategoryInterface;
use App\Repositories\Client\ClientInterface;
use App\Support\MonthlyTotals;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Excel as ExcelWriter;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ClientController extends Controller
{
    private const CLIENTS_PER_PAGE = 30;

    /**
     * The clients list (UI kit): search in the query string ("q"), 30 per page, code descending.
     */
    public function index(Request $request)
    {
        $search = $this->search($request);

        $clients = Client::query()->listing($search)->paginate(self::CLIENTS_PER_PAGE)->withQueryString();

        return view('theme.pages.Client.__datatable.index', compact('clients', 'search'));
    }

    /**
     * The listed clients, with the same search, as an Excel file (xlsx) or a PDF (mPDF: the PDF
     * library the app installs, see BACKLOG.md BL-58).
     */
    public function export(Request $request, string $format): BinaryFileResponse
    {
        $export = new ClientsListExport($this->search($request));

        return $format === 'pdf'
            ? Excel::download($export, 'clients.pdf', ExcelWriter::MPDF)
            : Excel::download($export, 'clients.xlsx', ExcelWriter::XLSX);
    }

    public function create()
    {
        $this->authorize('create', Client::class);

        $categories = app(CategoryInterface::class)->getCategories(['id', 'name']);

        return view('theme.pages.Client.__create.index', compact('categories'));
    }

    public function store(ClientFormRequest $request)
    {
        $this->authorize('create', Client::class);

        $client = Client::create($request->validated());

        if ($request->hasFile('logo')) {
            $client->addMediaFromRequest('logo')
                ->toMediaCollection('clients-logo');
        }

        $request->whenFilled('category', function ($input) use ($client) {
            $client->update(['category_id' => $input]);
        });

        return redirect($client->edit)->with('success', "L'ajoute a éte effectuer avec success");
    }

    public function edit(Client $client)
    {
        $this->authorize('update', $client);

        $client->load('telephones', 'emails');

        $categories = app(CategoryInterface::class)->getCategories(['id', 'name']);

        return view('theme.pages.Client.__edit.index', compact('client', 'categories'));
    }

    public function update(ClientUpdateFormRequest $request, $client)
    {
        // dd($request->telephones);
        $client = Client::whereUuid($client)->firstOrFail();

        $this->authorize('update', $client);

        $client->update($request->validated());

        if ($request->hasFile('photo')) {
            $client->addMediaFromRequest('photo')
                ->toMediaCollection('clients-logo');
        }

        $request->whenFilled('category', function ($input) use ($client) {
            $client->update(['category_id' => $input]);
        });

        return redirect($client->edit)->with('success', "L' Update a éte effectuer avec success");
    }

    public function addEmails(EmailsFormRequest $request, Client $client)
    {
        if ($request->secend_email) {
            $client->emails()->create(['email' => $request->secend_email]);

            return redirect()->back()->with('success', "L' Update a éte effectuer avec success");
        }

        return redirect()->back()->with('errors', "L' Update a éte effectuer avec success");
    }

    public function addPhones(PhonesFormRequest $request, Client $client)
    {
        // dd('Ouiii', $request->all());

        if ($request->telephone) {
            $client->telephones()->create(['telephone' => $request->telephone, 'type' => $request->type]);

            return redirect()->back()->with('success', "L' Update a éte effectuer avec success");
        }

        return redirect()->back()->with('errors', "L' Update a éte effectuer avec success");
    }

    public function show(string $slug)
    {
        $client = app(ClientInterface::class)
            ->getClientByUuid($slug)
            ->withSum('invoices', 'price_total')
            ->withSum(['invoices as price_total_paid' => function ($query) {
                $query->has('bill');
            }], 'price_total')
            ->firstOrFail();

        $client->load(['tickets' => fn ($query) => $query->latest()]);

        // Monthly invoiced total of this client (the former LaravelChart crashed, BACKLOG.md BL-67).
        $chart = MonthlyTotals::chart("Chiffre d'affaires par mois", 'line', $client->invoices()->getQuery(), 'price_total');

        return view('theme.pages.Client.__profile.index', compact('client', 'chart'));
    }

    public function delete(Request $request)
    {
        $request->validate(['clientId' => 'required|uuid']);

        $client = Client::whereUuid($request->clientId)->firstOrFail();

        $this->authorize('delete', $client);

        if ($client) {
            // dd('Oui client');

            // $client->delete();

            return redirect()->back()->with('success', 'Le client a été supprimer avec success');
        }

        return redirect()->back()->with('success', 'Problem ... !!');
    }

    public function deletePhone(Request $request)
    {
        // dd($request->all());
        $request->validate(['client' => 'required|uuid', 'phone' => 'required|uuid']);

        $client = Client::whereUuid($request->client)->firstOrFail();

        $this->authorize('delete', $client);

        $phone = Telephone::whereUuid($request->phone)->firstOrFail();

        if ($client && $phone) {
            $client->telephones()
                ->whereUuid($request->phone)
                ->where('telephoneable_id', $client->id)
                ->delete();

            return response()->json([
                'success' => 'Record deleted successfully!',
            ]);
        }

        return response()->json([
            'error' => 'problem detected !',
        ]);
    }

    public function deleteEmail(Request $request)
    {
        // dd($request->all());
        $request->validate(['client' => 'required|uuid', 'email' => 'required|uuid']);

        $client = Client::whereUuid($request->client)->firstOrFail();

        $this->authorize('delete', $client);

        $email = Email::whereUuid($request->email)->firstOrFail();

        if ($client && $email) {
            // dd("Yes delete it");
            $client->emails()
                ->whereUuid($request->email)
                ->where('emailable_id', $client->id)
                ->delete();

            return response()->json([
                'success' => 'Record deleted successfully!',
            ]);
        }

        return response()->json([
            'error' => 'problem detected !',
        ]);
    }

    private function search(Request $request): string
    {
        return trim((string) $request->query('q', ''));
    }
}
