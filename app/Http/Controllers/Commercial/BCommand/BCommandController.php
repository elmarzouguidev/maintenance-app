<?php

namespace App\Http\Controllers\Commercial\BCommand;

use App\Http\Controllers\Controller;
use App\Http\Requests\Commercial\BCommand\BCDeleteArticleFormRequest;
use App\Http\Requests\Commercial\BCommand\BCFormRequest;
use App\Http\Requests\Commercial\BCommand\BCUpdateFormRequest;
use App\Models\Finance\Article;
use App\Models\Finance\BCommand;
use App\Services\Commercial\Taxes\TVACalulator;
use Illuminate\Http\Request;

class BCommandController extends Controller
{

    use TVACalulator;

    public function index()
    {

        $commandes = BCommand::with(['provider', 'company'])->get();

        return view('theme.pages.Commercial.BC.index', compact('commandes'));
    }

    public function create()
    {
        return view('theme.pages.Commercial.BC.__create.index');
    }

    public function single(BCommand $command)
    {
        $command->load('articles');

        return view('theme.pages.Commercial.BC.__detail.index', compact('command'));
    }


    public function store(BCFormRequest $request)
    {
        //dd($request->all());

        $articles = $request->articles;

        $totalPrice = collect($articles)->map(function ($item) {
            return $item['prix_unitaire'] * $item['quantity'];
        })->sum();

        $commandArticles = collect($articles)->map(function ($item) {

            return collect($item)->merge(['montant_ht' => $item['prix_unitaire'] * $item['quantity']]);
        })->toArray();

        $command = new BCommand();

        //$command->b_code = $request->b_code;
        $command->date_command = $request->date('date_command');
        $command->date_due = $request->date('date_due');

        $command->admin_notes = $request->admin_notes;

        $command->price_ht = $totalPrice;

        $command->price_total = $this->caluculateTva($totalPrice);
        $command->total_tva = $this->calculateOnlyTva($totalPrice);

        $command->provider()->associate($request->provider);
        $command->company()->associate($request->company);

        $command->provider_code = $command->provider->provider_ref;

        $command->save();

        $command->articles()->createMany($commandArticles);

        return redirect($command->edit_url);
    }

    public function edit(BCommand $command)
    {

        $command->load('articles', 'provider', 'company');

        return view('theme.pages.Commercial.BC.__edit.index', compact('command'));
    }

    public function update(BCUpdateFormRequest $request, $command)
    {

        //dd($request->all());
        $command = BCommand::whereUuid($command)->firstOrFail();

        $newArticles = $request->getArticles()->map(function ($item) {
            return collect($item)
                ->merge(['montant_ht' => $item['prix_unitaire'] * $item['quantity']]);
        })->toArray();

        $totalArticlePrice = collect($newArticles)->map(function ($item) {
            return $item['prix_unitaire'] * $item['quantity'];
        })->sum();

        $totalPrice = $command->price_ht + $totalArticlePrice;

        $command->date_command = $request->date('date_command');
        $command->date_due = $request->date('date_due');

        $command->admin_notes = $request->admin_notes;

        $command->price_ht = $totalPrice;

        $command->price_total = $this->caluculateTva($totalPrice);
        $command->total_tva = $this->calculateOnlyTva($totalPrice);

        $command->provider()->associate($request->provider);
        $command->company()->associate($request->company);

        $command->save();

        $command->articles()->createMany($newArticles);

        return redirect($command->edit_url);
        //return redirect()->back()->with('success', "Le Facture a été modifier avec success");
    }

    public function deleteCommand(Request $request)
    {
        dd($request->all());
        $request->validate(['commandId' => 'required|uuid']);

        $command = BCommand::whereUuid($request->commandId)->firstOrFail();

        if ($command) {

            $command->delete();

            return redirect()->back()->with('success', "La Commande  a éte supprimer avec success");
        }
        return redirect()->back()->with('success', "erreur . . . ");
    }

    public function deleteArticle(BCDeleteArticleFormRequest $request)
    {

        //dd($request->all());

        $command = BCommand::whereUuid($request->command)->firstOrFail();
        $article = Article::whereUuid($request->article)->firstOrFail();

        if ($command && $article) {

            $totalPrice = $command->price_ht;

            $totalArticlePrice = $article->montant_ht;

            $finalPrice = $totalPrice - $totalArticlePrice;

            $command->articles()
                ->whereUuid($request->article)
                ->whereUuid($article->id)
                ->delete();

            $command->price_ht = $finalPrice;
            $command->price_total = $this->caluculateTva($finalPrice);
            $command->total_tva = $this->calculateOnlyTva($finalPrice);
            $command->save();

            return response()->json([
                'success' => 'Record deleted successfully!'
            ]);
        }
        return response()->json([
            'error' => 'problem detected !'
        ]);
    }
}