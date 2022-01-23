<?php

namespace App\Http\Controllers\Commercial\Invoice;

use App\Http\Controllers\Controller;
use App\Http\Requests\Commercial\Invoice\InvoiceFormRequest;
use App\Models\Client;
use App\Models\Finance\Invoice;
use App\Repositories\Client\ClientInterface;
use App\Repositories\Company\CompanyInterface;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::paginate(20);

        return view('theme.pages.Commercial.Invoice.index', compact('invoices'));
    }

    public function create()
    {

       // $clients = app(ClientInterface::class)->getClients(['id', 'entreprise', 'contact']);

        //$companies = app(CompanyInterface::class)->getCompanies(['id', 'name']);

        return view('theme.pages.Commercial.Invoice.__create.index');
    }

    public function store(InvoiceFormRequest $request)
    {

        dd($request->all());

        $invoice = new Invoice();
        $invoice->client_code = $request->client_code;
        $invoice->invoice_code = $request->invoice_code;
        $invoice->price_ht = $request->price_ht;
        $invoice->price_total = $request->price_total;
        $invoice->date_due = $request->date_due;
        $invoice->client_id = $request->client;
        $invoice->ticket_id = $request->ticket;
        $invoice->company_id = $request->company;
        $invoice->save();
    }
}
