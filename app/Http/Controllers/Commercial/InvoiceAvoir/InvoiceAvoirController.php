<?php

namespace App\Http\Controllers\Commercial\InvoiceAvoir;

use App\Actions\Commercial\SendDocumentByMail;
use App\Actions\Commercial\UpdateDocumentArticle;
use App\Http\Controllers\Controller;
use App\Http\Requests\Commercial\InvoiceAvoir\AvoirDeleteArticleFormRequest;
use App\Http\Requests\Commercial\InvoiceAvoir\AvoirFormRequest;
use App\Http\Requests\Commercial\InvoiceAvoir\AvoirUpdateFormRequest;
use App\Http\Requests\Commercial\InvoiceAvoir\SendEmailFormRequest;
use App\Mail\Commercial\InvoiceAvoir\SendInvoiceAvoirMail;
use App\Models\Finance\Article;
use App\Models\Finance\Company;
use App\Models\Finance\Invoice;
use App\Models\Finance\InvoiceAvoir;
use App\Repositories\Client\ClientInterface;
use App\Services\Commercial\Taxes\TVACalulator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class InvoiceAvoirController extends Controller
{
    private const INVOICES_PER_PAGE = 30;

    use TVACalulator;

    public function index()
    {
        if (request()->has('appFilter') && request()->filled('appFilter')) {
            $invoices = QueryBuilder::for(InvoiceAvoir::class)
                ->allowedFilters(
                    // 'company_id'
                    // AllowedFilter::exact('etat')
                    AllowedFilter::scope('GetInvoiceAvoirDate', 'filters_date_invoice_avoir'),
                    AllowedFilter::scope('GetCompany', 'filters_companies'),
                    AllowedFilter::scope('GetClient', 'filters_clients'),
                    AllowedFilter::scope('GetStatus', 'filters_status'),
                    AllowedFilter::scope('DateBetween', 'filters_date'),
                    AllowedFilter::scope('GetPeriod', 'filters_periods'),

                )
                ->with(['company', 'client'])
                ->latest()
                ->paginate(self::INVOICES_PER_PAGE)
                ->withQueryString();
            // ->appends(request()->query());
            // ->get();
        } else {
            $invoices = InvoiceAvoir::with(['company:id,name,logo', 'client:id,entreprise,email', 'client.emails'])
                ->latest()
                ->paginate(self::INVOICES_PER_PAGE)
                ->withQueryString();
        }
        $clients = app(ClientInterface::class)->getClients(['id', 'uuid', 'entreprise', 'contact']);

        $companies = Company::select(['id', 'name', 'uuid'])->get();

        return view('theme.pages.Commercial.InvoiceAvoir.__datatable.index', compact('invoices', 'clients', 'companies'));
    }

    public function create()
    {
        $invoices = Invoice::query()
            ->doesntHave('avoir')
            ->with(['company:id,name,prefix_invoice_avoir,invoice_avoir_start_number', 'client:id,entreprise'])
            ->latest()
            ->get(['id', 'code', 'full_number', 'company_id', 'client_id']);

        $nextNumbers = $invoices->pluck('company')->filter()->unique('id')
            ->mapWithKeys(fn (Company $company): array => [$company->id => $company->nextInvoiceAvoirNumber()]);

        // For the form: the invoice chosen gives the company, the client and the credit note number.
        $invoiceChoices = $invoices->mapWithKeys(fn (Invoice $invoice): array => [$invoice->id => [
            'company' => $invoice->company_id,
            'companyName' => optional($invoice->company)->name,
            'client' => $invoice->client_id,
            'clientName' => optional($invoice->client)->entreprise,
            'number' => $nextNumbers[$invoice->company_id] ?? null,
        ]]);

        return view('theme.pages.Commercial.InvoiceAvoir.__create_avoir.index', compact('invoices', 'invoiceChoices'));
    }

    public function store(AvoirFormRequest $request)
    {
        // dd($request->all(), "avoir");

        $articles = $request->articles;

        $totalPrice = collect($articles)->map(function ($item) {
            return $item['prix_unitaire'] * $item['quantity'];
        })->sum();

        $invoicesArticles = collect($articles)->map(function ($item) {
            return collect($item)->merge(['montant_ht' => $item['prix_unitaire'] * $item['quantity']]);
        })->toArray();

        $invoice = new InvoiceAvoir;
        $invoice->invoice_number = $request->invoice_number;

        $invoice->invoice_date = $request->date('invoice_date');
        // $invoice->due_date = $request->date('due_date');
        $invoice->payment_mode = $request->payment_mode;

        $invoice->admin_notes = $request->admin_notes;

        $invoice->condition_general = $request->condition_general;

        $invoice->price_ht = $totalPrice;
        $invoice->price_total = $this->caluculateTva($totalPrice);
        $invoice->price_tva = $this->calculateOnlyTva($totalPrice);

        $invoice->client_id = $request->client;
        $invoice->company_id = $request->company;
        $invoice->status = 'paid';

        $invoice->invoice()->associate($request->invoice_number);
        $invoice->invoice_number = $invoice->invoice->code;

        $invoice->save();

        $invoice->articles()->createMany($invoicesArticles);

        $invoice->histories()->create([
            'user_id' => auth()->id(),
            'user' => auth()->user()->full_name,
            'detail' => 'a crée la facture avoir',
            'action' => 'add',
        ]);

        return redirect($invoice->edit_url)->with('success', 'La Facture Avoir a été crée avec success');
    }

    public function single(InvoiceAvoir $invoice)
    {
        $invoice->load('articles');

        return view('theme.pages.Commercial.InvoiceAvoir.__detail.index', compact('invoice'));
    }

    public function edit(InvoiceAvoir $invoice)
    {
        $invoice->load('articles', 'histories');

        return view('theme.pages.Commercial.InvoiceAvoir.__edit.index', compact('invoice'));
    }

    public function update(AvoirUpdateFormRequest $request, InvoiceAvoir $invoice)
    {
        $newArticles = $request->getArticles()->map(function ($item) {
            return collect($item)
                ->merge(['montant_ht' => $item['prix_unitaire'] * $item['quantity']]);
        })->toArray();

        $articlesData = array_filter(array_map('array_filter', $newArticles));

        $totalArticlePrice = collect($newArticles)->map(function ($item) {
            return $item['prix_unitaire'] * $item['quantity'];
        })->sum();

        $totalPrice = $invoice->price_ht + $totalArticlePrice;
        $invoice->price_ht = $totalPrice;
        $invoice->price_total = $this->caluculateTva($totalPrice);
        $invoice->price_tva = $this->calculateOnlyTva($totalPrice);

        $invoice->invoice_date = $request->date('invoice_date');
        // $invoice->due_date = $request->date('due_date');
        $invoice->payment_mode = $request->payment_mode;

        $invoice->admin_notes = $request->admin_notes;
        $invoice->client_notes = $request->client_notes;
        $invoice->condition_general = $request->condition_general;

        $invoice->save();
        if (! empty($articlesData)) {
            $invoice->articles()->createMany($articlesData);
        }
        $invoice->histories()->create([
            'user_id' => auth()->id(),
            'user' => auth()->user()->full_name,
            'detail' => 'a modifier la facture avoir',
            'action' => 'update',
        ]);

        return redirect($invoice->edit_url)->with('success', 'La Facture Avoir a été modifier avec success');
    }

    public function deleteInvoice(Request $request)
    {
        $request->validate(['invoiceId' => 'required|uuid']);

        $invoice = InvoiceAvoir::whereUuid($request->invoiceId)->firstOrFail();

        if ($invoice) {
            $invoice->articles()
                ->where('articleable_type', 'App\Models\Finance\InvoiceAvoir')
                ->where('articleable_id', $invoice->id)
                ->delete();

            $invoice->histories()->create([
                'user_id' => auth()->id(),
                'user' => auth()->user()->full_name,
                'detail' => 'a supprimer la facture avoir',
                'action' => 'delete',
            ]);

            $invoice->delete();

            return redirect(route('commercial:invoices.index.avoir'))->with('success', 'La Facture  a éte supprimer avec success');
        }

        return redirect(route('commercial:invoices.index.avoir'))->with('success', 'erreur . . . ');
    }

    public function deleteArticle(AvoirDeleteArticleFormRequest $request)
    {
        $invoice = InvoiceAvoir::whereUuid($request->invoice)->firstOrFail();
        $article = Article::whereUuid($request->article)->firstOrFail();

        if ($invoice && $article) {
            $totalPrice = $invoice->price_ht;

            $totalArticlePrice = $article->montant_ht;

            $finalPrice = $totalPrice - $totalArticlePrice;

            $article = $invoice->articles()
                ->whereUuid($request->article)
                ->whereId($article->id)
                ->whereArticleableId($invoice->id)
                ->forceDelete();

            if ($article) {
                $invoice->price_ht = $finalPrice;
                $invoice->price_total = $this->caluculateTva($finalPrice);
                $invoice->price_tva = $this->calculateOnlyTva($finalPrice);
                $invoice->save();
            }

            if ($invoice->articles()->count() <= 0) {
                $invoice->price_ht = 0;
                $invoice->price_total = 0;
                $invoice->price_tva = 0;
                $invoice->save();
            }

            $invoice->histories()->create([
                'user_id' => auth()->id(),
                'user' => auth()->user()->full_name,
                'detail' => 'a supprimer un article depuis  la facture avoir',
                'action' => 'delete-article',
            ]);

            return response()->json([
                'success' => 'Record deleted successfully!',
            ]);
        }

        return response()->json([
            'error' => 'problem detected !',
        ]);
    }

    /**
     * Update one article of the credit note and its totals (no discount on credit notes).
     * Replaces the Livewire component commercial.invoice-avoir.edit.edit-article.
     */
    public function updateArticle(Request $request, InvoiceAvoir $invoice, string $article, UpdateDocumentArticle $updateArticle): RedirectResponse
    {
        $article = $invoice->articles()->whereUuid($article)->firstOrFail();

        $data = $request->validateWithBag('article', [
            'designation' => ['required', 'string'],
            'quantity' => ['required', 'integer', 'min:1'],
            'prix_unitaire' => ['required', 'numeric', 'min:0'],
        ]);

        $updateArticle($invoice, $article, $data, withDiscount: false, historyDetail: 'a modifié un article de la facture avoir');

        return redirect($invoice->edit_url)->with('success', "L'article a été modifier avec success");
    }

    public function sendInvoiceAvoir(SendEmailFormRequest $request, SendDocumentByMail $sendDocument): RedirectResponse
    {
        $invoice = InvoiceAvoir::whereUuid($request->invoice)->firstOrFail();

        $sent = $sendDocument($invoice, SendInvoiceAvoirMail::class, [$invoice->client?->email, $request->input('emails.*.*', [])], 'a envoyé la facture avoir par mail');

        return redirect()->back()->with($sendDocument->flash($sent));
    }
}
