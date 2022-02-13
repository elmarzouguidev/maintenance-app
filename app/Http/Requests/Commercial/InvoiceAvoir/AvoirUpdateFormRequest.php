<?php

namespace App\Http\Requests\Commercial\InvoiceAvoir;

use Illuminate\Foundation\Http\FormRequest;

class AvoirUpdateFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function getArticles()
    {
        $articles = $this->articles ?? [];

        return collect($articles)
            ->where('montant_ht', '<=', 0)
            ->collect();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'client' => ['required', 'integer'],
            'company' => ['required', 'integer'],
            'ticket' => ['nullable', 'integer'],

            'invoice' => ['nullable', 'integer'], //avoir invoice

            'date_invoice' => ['required', 'date'],
            'date_due' => ['required', 'date'],
            'payment_method' => ['required', 'string', 'in:espece,virement,cheque'],

            'admin_notes' => ['nullable', 'string'],
            'client_notes' => ['nullable', 'string'],
            'condition_general' => ['nullable', 'string'],

            'articles' => ['nullable', 'array'],
            'articles.*.designation' => ['required', 'string'],
            'articles.*.description' => ['nullable', 'string'],
            'articles.*.quantity' => ['required', 'integer'],
            'articles.*.prix_unitaire' => ['required', 'numeric'],
            //'articles.*.montant_ht' => ['nullable', 'numeric'],
        ];
    }
}