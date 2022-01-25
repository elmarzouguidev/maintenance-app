<?php

namespace App\Http\Controllers\Commercial\Invoice;

use App\Http\Controllers\Controller;
use App\Http\Requests\Commercial\Invoice\InvoiceFormRequest;
use App\Http\Requests\Commercial\Invoice\InvoiceUpdateFormRequest;
use App\Models\Client;
use App\Models\Finance\Invoice;
use App\Repositories\Client\ClientInterface;
use App\Repositories\Company\CompanyInterface;
use App\Services\Commercial\Taxes\TVACalulator;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{

    use TVACalulator;

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
        //dd($request->all());

        $articles = $request->articles;

        $totalPrice = collect($articles)->map(function ($item) {
            return $item['prix_unitaire'] * $item['quantity'];
        })->sum();

        $invoicesArticles = collect($articles)->map(function ($item) {

            return collect($item)->merge(['montant_ht' => $item['prix_unitaire'] * $item['quantity']]);
        })->toArray();

        //dd($invoicesArticles);

        //dd($articles, "###", $totalPrice,'##TVA##',$this->caluculateTva($totalPrice),'##OnlyTVA##',$this->calculateOnlyTva($totalPrice));

        $invoice = new Invoice();

        //$invoice->invoice_code = $request->invoice_code;
        $invoice->date_invoice = $request->date('date_invoice');
        $invoice->date_due = $request->date('date_due');

        $invoice->price_ht = $totalPrice;
        $invoice->price_total = $this->caluculateTva($totalPrice);
        $invoice->total_tva = $this->calculateOnlyTva($totalPrice);

        $invoice->client()->associate($request->client);
        $invoice->ticket()->associate($request->ticket);
        $invoice->company()->associate($request->company);

        //$invoice->client_code = $invoice->client()->whereId($request->client)->value('client_ref');
        $invoice->client_code = $invoice->client->client_ref;

        $invoice->save();

        $invoice->articles()->createMany($invoicesArticles);

        return redirect()->back()->with('success', "Le Facture a été crée avec success");
    }

    public function edit(Invoice $invoice)
    {

        $invoice->load('articles');

        return view('theme.pages.Commercial.Invoice.__edit.index', compact('invoice'));
    }

    public function update(InvoiceUpdateFormRequest $request, $invoice)
    {
        //dd($request->all());

        $invoice = Invoice::whereUuid($invoice)->firstOrFail();

        // $existsArticles = $invoice->articles()->get()->toArray();

        /*$newArticles = collect($articles)->map(function ($item) {
            return collect($item)
                ->where('montant_ht', '<=', 0)
                ->merge(['montant_ht' => $item['prix_unitaire'] * $item['quantity']]);
        })->collect();

        $newArticles = collect($articles)->map(function ($item) {
            return collect($item)

                ->merge(['montant_ht' => $item['prix_unitaire'] * $item['quantity']]);
        })->collect()->where('montant_ht', '<=', 0);*/

        $articles = collect($request->articles)->where('montant_ht', '<=', 0)->collect();
        $newArticles = $articles->map(function ($item) {
            return collect($item)
                ->merge(['montant_ht' => $item['prix_unitaire'] * $item['quantity']]);
        })->toArray();
        //dd($newArticles);
        $totalArticlePrice = collect($newArticles)->map(function ($item) {
            return $item['prix_unitaire'] * $item['quantity'];
        })->sum();
        //dd($invoice->price_ht,'###',$invoice->price_ht + $totalPrice);
        $totalPrice = $invoice->price_ht + $totalArticlePrice;

        $invoice->date_invoice = $request->date('date_invoice');
        $invoice->date_due = $request->date('date_due');
        $invoice->price_ht = $totalPrice;
        $invoice->price_total = $this->caluculateTva($totalPrice);
        $invoice->total_tva = $this->calculateOnlyTva($totalPrice);

        $invoice->client_code = $invoice->client->client_ref;

        $invoice->save();

        $invoice->articles()->createMany($newArticles);

        return redirect()->back()->with('success', "Le Facture a été modifier avec success");
    }
}
