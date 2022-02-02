<?php

namespace App\Http\Controllers\Commercial\BCommand;

use App\Http\Controllers\Controller;
use App\Http\Requests\Commercial\BCommand\BCFormRequest;
use App\Models\Finance\BCommand;
use App\Services\Commercial\Taxes\TVACalulator;
use Illuminate\Http\Request;

class BCommandController extends Controller
{

    use TVACalulator;

    public function index()
    {

        $commandes = BCommand::all();

        return view('theme.pages.Commercial.BC.index', compact('commandes'));
    }

    public function create()
    {
        return view('theme.pages.Commercial.BC.__create.index');
    }

    public function single(Invoice $invoice)
    {
        $invoice->load('articles');

        return view('theme.pages.Commercial.Invoice.__detail.index', compact('invoice'));
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
       // $command->total_tva = $this->calculateOnlyTva($totalPrice);

        $command->provider()->associate($request->provider);
        $command->company()->associate($request->company);

        $command->provider_code = $command->provider->provider_ref;

        $command->save();

        $command->articles()->createMany($commandArticles);

        return redirect()->back();
    }

    public function edit(BCommand $command)
    {

        $command->load('articles');

        return view('theme.pages.Commercial.Invoice.__edit.index', compact('command'));
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
