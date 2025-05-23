<?php

namespace App\Http\Controllers\Commercial\Estimate;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Http\Requests\Commercial\Estimate\EstimateDeleteRequest;
use App\Http\Requests\Commercial\Estimate\EstimateFormRequest;
use App\Http\Requests\Commercial\Estimate\EstimateUpdateFormRequest;
use App\Http\Requests\Commercial\Estimate\SendEmailFormRequest;
use App\Mail\Commercial\Estimate\DeleteItemMail;
use App\Mail\Commercial\Estimate\SendEstimateMail;
use App\Models\Finance\Article;
use App\Models\Finance\Company;
use App\Models\Finance\Estimate;
use App\Models\Ticket;
use App\Repositories\Client\ClientInterface;
use App\Repositories\Company\CompanyInterface;
use App\Services\Commercial\Remise\RemiseCalculator;
use App\Services\Commercial\Taxes\TVACalulator;
use App\Services\Mail\CheckConnection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class EstimateController extends Controller
{
    use TVACalulator;
    use RemiseCalculator;

    public function indexFilter()
    {
        if (request()->has('appFilter') && request()->filled('appFilter')) {
            $estimates = QueryBuilder::for(Estimate::class)
                ->allowedFilters([
                    AllowedFilter::scope('GetEstimateDate', 'filters_date_estimate'),
                    AllowedFilter::scope('GetCompany', 'filters_companies'),
                    AllowedFilter::scope('GetStatus', 'filters_status'),
                    AllowedFilter::scope('GetClient', 'filters_clients'),
                    AllowedFilter::scope('GetSend', 'filters_send'),
                    AllowedFilter::scope('DateBetween', 'filters_date'),
                ])
                ->with(['company:id,name,logo', 'client:id,entreprise,email', 'client.emails', 'ticket', 'tickets'])
                ->withCount('invoice')
                ->withCount('ticket')
                ->withCount('tickets')
                ->paginate(600)
                
                ->appends(request()->query());
            //->get();
        } else {
            $estimates = Estimate::with(['company:id,name,logo', 'client:id,entreprise,email', 'client.emails', 'ticket', 'tickets'])
                ->latest()
                ->withCount('invoice')
                ->withCount('ticket')
                ->withCount('tickets')
                
                ->get();
               
                
        }

        $clients = app(ClientInterface::class)->getClients(['id', 'uuid', 'entreprise', 'contact']);

        $companies = Company::select(['id', 'name', 'uuid'])->get();

        return view('theme.pages.Commercial.Estimate.index', compact('estimates', 'companies', 'clients'));
    }

    public function index()
    {
        $estimates = Estimate::with(['company:id,name,logo', 'client:id,entreprise,email', 'client.emails'])
            ->withCount('invoice')
            //->paginate(20);
            ->get();

        return view('theme.pages.Commercial.Estimate.index', compact('estimates'));
    }

    public function create()
    {
        $this->authorize('create', Estimate::class);
        // $clients = app(ClientInterface::class)->getClients(['id', 'entreprise', 'contact']);

        //$companies = app(CompanyInterface::class)->getCompanies(['id', 'name']);

        $tickets = Ticket::all();

        return view('theme.pages.Commercial.Estimate.__create.index', compact('tickets'));
    }

    public function createFromTicket(Request $request, $ticket)
    {
        $this->authorize('create', Estimate::class);
        //dd('yes from ticket');
        validator($request->route()->parameters(), [

            'ticket' => ['required', 'uuid'],

        ])->validate();

        $ticket = Ticket::whereUuid($ticket)->with('client')->firstOrFail();

        $companies = app(CompanyInterface::class)->getCompanies(['id', 'name']);

        return view('theme.pages.Commercial.Estimate.__create_from_ticket.index', compact('ticket', 'companies'));
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
                //dd('ohoer');
                $itemPrice = $item['prix_unitaire'] * $item['quantity'];
                $finalePrice = $this->caluculateRemise($itemPrice, $item['remise']);
                $tauxRemise = $this->calculateOnlyRemise($itemPrice, $item['remise']);

                return collect($item)->merge(['montant_ht' => $finalePrice, 'taux_remise' => $tauxRemise]);
            }

            return collect($item)->merge(['remise' => '0', 'montant_ht' => $item['prix_unitaire'] * $item['quantity']]);
        })->toArray();

        //dd($estimateArticles);

        $estimate = new Estimate();

        $estimate->estimate_date = $request->date('estimate_date');
        $estimate->due_date = $request->date('due_date');
        $estimate->payment_mode = $request->payment_mode;

        $estimate->admin_notes = $request->admin_notes;

        $estimate->condition_general = $request->condition_general;

        $estimate->price_ht = $totalPriceRemise;

        //$estimate->ht_price_remise = $totalPrice;

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
                    'description' => __('status.history.' . Status::EN_ATTENTE_DE_BON_DE_COMMAND, ['user' => auth()->user()->full_name, 'number' => $estimate->code]),
                ]
            );
        }

        if (isset($request->tickets) && is_array($request->tickets) && count($request->tickets)) {
            //dd($request->tickets);
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

        /* $client = $estimate->client()->first();

        $tickets = $client->tickets()
        //->whereDoesntHave('invoice')
        //->whereDoesntHave('invoices')
        ->get();*/

        $estimate->load('articles', 'tickets:id,code,uuid,code_retour,is_retour', 'histories')->loadCount('invoice', 'tickets');

        return view('theme.pages.Commercial.Estimate.__edit.index', compact('estimate'));
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

        //if ($totalPriceRemise !== $estimate->price_ht && $totalPriceRemise > 0) {

        $totalPrice = $estimate->price_ht + $totalPriceRemise;
        $estimate->price_ht = $totalPrice;
        $estimate->price_total = $this->caluculateTva($totalPrice);
        $estimate->price_tva = $this->calculateOnlyTva($totalPrice);
        //$estimate->ht_price_remise = $totalArticlePrice;

        // }

        $estimate->estimate_date = $request->date('estimate_date');
        $estimate->due_date = $request->date('due_date');

        $estimate->payment_mode = $request->payment_mode;

        $estimate->admin_notes = $request->admin_notes;

        $estimate->condition_general = $request->condition_general;

        $estimate->save();

        if (!empty($articlesData)) {
            $estimate->articles()->createMany($articlesData);
        }

        if (isset($request->tickets) && is_array($request->tickets) && count($request->tickets)) {
            //dd($request->tickets);
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

            //if (CheckConnection::isConnected()) {

            // Mail::to($estimate->company->email)->send(New DeleteItemMail($estimate));

            // if (empty(Mail::failures())) {

            $estimate->delete();

            return redirect(route('commercial:estimates.index'))->with('success', 'Le devis  a éte supprimer avec success');
            //}
            //}
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

    public function createInvoice(Estimate $estimate)
    {
        //dd('OoOKK');
        $estimate->load('articles', 'tickets:id,code,is_retour,code_retour', 'client:id,entreprise', 'company:id,name,prefix_invoice,invoice_start_number');
        $estimate->loadCount('tickets', 'ticket');

        return view('theme.pages.Commercial.Invoice.__create_from_estimate.index', compact('estimate'));
    }

    public function sendEstimate(SendEmailFormRequest $request)
    {
        $estimate = Estimate::whereUuid($request->estimater)->first();
        //dd($request->input('emails.*.*'),$request->collect('emails.*.*'));
        $emails = $request->input('emails.*.*');
        if (CheckConnection::isConnected()) {
            if (isset($emails) && is_array($emails) && count($emails)) {
                foreach ($emails as $email) {
                    Mail::to($email)->send(new SendEstimateMail($estimate));
                }
            }

            Mail::to($estimate->client->email)->send(new SendEstimateMail($estimate));

            if (empty(Mail::failures())) {
                $estimate->update(['is_send' => !$estimate->is_send]);

                //$estimate->tickets()->update(['status' => Status::EN_ATTENTE_DE_BON_DE_COMMAND]);

                $estimate->histories()->create([
                    'user_id' => auth()->id(),
                    'user' => auth()->user()->full_name,
                    'detail' => 'A envoyer le devis par mail',
                    'action' => 'send',
                ]);

                return redirect()->back()->with('success', "l'email a été envoyé avec succès");
            }
        }

        return redirect()->back()->with('error', 'Email not send');
    }
}
