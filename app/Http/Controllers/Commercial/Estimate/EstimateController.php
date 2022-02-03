<?php

namespace App\Http\Controllers\Commercial\Estimate;

use App\Http\Controllers\Controller;
use App\Http\Requests\Commercial\Estimate\EstimateDeleteRequest;
use App\Http\Requests\Commercial\Estimate\EstimateFormRequest;
use App\Http\Requests\Commercial\Estimate\EstimateUpdateFormRequest;
use App\Models\Finance\Article;
use App\Models\Finance\Estimate;
use App\Services\Commercial\Taxes\TVACalulator;
use Illuminate\Http\Request;

class EstimateController extends Controller
{

    use TVACalulator;


    public function index()
    {
        $estimates = Estimate::with(['company', 'client'])->paginate(20);

        return view('theme.pages.Commercial.Estimate.index', compact('estimates'));
    }


    public function create()
    {

        // $clients = app(ClientInterface::class)->getClients(['id', 'entreprise', 'contact']);

        //$companies = app(CompanyInterface::class)->getCompanies(['id', 'name']);

        return view('theme.pages.Commercial.Estimate.__create.index');
    }

    public function store(EstimateFormRequest $request)
    {
        //dd($request->all());

        $articles = $request->articles;

        $totalPrice = collect($articles)->map(function ($item) {

            return $item['prix_unitaire'] * $item['quantity'];
        })->sum();

        $estimateArticles = collect($articles)->map(function ($item) {

            return collect($item)->merge(['montant_ht' => $item['prix_unitaire'] * $item['quantity']]);
        })->toArray();

        $estimate = new Estimate();

        //$invoice->invoice_code = $request->invoice_code;
        $estimate->estimate_date = $request->date('estimate_date');
        $estimate->date_due = $request->date('date_due');

        /*$estimate->admin_notes = $request->admin_notes;
        $estimate->client_notes = $request->client_notes;
        $estimate->condition_general = $request->condition_general;*/

        $estimate->price_ht = $totalPrice;
        $estimate->price_total = $this->caluculateTva($totalPrice);
        $estimate->total_tva = $this->calculateOnlyTva($totalPrice);

        $estimate->client()->associate($request->client);
        $estimate->ticket()->associate($request->ticket);
        $estimate->company()->associate($request->company);

        $estimate->save();

        $estimate->articles()->createMany($estimateArticles);

        return redirect($estimate->edit_url);
    }

    public function single(Estimate $estimate)
    {
        $estimate->load('articles');

        return view('theme.pages.Commercial.Estimate.__detail.index', compact('estimate'));
    }

    public function edit(Estimate $estimate)
    {

        $estimate->load('articles');

        return view('theme.pages.Commercial.Estimate.__edit.index', compact('estimate'));
    }

    public function update(EstimateUpdateFormRequest $request, $estimate)
    {

        //dd($request->all(),"update");

        $estimate = Estimate::whereUuid($estimate)->firstOrFail();

        $newArticles = $request->getArticles()->map(function ($item) {

            return collect($item)
                ->merge(['montant_ht' => $item['prix_unitaire'] * $item['quantity']]);
        })->toArray();

        $totalArticlePrice = collect($newArticles)->map(function ($item) {
            return $item['prix_unitaire'] * $item['quantity'];
        })->sum();

        $totalPrice = $estimate->price_ht + $totalArticlePrice;

        //dd($newArticles,"update");

        $estimate->estimate_date = $request->date('estimate_date');
        $estimate->date_due = $request->date('date_due');
        $estimate->price_ht = $totalPrice;
        $estimate->price_total = $this->caluculateTva($totalPrice);
        $estimate->total_tva = $this->calculateOnlyTva($totalPrice);

        /*$estimate->admin_notes = $request->admin_notes;
        $estimate->client_notes = $request->client_notes;
        $estimate->condition_general = $request->condition_general;*/


        $estimate->save();

        $estimate->articles()->createMany($newArticles);

        return redirect($estimate->edit_url);
        //return redirect()->back()->with('success', "Le Facture a été modifier avec success");
    }

    public function deleteEstimate(Request $request)
    {
        // dd($request->all());
        $request->validate(['estimateId' => 'required|uuid']);

        $estimate = Estimate::whereUuid($request->estimateId)->firstOrFail();

        if ($estimate) {

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
                ->delete();

            $estimate->price_ht = $finalPrice;
            $estimate->price_total = $this->caluculateTva($finalPrice);
            $estimate->total_tva = $this->calculateOnlyTva($finalPrice);
            $estimate->save();

            return response()->json([
                'success' => 'Record deleted successfully!'
            ]);
        }
        return response()->json([
            'error' => 'problem detected !'
        ]);
    }


    public function estimateStatus(Request $request)
    {
    }

    public function createInvoice(Estimate $estimate)
    {
        $estimate->load('articles', 'client', 'company');

        return view('theme.pages.Commercial.Invoice.__create_from_estimate.index', compact('estimate'));
    }
}
