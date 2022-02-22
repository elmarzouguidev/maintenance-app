<?php

namespace App\Http\Controllers\Commercial\Estimate;

use App\Http\Controllers\Controller;
use App\Http\Requests\Commercial\Estimate\EstimateDeleteRequest;
use App\Http\Requests\Commercial\Estimate\EstimateFormRequest;
use App\Http\Requests\Commercial\Estimate\EstimateUpdateFormRequest;
use App\Models\Finance\Article;
use App\Models\Finance\Estimate;
use App\Models\Ticket;
use App\Repositories\Company\CompanyInterface;
use App\Services\Commercial\Taxes\TVACalulator;
use Illuminate\Http\Request;

class EstimateController extends Controller
{

    use TVACalulator;

    public function index()
    {
        $estimates = Estimate::with(['company:id,name,logo', 'client:id,entreprise'])
            ->withCount('invoice')
            ->paginate(20);

        return view('theme.pages.Commercial.Estimate.index', compact('estimates'));
    }


    public function create()
    {

        // $clients = app(ClientInterface::class)->getClients(['id', 'entreprise', 'contact']);

        //$companies = app(CompanyInterface::class)->getCompanies(['id', 'name']);

        $tickets = Ticket::all();

        return view('theme.pages.Commercial.Estimate.__create.index', compact('tickets'));
    }

    public function createFromTicket(Request $request, $ticket)
    {
        validator($request->route()->parameters(), [

            'ticket' => ['required', 'uuid']

        ])->validate();

        $ticket = Ticket::whereUuid($ticket)->with('client')->firstOrFail();

        $companies = app(CompanyInterface::class)->getCompanies(['id', 'name']);

        return view('theme.pages.Commercial.Estimate.__create_from_ticket.index', compact('ticket', 'companies'));
    }

    public function store(EstimateFormRequest $request)
    {
       // dd($request->all());

        $articles = $request->articles;

        $totalPrice = collect($articles)->map(function ($item) {
            return $item['prix_unitaire'] * $item['quantity'];
        })->sum();

        $estimateArticles = collect($articles)->map(function ($item) {
            return collect($item)->merge(['montant_ht' => $item['prix_unitaire'] * $item['quantity']]);
        })->toArray();

        $estimate = new Estimate();

        $estimate->estimate_date = $request->date('estimate_date');
        $estimate->due_date = $request->date('due_date');

        $estimate->admin_notes = $request->admin_notes;
        //$estimate->client_notes = $request->client_notes;
        $estimate->condition_general = $request->condition_general;

        $estimate->price_ht = $totalPrice;
        $estimate->price_total = $this->caluculateTva($totalPrice);
        $estimate->price_tva = $this->calculateOnlyTva($totalPrice);

        $estimate->client_id = $request->client;
        //$estimate->ticket_id = $request->ticket;
        $estimate->company_id = $request->company;

        $estimate->save();
        $estimate->articles()->createMany($estimateArticles);
        $estimate->tickets()->attach($request->tickets);

        return redirect($estimate->edit_url);
    }

    public function single(Estimate $estimate)
    {
        $estimate->load('articles');

        return view('theme.pages.Commercial.Estimate.__detail.index', compact('estimate'));
    }

    public function edit(Estimate $estimate)
    {

        $estimate->load('articles','tickets');

        return view('theme.pages.Commercial.Estimate.__edit.index', compact('estimate'));
    }

    public function update(EstimateUpdateFormRequest $request, Estimate $estimate)
    {

        //dd($request->all(), "update");

        $newArticles = $request->getArticles()->map(function ($item) {

            return collect($item)
                ->merge(['montant_ht' => $item['prix_unitaire'] * $item['quantity']]);
        })->toArray();

        $totalArticlePrice = collect($newArticles)->map(function ($item) {
            return $item['prix_unitaire'] * $item['quantity'];
        })->sum();

        if ($totalArticlePrice !== $estimate->price_ht && $totalArticlePrice > 0) {
            $totalPrice = $estimate->price_ht + $totalArticlePrice;
            $estimate->price_ht = $totalPrice;
            $estimate->price_total = $this->caluculateTva($totalPrice);
            $estimate->price_tva = $this->calculateOnlyTva($totalPrice);
        }

        $estimate->estimate_date = $request->date('estimate_date');
        $estimate->due_date = $request->date('due_date');

        $estimate->admin_notes = $request->admin_notes;
        //$estimate->client_notes = $request->client_notes;
        $estimate->condition_general = $request->condition_general;

        $estimate->save();
        $estimate->articles()->createMany($newArticles);
        $estimate->tickets()->sync($request->tickets);

        return redirect($estimate->edit_url)->with('success', "Le devis a été modifier avec success");
    }

    public function deleteEstimate(Request $request)
    {
        // dd($request->all());
        $request->validate(['estimateId' => 'required|uuid']);

        $estimate = Estimate::whereUuid($request->estimateId)->firstOrFail();

        if ($estimate) {

            $estimate->articles()->delete();
            $estimate->delete();

            return redirect()->back()->with('success', "Le devis  a éte supprimer avec success");
        }
        return redirect()->back()->with('success', "erreur . . . ");
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

            return response()->json([
                'success' => 'Record deleted successfully!'
            ]);
        }
        return response()->json([
            'error' => 'problem detected !'
        ]);
    }

    public function createInvoice(Estimate $estimate)
    {
        //dd('OoOKK');
        $estimate->load('articles', 'client:id,entreprise', 'company:id,name');

        return view('theme.pages.Commercial.Invoice.__create_from_estimate.index', compact('estimate'));
    }

    public function sendEstimate(Request $request)
    {
        $request->validate(['estimater' => 'required|uuid']);

        $estimate = Estimate::whereUuid($request->estimater)->first();

        $estimate->update(['is_send' => !$estimate->is_send]);

        return redirect()->back();
    }
}
