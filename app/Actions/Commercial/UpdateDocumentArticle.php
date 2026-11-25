<?php

namespace App\Actions\Commercial;

use App\Models\Finance\Article;
use App\Services\Commercial\Remise\RemiseCalculator;
use App\Services\Commercial\Taxes\TVACalulator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Update one article of a commercial document (invoice, estimate, credit note) and the
 * document's totals, then record it in the document history. Replaces the three Livewire
 * EditArticle components (same arithmetic: discount on the line, TVA 20 % on the document).
 */
class UpdateDocumentArticle
{
    use RemiseCalculator;
    use TVACalulator;

    /**
     * @param  Model  $document  an Invoice, Estimate or InvoiceAvoir (price_ht, price_total, price_tva, histories())
     * @param  array{designation: string, quantity: int|string, prix_unitaire: float|string, remise?: float|string|null}  $data
     * @param  bool  $withDiscount  false for credit notes, whose articles never carry a discount
     */
    public function __invoke(Model $document, Article $article, array $data, bool $withDiscount, string $historyDetail): void
    {
        DB::transaction(function () use ($document, $article, $data, $withDiscount, $historyDetail): void {
            $grossPrice = (float) $data['prix_unitaire'] * (float) $data['quantity'];
            $discount = $withDiscount ? (float) ($data['remise'] ?? 0) : 0.0;
            $oldArticlePrice = (float) $article->montant_ht;

            $article->update([
                'designation' => $data['designation'],
                'quantity' => $data['quantity'],
                'prix_unitaire' => $data['prix_unitaire'],
                'montant_ht' => $this->caluculateRemise($grossPrice, $discount),
                'remise' => $discount,
                'taux_remise' => $this->calculateOnlyRemise($grossPrice, $discount),
            ]);

            $totalPrice = ((float) $document->price_ht - $oldArticlePrice) + (float) $article->montant_ht;
            $document->price_ht = $totalPrice;
            $document->price_total = $this->caluculateTva($totalPrice);
            $document->price_tva = $this->calculateOnlyTva($totalPrice);
            $document->save();

            $document->histories()->create([
                'user_id' => auth()->id(),
                'user' => auth()->user()->full_name,
                'detail' => $historyDetail,
                'action' => 'update',
            ]);
        });
    }
}
