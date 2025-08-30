<?php

declare(strict_types=1);

namespace App\Http\Controllers\Commercial\BL;

use App\Http\Controllers\Controller;
use App\Http\Requests\Commercial\BCommand\EmailFormRequest;
use App\Http\Requests\Commercial\BL\BLDeleteArticleFormRequest;
use App\Http\Requests\Commercial\BL\BLFormRequest;
use App\Http\Requests\Commercial\BL\BLUpdateFormRequest;
use App\Models\Finance\Article;
use App\Models\Finance\BLivraison;
use App\Models\Finance\Company;
use App\Repositories\Client\ClientInterface;
use App\Services\Commercial\Taxes\TVACalulator;
use App\Services\Mail\CheckConnection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class BLController extends Controller
{
    use TVACalulator;

    public function indexFilter()
    {
        if (request()->has('appFilter') && request()->filled('appFilter')) {
            $commandes = QueryBuilder::for(BLivraison::class)
                ->allowedFilters([
                    //'company_id'
                    //AllowedFilter::exact('etat')
                    AllowedFilter::scope('GetBCDate', 'filters_date_bc'),
                    AllowedFilter::scope('GetCompany', 'filters_companies'),
                    AllowedFilter::scope('GetClient', 'filters_clients'),
                    AllowedFilter::scope('GetStatus', 'filters_status'),
                    AllowedFilter::scope('DateBetween', 'filters_date'),
                    AllowedFilter::scope('GetPeriod', 'filters_periods'),

                ])
                ->with(['company', 'client', 'client.emails'])
                ->paginate(100)
                ->appends(request()->query());
            //->get();
        } else {
            $commandes = BLivraison::with(['client', 'client.emails', 'company'])->get();
        }

        $clients = app(ClientInterface::class)->getClients(['id', 'uuid', 'entreprise', 'contact']);

        $companies = Company::select(['id', 'name', 'uuid'])->get();

        return view('theme.pages.Commercial.BL.index', compact('commandes', 'companies', 'clients'));
    }

    public function index()
    {
        $commandes = BLivraison::with(['provider', 'provider.emails', 'company'])->get();

        return view('theme.pages.Commercial.BL.index', compact('commandes'));
    }

    public function create()
    {
        $this->authorize('create', BLivraison::class);

        return view('theme.pages.Commercial.BL.__create.index');
    }

    public function single(BLivraison $command)
    {
        $command->load('articles');

        return view('theme.pages.Commercial.BL.__detail.index', compact('command'));
    }

    public function store(BLFormRequest $request)
    {
      // dd($request->all());
        $this->authorize('create', BLivraison::class);

        $articles = $request->articles;

        $totalPrice = collect($articles)->map(function ($item) {
            return $item['prix_unitaire'] * $item['quantity'];
        })->sum();

        $commandArticles = collect($articles)->map(function ($item) {
            return collect($item)->merge(['montant_ht' => $item['prix_unitaire'] * $item['quantity']]);
        })->toArray();

        $command = new BLivraison();

        $command->date_bl = $request->date('date_bl');

        $command->bc_number = $request->bc_number;

        $command->condition_general = $request->condition_general ?? '';
        $command->admin_notes = $request->admin_notes;

        $command->price_ht = $totalPrice;

        $command->price_total = $this->caluculateTva($totalPrice);
        $command->price_tva = $this->calculateOnlyTva($totalPrice);

        $command->client()->associate($request->client);

        $command->company()->associate($request->company);

        $command->save();

        $command->articles()->createMany($commandArticles);

        $command->histories()->create([
            'user_id' => auth()->id(),
            'user' => auth()->user()->full_name,
            'detail' => 'a crée le BL',
            'action' => 'add',
        ]);

        return redirect($command->edit_url)->with('success', 'Le BL a été crée avec success');
    }

    public function edit(BLivraison $command)
    {
        $this->authorize('update', $command);

        $command->load('articles', 'client', 'client.emails', 'company', 'histories');

        return view('theme.pages.Commercial.BL.__edit.index', compact('command'));
    }

    public function update(BLUpdateFormRequest $request, BLivraison $command)
    {
        //dd($request->all());

        $this->authorize('update', $command);

        $newArticles = $request->getArticles()->map(function ($item) {
            return collect($item)
                ->merge(['montant_ht' => $item['prix_unitaire'] * $item['quantity']]);
        })->toArray();

        $totalArticlePrice = collect($newArticles)->map(function ($item) {
            return $item['prix_unitaire'] * $item['quantity'];
        })->sum();

        if ($totalArticlePrice !== $command->price_ht && $totalArticlePrice > 0) {
            $totalPrice = $command->price_ht + $totalArticlePrice;
            $command->price_ht = $totalPrice;
            $command->price_total = $this->caluculateTva($totalPrice);
            $command->price_tva = $this->calculateOnlyTva($totalPrice);
        }

        $command->date_bl = $request->date('date_bl');
        $command->bc_number = $request->bc_number;
        $command->condition_general = $request->condition_general ?? '';
        $command->admin_notes = $request->admin_notes;

        $command->client()->associate($request->client);
        $command->company()->associate($request->company);

        $command->save();

        $command->articles()->createMany($newArticles);

        $command->histories()->create([
            'user_id' => auth()->id(),
            'user' => auth()->user()->full_name,
            'detail' => 'a modifier le BL',
            'action' => 'update',
        ]);

        return redirect($command->edit_url)->with('success', 'Le BL a été modifier avec success');
    }

    public function deleteCommand(Request $request)
    {
       // dd($request->all());

        $request->validate(['commandId' => 'required|uuid']);

        $command = BLivraison::whereUuid($request->commandId)->firstOrFail();

        
       // $this->authorize('delete', $command);

        if ($command) {
            $command->articles()->delete();
            //dd($command);
            $command->histories()->create([
                'user_id' => auth()->id(),
                'user' => auth()->user()->full_name,
                'detail' => 'a supprimer le BL',
                'action' => 'delete',
            ]);

            $command->delete();

            return redirect(route('commercial:blivraison.index'))->with('success', 'Le BL  a éte supprimer avec success');
        }

        return redirect(route('commercial:blivraison.index'))->with('success', 'erreur . . . ');
    }

    public function deleteArticle(BLDeleteArticleFormRequest $request)
    {
      
        $command = BLivraison::whereUuid($request->command)->firstOrFail();

        $article = Article::whereUuid($request->article)->firstOrFail();

        //$this->authorize('delete', $command);

        if ($command && $article && $article->articleable()->is($command)) {
            $totalPrice = $command->price_ht;

            $totalArticlePrice = $article->montant_ht;

            $finalPrice = $totalPrice - $totalArticlePrice;

            $article = $command->articles()
                ->whereUuid($request->article)
                ->whereId($article->id)
                ->whereArticleableId($command->id)
                ->forceDelete();

            if ($article) {
                $command->price_ht = $finalPrice;
                $command->price_total = $this->caluculateTva($finalPrice);
                $command->price_tva = $this->calculateOnlyTva($finalPrice);
                $command->save();
            }

            if ($command->articles()->count() <= 0) {
                $command->price_ht = 0;
                $command->price_total = 0;
                $command->price_tva = 0;
                $command->save();
            }
            $command->histories()->create([
                'user_id' => auth()->id(),
                'user' => auth()->user()->full_name,
                'detail' => 'a supprimer un article depuis le BL',
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
}
