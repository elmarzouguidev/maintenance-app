<?php

namespace App\Http\Controllers\Commercial\Invoice;

use App\Http\Controllers\Controller;
use App\Http\Requests\Commercial\Invoice\DeleteArticleFormRequest;
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
        $invoices = Invoice::with(['company', 'client'])->paginate(20);

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
        // dd($request->all());

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

        $invoice->admin_notes = $request->admin_notes;
        $invoice->client_notes = $request->client_notes;
        $invoice->condition_general = $request->condition_general;

        $invoice->price_ht = $totalPrice;
        $invoice->price_total = $this->caluculateTva($totalPrice);
        $invoice->total_tva = $this->calculateOnlyTva($totalPrice);

        $invoice->client()->associate($request->client);
        $invoice->ticket()->associate($request->ticket);
        $invoice->company()->associate($request->company);

        $invoice->client_code = $invoice->client->client_ref;

        $invoice->save();

        $invoice->articles()->createMany($invoicesArticles);

        return redirect($invoice->edit_url);
    }

    public function edit(Invoice $invoice)
    {

        $invoice->load('articles');

        return view('theme.pages.Commercial.Invoice.__edit.index', compact('invoice'));
    }

    public function update(InvoiceUpdateFormRequest $request, $invoice)
    {

        $invoice = Invoice::whereUuid($invoice)->firstOrFail();

        $articles = collect($request->articles)->where('montant_ht', '<=', 0)->collect();

        $newArticles = $articles->map(function ($item) {
            return collect($item)
                ->merge(['montant_ht' => $item['prix_unitaire'] * $item['quantity']]);
        })->toArray();

        $totalArticlePrice = collect($newArticles)->map(function ($item) {
            return $item['prix_unitaire'] * $item['quantity'];
        })->sum();

        $totalPrice = $invoice->price_ht + $totalArticlePrice;

        $invoice->date_invoice = $request->date('date_invoice');
        $invoice->date_due = $request->date('date_due');
        $invoice->price_ht = $totalPrice;
        $invoice->price_total = $this->caluculateTva($totalPrice);
        $invoice->total_tva = $this->calculateOnlyTva($totalPrice);

        $invoice->client_code = $invoice->client->client_ref;

        $invoice->admin_notes = $request->admin_notes;
        $invoice->client_notes = $request->client_notes;
        $invoice->condition_general = $request->condition_general;


        $invoice->save();

        $invoice->articles()->createMany($newArticles);

        return redirect($invoice->edit_url);
        //return redirect()->back()->with('success', "Le Facture a été modifier avec success");
    }

    public function deleteInvoice(Request $request)
    {
        //dd($request->all());
        $request->validate(['invoiceId' => 'required|uuid']);

        $invoice = Invoice::whereUuid($request->invoiceId)->firstOrFail();

        if ($invoice) {

            $invoice->delete();

            return redirect()->back()->with('success', "La Facture  a éte supprimer avec success");
        }
        return redirect()->back()->with('success', "erreur . . . ");
    }

    public function deleteArticle(DeleteArticleFormRequest $request)
    {

        //dd($request->all());
        $invoice = Invoice::whereUuid($request->invoice)->firstOrFail();

        if ($invoice) {

            $invoice->articles()
                ->whereUuid($request->article)
                ->delete();

            return response()->json([
                'success' => 'Record deleted successfully!'
            ]);
        }
    }
}
