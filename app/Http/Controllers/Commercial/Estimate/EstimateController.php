<?php

namespace App\Http\Controllers\Commercial\Estimate;

use App\Actions\Commercial\SendDocumentByMail;
use App\Actions\Commercial\UpdateDocumentArticle;
use App\Constants\Etat;
use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Http\Requests\Commercial\Estimate\EstimateDeleteRequest;
use App\Http\Requests\Commercial\Estimate\EstimateFormRequest;
use App\Http\Requests\Commercial\Estimate\EstimateUpdateFormRequest;
use App\Http\Requests\Commercial\Estimate\SendEmailFormRequest;
use App\Mail\Commercial\Estimate\DeleteItemMail;
use App\Mail\Commercial\Estimate\SendEstimateMail;
use App\Models\Client;
use App\Models\Finance\Article;
use App\Models\Finance\Company;
use App\Models\Finance\Estimate;
use App\Models\Ticket;
use App\Repositories\Client\ClientInterface;
use App\Services\Commercial\Remise\RemiseCalculator;
use App\Services\Commercial\Taxes\TVACalulator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class EstimateController extends Controller
{
    private const ESTIMATES_PER_PAGE = 30;

    use RemiseCalculator;
    use TVACalulator;

    public function indexFilter()
    {
        if (request()->has('appFilter') && request()->filled('appFilter')) {
            $estimates = QueryBuilder::for(Estimate::class)
                ->allowedFilters(
                    AllowedFilter::scope('GetEstimateDate', 'filters_date_estimate'),
                    AllowedFilter::scope('GetCompany', 'filters_companies'),
                    AllowedFilter::scope('GetStatus', 'filters_status'),
                    AllowedFilter::scope('GetClient', 'filters_clients'),
                    AllowedFilter::scope('GetSend', 'filters_send'),
                    AllowedFilter::scope('DateBetween', 'filters_date'),
                    AllowedFilter::scope('GetPeriod', 'filters_periods'),
                )
                ->with(['company:id,name,logo', 'client:id,entreprise,email', 'client.emails', 'ticket', 'tickets'])
                ->withCount('invoice')
                ->withCount('ticket')
                ->withCount('tickets')
                ->latest()
                ->paginate(self::ESTIMATES_PER_PAGE)
                ->withQueryString();
            // ->get();
        } else {
            $estimates = Estimate::with(['company:id,name,logo', 'client:id,entreprise,email', 'client.emails', 'ticket', 'tickets'])
                ->latest()
                ->withCount('invoice')
                ->withCount('ticket')
                ->withCount('tickets')
                ->paginate(self::ESTIMATES_PER_PAGE)
                ->withQueryString();

        }

        $clients = app(ClientInterface::class)->getClients(['id', 'uuid', 'entreprise', 'contact']);

        $companies = Company::select(['id', 'name', 'uuid'])->get();

        return view('theme.pages.Commercial.Estimate.index', compact('estimates', 'companies', 'clients'));
    }

    public function index()
    {
        $estimates = Estimate::with(['company:id,name,logo', 'client:id,entreprise,email', 'client.emails'])
            ->withCount('invoice')
            // ->paginate(20);
            ->get();

        return view('theme.pages.Commercial.Estimate.index', compact('estimates'));
    }

    public function create()
    {
        $this->authorize('create', Estimate::class);

        return view('theme.pages.Commercial.Estimate.__create.index', $this->createFormData());
    }

    public function createFromTicket(Request $request, $ticket)
    {
        $this->authorize('create', Estimate::class);

        validator($request->route()->parameters(), [
            'ticket' => ['required', 'uuid'],
        ])->validate();

        $ticket = Ticket::whereUuid($ticket)->with('client')->firstOrFail();

        return view('theme.pages.Commercial.Estimate.__create.index', [...$this->createFormData(), 'ticket' => $ticket]);
    }

    /**
     * The tickets of a client that can go on a new estimate (repairable, waiting for an estimate,
     * on no estimate yet), for the create form. Replaces the Livewire component
     * commercial.estimate.create.tickets.
     */
    public function clientTickets(int $client): JsonResponse
    {
        $tickets = Client::findOrFail($client)->tickets()
            ->where('etat', Etat::REPARABLE)
            ->where('status', Status::EN_ATTENTE_DE_DEVIS)
            ->doesntHave('estimate')
            ->doesntHave('estimates')
            ->get(['id', 'code']);

        return response()->json($tickets->map(fn (Ticket $ticket): array => ['id' => $ticket->id, 'code' => $ticket->code])->values());
    }

    /**
     * Companies with the number of their next estimate (display only: Estimate::creating sets it)
     * and the clients, for the create form.
     *
     * @return array{companies: Collection<int, Company>, nextNumbers: Collection<int, string>, clients: Collection<int, string>, ticket: null}
     */
    private function createFormData(): array
    {
        $companies = Company::query()->get(['id', 'name', 'prefix_estimate', 'estimate_start_number']);

        return [
            'companies' => $companies,
            'nextNumbers' => $companies->mapWithKeys(fn (Company $company): array => [$company->id => $company->nextEstimateNumber()]),
            'clients' => Client::query()->orderBy('entreprise')->pluck('entreprise', 'id'),
            'ticket' => null,
        ];
    }

    public function store(EstimateFormRequest $request)
    {
        $this->authorize('create', Estimate::class);

        $articles = $request->articles;

        $totalPriceRemise = collect($articles)->map(function ($item) {
            if ($item['remise'] && $item['remise'] > 0 && $item['remise'] !== 0) {
                $itemPrice = $item['prix_unitaire'] * $item['quantity'];
                $finalePrice = $this->caluculateRemise($itemPrice, $item['remise']);

                return $finalePrice;
            }

            return $item['prix_unitaire'] * $item['quantity'];
        })->sum();

        $estimateArticles = collect($articles)->map(function ($item) {
            if ($item['remise'] && $item['remise'] > 0 && $item['remise'] !== 0) {
                // dd('ohoer');
                $itemPrice = $item['prix_unitaire'] * $item['quantity'];
                $finalePrice = $this->caluculateRemise($itemPrice, $item['remise']);
                $tauxRemise = $this->calculateOnlyRemise($itemPrice, $item['remise']);

                return collect($item)->merge(['montant_ht' => $finalePrice, 'taux_remise' => $tauxRemise]);
            }

            return collect($item)->merge(['remise' => '0', 'montant_ht' => $item['prix_unitaire'] * $item['quantity']]);
        })->toArray();

        // dd($estimateArticles);

        $estimate = new Estimate;

        $estimate->estimate_date = $request->date('estimate_date');
        $estimate->due_date = $request->date('due_date');
        $estimate->payment_mode = $request->payment_mode;

        $estimate->admin_notes = $request->admin_notes;

        $estimate->condition_general = $request->condition_general;

        $estimate->price_ht = $totalPriceRemise;

        // $estimate->ht_price_remise = $totalPrice;

        $estimate->price_total = $this->caluculateTva($totalPriceRemise);
        $estimate->price_tva = $this->calculateOnlyTva($totalPriceRemise);

        $estimate->client_id = $request->client;
        $estimate->ticket_id = $request->ticket;
        $estimate->company_id = $request->company;

        $estimate->save();

        $estimate->articles()->createMany($estimateArticles);

        if ($request->ticket && $request->ticket > 0) {
            $estimate->ticket()->update(['status' => Status::EN_ATTENTE_DE_BON_DE_COMMAND]);

            $estimate->ticket->statuses()->attach(
                Status::EN_ATTENTE_DE_BON_DE_COMMAND,
                [
                    'user_id' => auth()->id(),
                    'start_at' => now(),
                    'description' => __('status.history.'.Status::EN_ATTENTE_DE_BON_DE_COMMAND, ['user' => auth()->user()->full_name, 'number' => $estimate->code]),
                ]
            );
        }

        if (isset($request->tickets) && is_array($request->tickets) && count($request->tickets)) {
            // dd($request->tickets);
            $estimate->tickets()->attach($request->tickets);
            $estimate->tickets()->update(['status' => Status::EN_ATTENTE_DE_BON_DE_COMMAND]);
        }

        $estimate->histories()->create([
            'user_id' => auth()->id(),
            'user' => auth()->user()->full_name,
            'detail' => 'a crée le DEVIS ',
            'action' => 'add',
        ]);

        return redirect($estimate->edit_url)->with('success', 'Le Devis a éte crée avec success');
    }

    public function single(Estimate $estimate)
    {
        $estimate->load('articles');

        return view('theme.pages.Commercial.Estimate.__detail.index', compact('estimate'));
    }

    public function edit(Estimate $estimate)
    {
        $this->authorize('update', $estimate);

        $client = $estimate->client()->first();

        $tickets = $client->tickets()
                // ->whereDoesntHave('invoice')
                // ->whereDoesntHave('invoices')
            ->get();

        $estimate->load('articles', 'tickets:id,code,uuid,code_retour,is_retour', 'histories')->loadCount('invoice', 'tickets');

        return view('theme.pages.Commercial.Estimate.__edit.index', compact('estimate', 'tickets'));
    }

    public function update(EstimateUpdateFormRequest $request, Estimate $estimate)
    {
        $this->authorize('update', $estimate);

        $newArticles = $request->getNewArticles()->map(function ($item) {
            if ($item['remise'] && $item['remise'] > 0 && $item['remise'] !== 0) {
                $itemPrice = $item['prix_unitaire'] * $item['quantity'];
                $finalePrice = $this->caluculateRemise($itemPrice, $item['remise']);
                $tauxRemise = $this->calculateOnlyRemise($itemPrice, $item['remise']);

                return collect($item)->merge(['montant_ht' => $finalePrice, 'taux_remise' => $tauxRemise]);
            }

            return collect($item)->merge(['remise' => '0', 'montant_ht' => $item['prix_unitaire'] * $item['quantity']]);
        })->toArray();

        $articlesData = array_filter(array_map('array_filter', $newArticles));

        $totalArticlePrice = collect($newArticles)->map(function ($item) {
            return $item['prix_unitaire'] * $item['quantity'];
        })->sum();

        $totalPriceRemise = collect($newArticles)->map(function ($item) {
            if ($item['remise'] && $item['remise'] > 0 && $item['remise'] !== 0) {
                $itemPrice = $item['prix_unitaire'] * $item['quantity'];
                $finalePrice = $this->caluculateRemise($itemPrice, $item['remise']);

                return $finalePrice;
            }

            return $item['prix_unitaire'] * $item['quantity'];
        })->sum();

        // if ($totalPriceRemise !== $estimate->price_ht && $totalPriceRemise > 0) {

        $totalPrice = $estimate->price_ht + $totalPriceRemise;
        $estimate->price_ht = $totalPrice;
        $estimate->price_total = $this->caluculateTva($totalPrice);
        $estimate->price_tva = $this->calculateOnlyTva($totalPrice);
        // $estimate->ht_price_remise = $totalArticlePrice;

        // }

        $estimate->estimate_date = $request->date('estimate_date');
        $estimate->due_date = $request->date('due_date');

        $estimate->payment_mode = $request->payment_mode;

        $estimate->admin_notes = $request->admin_notes;

        $estimate->condition_general = $request->condition_general;

        $estimate->save();

        if (! empty($articlesData)) {
            $estimate->articles()->createMany($articlesData);
        }

        if (isset($request->tickets) && is_array($request->tickets) && count($request->tickets)) {
            // dd($request->tickets);
            $estimate->tickets()->sync($request->tickets);
        }

        $estimate->histories()->create([
            'user_id' => auth()->id(),
            'user' => auth()->user()->full_name,
            'detail' => 'a modifier le DEVIS ',
            'action' => 'update',
        ]);

        return redirect($estimate->edit_url)->with('success', 'Le devis a été modifier avec success');
    }

    public function deleteEstimate(Request $request)
    {
        // dd($request->all(),"Yes delete");
        $request->validate(['estimateId' => 'required|uuid']);

        $estimate = Estimate::whereUuid($request->estimateId)->firstOrFail();
        $this->authorize('delete', $estimate);
        if ($estimate) {
            $estimate->articles()->delete();

            $estimate->ticket()->update(['status' => Status::EN_ATTENTE_DE_DEVIS]);

            $estimate->tickets->each->update(['status' => Status::EN_ATTENTE_DE_DEVIS]);

            $estimate->tickets()->detach();

            $estimate->histories()->delete();

            /*$estimate->histories()->create([
                'user_id' => auth()->id(),
                'user' => auth()->user()->full_name,
                'detail' => 'a supprimer le DEVIS ',
                'action' => 'delete'
            ]);*/

            // if (CheckConnection::isConnected()) {

            // Mail::to($estimate->company->email)->send(New DeleteItemMail($estimate));

            // if (empty(Mail::failures())) {

            $estimate->delete();

            return redirect(route('commercial:estimates.index'))->with('success', 'Le devis  a éte supprimer avec success');
            // }
            // }
        }

        return redirect(route('commercial:estimates.index'))->with('success', 'erreur . . . ');
    }

    public function deleteArticle(EstimateDeleteRequest $request)
    {
        $estimate = Estimate::whereUuid($request->estimate)->firstOrFail();
        $article = Article::whereUuid($request->article)->firstOrFail();

        if ($estimate && $article) {
            $totalPrice = $estimate->price_ht;

            $totalArticlePrice = $article->montant_ht;

            $finalPrice = $totalPrice - $totalArticlePrice;

            $estimate->articles()
                ->whereUuid($request->article)
                ->whereId($article->id)
                ->whereArticleableId($estimate->id)
                ->forceDelete();

            if ($article) {
                $estimate->price_ht = $finalPrice;
                $estimate->price_total = $this->caluculateTva($finalPrice);
                $estimate->price_tva = $this->calculateOnlyTva($finalPrice);
                $estimate->save();
            }
            if ($estimate->articles()->count() <= 0) {
                $estimate->price_ht = 0;
                $estimate->price_total = 0;
                $estimate->price_tva = 0;
                $estimate->save();
            }
            $estimate->histories()->create([
                'user_id' => auth()->id(),
                'user' => auth()->user()->full_name,
                'detail' => 'a supprimer un article depuis le DEVIS ',
                'action' => 'delete',
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
     * The invoice form filled from the estimate (company, client, tickets, articles).
     */
    public function createInvoice(Estimate $estimate)
    {
        $estimate->load('articles', 'tickets:id,code,is_retour,code_retour', 'client:id,entreprise', 'company:id,name,prefix_invoice,invoice_start_number');

        return view('theme.pages.Commercial.Invoice.__create.index', [
            'estimate' => $estimate,
            'companies' => collect([$estimate->company]),
            'nextNumbers' => [$estimate->company_id => $estimate->company->nextInvoiceNumber()],
            'clients' => collect(),
            'ticket' => null,
        ]);
    }

    /**
     * Update one article of the estimate and the estimate totals. Replaces the Livewire component
     * commercial.estimate.edit.edit-article.
     */
    public function updateArticle(Request $request, Estimate $estimate, string $article, UpdateDocumentArticle $updateArticle): RedirectResponse
    {
        $this->authorize('update', $estimate);

        $article = $estimate->articles()->whereUuid($article)->firstOrFail();

        $data = $request->validateWithBag('article', [
            'designation' => ['required', 'string'],
            'quantity' => ['required', 'integer', 'min:1'],
            'prix_unitaire' => ['required', 'numeric', 'min:0'],
            'remise' => ['nullable', 'numeric', 'min:0', 'max:100'],
        ]);

        $updateArticle($estimate, $article, $data, withDiscount: true, historyDetail: 'a modifié un article du devis');

        return redirect($estimate->edit_url)->with('success', "L'article a été modifier avec success");
    }

    public function sendEstimate(SendEmailFormRequest $request, SendDocumentByMail $sendDocument): RedirectResponse
    {
        $estimate = Estimate::whereUuid($request->estimater)->firstOrFail();

        $sent = $sendDocument($estimate, SendEstimateMail::class, [$estimate->client?->email, $request->input('emails.*.*', [])], 'a envoyé le devis par mail');

        return redirect()->back()->with($sendDocument->flash($sent));
    }
}
