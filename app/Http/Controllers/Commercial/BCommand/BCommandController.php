<?php

namespace App\Http\Controllers\Commercial\BCommand;

use App\Http\Controllers\Controller;
use App\Http\Requests\Commercial\BCommand\BCDeleteArticleFormRequest;
use App\Http\Requests\Commercial\BCommand\BCFormRequest;
use App\Http\Requests\Commercial\BCommand\BCUpdateFormRequest;
use App\Http\Requests\Commercial\BCommand\EmailFormRequest;
use App\Http\Requests\Commercial\Estimate\SendEmailFormRequest;
use App\Mail\Commercial\BC\SendBCMail;
use App\Mail\Commercial\Estimate\SendEstimateMail;
use App\Models\Finance\Article;
use App\Models\Finance\BCommand;
use App\Models\Finance\Estimate;
use App\Services\Commercial\Taxes\TVACalulator;
use App\Services\Mail\CheckConnection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

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
            'action' => 'add'
        ]);

        return redirect($command->edit_url);
    }

    public function edit(BCommand $command)
    {

        $command->load('articles', 'provider', 'company','histories');

        return view('theme.pages.Commercial.BC.__edit.index', compact('command'));
    }

    public function update(BCUpdateFormRequest $request, BCommand $command)
    {

        //dd($request->all());

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
            'action' => 'update'
        ]);

        return redirect($command->edit_url)->with('success', "Le Bon a été modifier avec success");
    }

    public function deleteCommand(Request $request)
    {
        // dd($request->all());
        $request->validate(['commandId' => 'required|uuid']);

        $command = BCommand::whereUuid($request->commandId)->firstOrFail();

        if ($command) {

            $command->articles()->delete();

            $command->histories()->create([
                'user_id' => auth()->id(),
                'user' => auth()->user()->full_name,
                'detail' => 'a supprimer le BC',
                'action' => 'delete'
            ]);

            $command->delete();

            return redirect(route('commercial:bcommandes.index'))->with('success', "La Commande  a éte supprimer avec success");
        }
        return redirect(route('commercial:bcommandes.index'))->with('success', "erreur . . . ");
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
                'action' => 'delete'
            ]);

            return response()->json([
                'success' => 'Record deleted successfully!'
            ]);
        }
        return response()->json([
            'error' => 'problem detected !'
        ]);
    }


    public function sendBC(EmailFormRequest $request)
    {

        $bc = BCommand::whereUuid($request->bc)->first();
        //dd($request->input('emails.*.*'),$request->collect('emails.*.*'));
       // $emails = $request->input('emails.*.*');
        if (CheckConnection::isConnected()) {

            /*if (isset($emails) && is_array($emails) && count($emails)) {

                foreach ($emails as $email) {
                    Mail::to($email)->send(New SendEstimateMail($estimate));
                }
            }*/

            Mail::to($bc->provider)->send(New SendBCMail($bc));

            if (empty(Mail::failures())) {

                $bc->update(['is_send' => !$bc->is_send]);

                //$estimate->tickets()->update(['status' => Status::EN_ATTENTE_DE_BON_DE_COMMAND]);

                $bc->histories()->create([
                    'user_id' => auth()->id(),
                    'user' => auth()->user()->full_name,
                    'detail' => 'A envoyer le BC pa mail',
                    'action' => 'send'
                ]);

                return redirect()->back()->with('success', 'Email was send');
            }
        }
        return redirect()->back()->with('errors', 'Email not send');
    }
}
