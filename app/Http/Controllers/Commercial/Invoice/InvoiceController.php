<?php

namespace App\Http\Controllers\Commercial\Invoice;

use App\Http\Controllers\Controller;
use App\Http\Requests\Commercial\Invoice\InvoiceFormRequest;
use App\Models\Client;
use App\Models\Finance\Invoice;
use App\Repositories\Client\ClientInterface;
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
        
       $clients = app(ClientInterface::class)->getClients(['id', 'entreprise', 'contact']);

        return view('theme.pages.Commercial.Invoice.__create.index', compact('clients'));
    }

    public function store(InvoiceFormRequest $request)
    {

        dd($request->all());
    }
}
