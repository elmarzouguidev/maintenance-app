<?php

namespace App\Http\Controllers\Commercial\Invoice;

use App\Http\Controllers\Controller;
use App\Http\Requests\Commercial\Invoice\DeleteArticleFormRequest;
use App\Http\Requests\Commercial\Invoice\InvoiceFormRequest;
use App\Http\Requests\Commercial\Invoice\InvoiceUpdateFormRequest;
use App\Models\Client;
use App\Models\Finance\Article;
use App\Models\Finance\Company;
use App\Models\Finance\Estimate;
use App\Models\Finance\Invoice;
use App\Repositories\Client\ClientInterface;
use App\Repositories\Company\CompanyInterface;
use App\Services\Commercial\Taxes\TVACalulator;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class InvoiceController extends Controller
{

    use TVACalulator;

    public function indexFilter(Request $request)
    {
        if (request()->has('appFilter') && request()->filled('appFilter')) {

            $invoices = QueryBuilder::for(Invoice::class)

                ->allowedFilters([
                    //'company_id'
                    //AllowedFilter::exact('etat')
                    AllowedFilter::scope('GetCompany', 'filters_companies'),
                    AllowedFilter::scope('GetStatus', 'filters_status'),

                ])
                ->with(['company', 'client'])
                ->get()
                ->appends($request->query());
            //->get();
        } else {
            $invoices = Invoice::with(['company', 'client'])->get();
        }

        $companies = Company::all();

        return view('theme.pages.Commercial.Invoice.index', compact('invoices', 'companies'));
    }

    public function index()
    {
        $invoices = Invoice::with(['company', 'client'])->paginate(5);

        return view('theme.pages.Commercial.Invoice.index', compact('invoices'));
    }

    public function create()
    {

        // $clients = app(ClientInterface::class)->getClients(['id', 'entreprise', 'contact']);

        //$companies = app(CompanyInterface::class)->getCompanies(['id', 'name']);

        return view('theme.pages.Commercial.Invoice.__create.index');
    }

    public function single(Invoice $invoice)
    {
        $invoice->load('articles');

        return view('theme.pages.Commercial.Invoice.__detail.index', compact('invoice'));
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

        if ($request->has('estimated') && $request->filled('estimated')) {
            // dd($request->estimated,"ouii");
            $estimate = Estimate::whereUuid($request->estimated)->firstOrFail();

            $invoice->estimate()->associate($estimate)->save();
            $estimate->update(['is_invoiced' => true]);
        }

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

        $newArticles = $request->getArticles()->map(function ($item) {
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

        $invoice = Invoice::whereUuid($request->invoice)->firstOrFail();
        $article = Article::whereUuid($request->article)->firstOrFail();

        if ($invoice && $article) {

            $totalPrice = $invoice->price_ht;

            $totalArticlePrice = $article->montant_ht;

            $finalPrice = $totalPrice - $totalArticlePrice;

            $invoice->articles()
                ->whereUuid($request->article)
                ->whereInvoiceId($invoice->id)
                ->delete();

            $invoice->price_ht = $finalPrice;
            $invoice->price_total = $this->caluculateTva($finalPrice);
            $invoice->total_tva = $this->calculateOnlyTva($finalPrice);
            $invoice->save();

            return response()->json([
                'success' => 'Record deleted successfully!'
            ]);
        }
        return response()->json([
            'error' => 'problem detected !'
        ]);
    }
}
