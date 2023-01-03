<?php

namespace App\Http\Livewire\Commercial\Estimate\Edit;

use App\Http\Requests\Commercial\Estimate\ArticleUpdateFormRequest;
use App\Models\Finance\Article;
use App\Models\Finance\Estimate;
use App\Services\Commercial\Remise\RemiseCalculator;
use App\Services\Commercial\Taxes\TVACalulator;
use Livewire\Component;

class EditArticle extends Component
{
    use TVACalulator;
    use RemiseCalculator;

    public Article $article;

    public Estimate $estimate;

    public $designation;

    public $quantity;

    public $prix_unitaire;

    public $remise;

    public $montant_ht;

    public function render()
    {
        return view('theme.livewire.commercial.estimate.edit.edit-article');
    }

    public function mount()
    {
        $this->designation = str_replace('<br />', '', $this->article->designation);
        $this->quantity = $this->article->quantity;
        $this->prix_unitaire = $this->article->prix_unitaire;
        $this->remise = $this->article->remise;
        $this->montant_ht = $this->article->montant_ht;
    }

    public function rules()
    {
        return (new ArticleUpdateFormRequest())->rules();
    }

    public function updateArticle()
    {
        $estimate = $this->estimate;

        $article = $this->article;

        $oldArticlePrice = $article->montant_ht;

        if ($article && $estimate) {
            $itemPrice = $this->prix_unitaire * $this->quantity;
            $finalePrice = $this->caluculateRemise($itemPrice, $this->remise ?? 0);
            $tauxRemise = $this->calculateOnlyRemise($itemPrice, $this->remise ?? 0);
            $article->update([
                'designation' => $this->designation,
                'quantity' => $this->quantity,
                'prix_unitaire' => $this->prix_unitaire,
                'montant_ht' => $finalePrice,
                'remise' => $this->remise ?? 0,
                'taux_remise' => $tauxRemise ?? 0,
            ]);
        }

        $totalPrice = ($estimate->price_ht - $oldArticlePrice) + $article->montant_ht;
        $estimate->price_ht = $totalPrice;
        $estimate->price_total = $this->caluculateTva($totalPrice);
        $estimate->price_tva = $this->calculateOnlyTva($totalPrice);

        $estimate->save();

        $estimate->histories()->create([
            'user_id' => auth()->id(),
            'user' => auth()->user()->full_name,
            'detail' => 'a modifier un article depuis le DEVIS ',
            'action' => 'update',
        ]);

        return redirect($estimate->edit_url)->with('success', "L'article a été modifier avec success");
    }
}
