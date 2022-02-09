<?php

namespace App\Http\Controllers\Commercial\InvoiceAvoir;

use App\Http\Controllers\Controller;
use App\Http\Requests\Commercial\InvoiceAvoir\AvoirFormRequest;
use App\Models\Finance\InvoiceAvoir;
use App\Services\Commercial\Taxes\TVACalulator;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use App\Repositories\Client\ClientInterface;
use App\Repositories\Company\CompanyInterface;

class InvoiceAvoirController extends Controller
{

    use TVACalulator;

    public function index()
    {

        if (request()->has('appFilter') && request()->filled('appFilter')) {

            $invoices = QueryBuilder::for(InvoiceAvoir::class)

                ->allowedFilters([
                    //'company_id'
                    //AllowedFilter::exact('etat')
                    AllowedFilter::scope('GetCompany', 'filters_companies'),
                    AllowedFilter::scope('GetStatus', 'filters_status'),

                ])
                ->with(['company', 'client'])
                ->get()
                ->appends(request()->query());
            //->get();
        } else {
            $invoices = InvoiceAvoir::with(['company', 'client', 'bill'])->withCount('bill')->get();
        }
        //$invoicesBills = Invoice::has('bill')->get();

        // $companies = Company::all();
        return view('theme.pages.Commercial.InvoiceAvoir.__datatable.index');
    }

    public function create()
    {

        $clients = app(ClientInterface::class)->getClients(['id', 'entreprise', 'contact']);
        $companies = app(CompanyInterface::class)->getCompanies(['id', 'name']);

        return view('theme.pages.Commercial.Invoice.__create_avoir.index', compact('clients', 'companies'));
    }

    public function store(AvoirFormRequest $request)
    {
        //dd($request->all());

        $articles = $request->articles;

        $totalPrice = collect($articles)->map(function ($item) {
            return $item['prix_unitaire'] * $item['quantity'];
        })->sum();

        $invoicesArticles = collect($articles)->map(function ($item) {

            return collect($item)->merge(['montant_ht' => $item['prix_unitaire'] * $item['quantity']]);
        })->toArray();

        $invoice = new InvoiceAvoir();

        $invoice->date_invoice = $request->date('date_invoice');
        $invoice->date_due = $request->date('date_due');

        $invoice->admin_notes = $request->admin_notes;
        $invoice->client_notes = $request->client_notes;
        $invoice->condition_general = $request->condition_general;

        $invoice->price_ht = $totalPrice;
        $invoice->price_total = $this->caluculateTva($totalPrice);
        $invoice->price_tva = $this->calculateOnlyTva($totalPrice);

        $invoice->client()->associate($request->client);
        $invoice->company()->associate($request->company);
        //$invoice->invoice()->associate($request->invoice);

        $invoice->client_code = $invoice->client->client_ref;

        $invoice->save();

        $invoice->articles()->createMany($invoicesArticles);

        return redirect()->back();
    }
}
