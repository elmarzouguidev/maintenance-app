<?php

namespace App\Http\Controllers\Commercial\BCommand;

use App\Actions\Commercial\SendDocumentByMail;
use App\Actions\Commercial\UpdateDocumentArticle;
use App\Http\Controllers\Controller;
use App\Http\Requests\Commercial\BCommand\BCDeleteArticleFormRequest;
use App\Http\Requests\Commercial\BCommand\BCFormRequest;
use App\Http\Requests\Commercial\BCommand\BCUpdateFormRequest;
use App\Http\Requests\Commercial\BCommand\EmailFormRequest;
use App\Mail\Commercial\BC\SendBCMail;
use App\Models\Finance\Article;
use App\Models\Finance\BCommand;
use App\Models\Finance\Company;
use App\Models\Finance\Provider;
use App\Repositories\Provider\ProviderInterface;
use App\Services\Commercial\Taxes\TVACalulator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class BCommandController extends Controller
{
    use TVACalulator;

    private const COMMANDS_PER_PAGE = 30;

    public function indexFilter()
    {
        if (request()->has('appFilter') && request()->filled('appFilter')) {
            $commandes = QueryBuilder::for(BCommand::class)
                ->allowedFilters(
                    // 'company_id'
                    // AllowedFilter::exact('etat')
                    AllowedFilter::scope('GetBCDate', 'filters_date_bc'),
                    AllowedFilter::scope('GetCompany', 'filters_companies'),
                    AllowedFilter::scope('GetProvider', 'filters_providers'),
                    AllowedFilter::scope('GetStatus', 'filters_status'),
                    AllowedFilter::scope('DateBetween', 'filters_date'),
                    AllowedFilter::scope('GetPeriod', 'filters_periods'),

                )
                ->with(['company', 'provider'])
                ->latest()
                ->paginate(self::COMMANDS_PER_PAGE)
                ->withQueryString();
        } else {
            $commandes = BCommand::with(['provider', 'company'])
                ->latest()
                ->paginate(self::COMMANDS_PER_PAGE)
                ->withQueryString();
        }

        $providers = app(ProviderInterface::class)->Providers(['id', 'uuid', 'entreprise', 'contact']);

        $companies = Company::select(['id', 'name', 'uuid'])->get();

        return view('theme.pages.Commercial.BC.index', compact('commandes', 'companies', 'providers'));
    }

    public function index()
    {
        $commandes = BCommand::with(['provider', 'provider.emails', 'company'])->get();

        return view('theme.pages.Commercial.BC.index', compact('commandes'));
    }

    public function create()
    {
        $this->authorize('create', BCommand::class);

        $companies = Company::query()->get(['id', 'name', 'prefix_bcommand', 'bcommand_start_number']);

        // The number the next purchase order of each company will get (display only: BCommand::creating sets it).
        $nextNumbers = $companies->mapWithKeys(fn (Company $company): array => [$company->id => $company->nextBCommandNumber()]);

        $providers = Provider::query()->orderBy('entreprise')->pluck('entreprise', 'id');

        return view('theme.pages.Commercial.BC.__create.index', compact('companies', 'nextNumbers', 'providers'));
    }

    public function single(BCommand $command)
    {
        $command->load('articles', 'provider', 'company');

        return view('theme.pages.Commercial.BC.__detail.index', compact('command'));
    }

    public function store(BCFormRequest $request)
    {
        // dd($request->all());

        $this->authorize('create', BCommand::class);

        $articles = $request->articles;

        $totalPrice = collect($articles)->map(function ($item) {
            return $item['prix_unitaire'] * $item['quantity'];
        })->sum();

        $commandArticles = collect($articles)->map(function ($item) {
            return collect($item)->merge(['montant_ht' => $item['prix_unitaire'] * $item['quantity']]);
        })->toArray();

        $command = new BCommand;

        $command->date_command = $request->date('date_command');

        $command->condition_general = $request->condition_general;
        $command->admin_notes = $request->admin_notes;

        $command->price_ht = $totalPrice;

        $command->price_total = $this->caluculateTva($totalPrice);
        $command->price_tva = $this->calculateOnlyTva($totalPrice);

        $command->provider()->associate($request->provider);

        $command->company()->associate($request->company);

        $command->save();

        $command->articles()->createMany($commandArticles);

        $command->histories()->create([
            'user_id' => auth()->id(),
            'user' => auth()->user()->full_name,
            'detail' => 'a crée le BC',
            'action' => 'add',
        ]);

        return redirect($command->edit_url)->with('success', 'Le BC a été crée avec success');
    }

    public function edit(BCommand $command)
    {
        $this->authorize('update', $command);

        $command->load('articles', 'provider', 'provider.emails', 'company', 'histories');

        return view('theme.pages.Commercial.BC.__edit.index', compact('command'));
    }

    public function update(BCUpdateFormRequest $request, BCommand $command)
    {
        // dd($request->all());

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

        $command->date_command = $request->date('date_command');

        $command->condition_general = $request->condition_general;
        $command->admin_notes = $request->admin_notes;

        $command->provider()->associate($request->provider);
        $command->company()->associate($request->company);

        $command->save();

        $command->articles()->createMany($newArticles);

        $command->histories()->create([
            'user_id' => auth()->id(),
            'user' => auth()->user()->full_name,
            'detail' => 'a modifier le BC',
            'action' => 'update',
        ]);

        return redirect($command->edit_url)->with('success', 'Le BC a été modifier avec success');
    }

    /**
     * Update one article of the purchase order and its totals (no discount on purchase orders).
     * The former edit form re-posted these articles with the new ones and the update ignored them.
     */
    public function updateArticle(Request $request, BCommand $command, string $article, UpdateDocumentArticle $updateArticle): RedirectResponse
    {
        $this->authorize('update', $command);

        $article = $command->articles()->whereUuid($article)->firstOrFail();

        $data = $request->validateWithBag('article', [
            'designation' => ['required', 'string'],
            'quantity' => ['required', 'integer', 'min:1'],
            'prix_unitaire' => ['required', 'numeric', 'min:0'],
        ]);

        $updateArticle($command, $article, $data, withDiscount: false, historyDetail: 'a modifié un article du BC');

        return redirect($command->edit_url)->with('success', "L'article a été modifié avec succès");
    }

    public function deleteCommand(Request $request)
    {
        // dd($request->all());

        $request->validate(['commandId' => 'required|uuid']);

        $command = BCommand::whereUuid($request->commandId)->firstOrFail();

        $this->authorize('delete', $command);

        if ($command) {
            $command->articles()->delete();

            $command->histories()->create([
                'user_id' => auth()->id(),
                'user' => auth()->user()->full_name,
                'detail' => 'a supprimer le BC',
                'action' => 'delete',
            ]);

            $command->delete();

            return redirect(route('commercial:bcommandes.index'))->with('success', 'La Commande  a éte supprimer avec success');
        }

        return redirect(route('commercial:bcommandes.index'))->with('success', 'erreur . . . ');
    }

    public function deleteArticle(BCDeleteArticleFormRequest $request)
    {
        // dd($request->all());

        $command = BCommand::whereUuid($request->command)->firstOrFail();

        $article = Article::whereUuid($request->article)->firstOrFail();

        $this->authorize('delete', $command);

        if ($command && $article) {
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
                'detail' => 'a supprimer un article depuis le BC',
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

    public function sendBC(EmailFormRequest $request, SendDocumentByMail $sendDocument): RedirectResponse
    {
        $bc = BCommand::whereUuid($request->bc)->firstOrFail();

        $sent = $sendDocument($bc, SendBCMail::class, [$bc->provider?->email, $request->input('emails.*.*', [])], 'a envoyé le BC par mail');

        return redirect()->back()->with($sendDocument->flash($sent));
    }
}
