<?php

namespace App\Http\Requests\Commercial\Estimate;

use Illuminate\Foundation\Http\FormRequest;

class EstimateUpdateFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */


    public function getNewArticles()
    {
        $articles = $this->articlesnew ?? [];

        return collect($articles)
            ->whereNull('montant_ht')
            ->collect();
    }

    public function getOlderArticles()
    {
        $articles = $this->articles ?? [];

        return collect($articles)
            ->whereNotNull('articleuuid')
            ->collect();
    }

    public function authorize()
    {
        return true;
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

            'tickets' => ['nullable', 'array'],

            'estimate_date' => ['required', 'date', 'date'],
            'due_date' => ['required', 'date', 'date'],
            'payment_mode' => ['required', 'string'],

            'admin_notes' => ['nullable', 'string'],
            // 'client_notes' => ['nullable', 'string'],
            'condition_general' => ['nullable', 'string'],

            'articlesnew' => ['nullable', 'array'],

            'articlesnew.*.articleuuid'=>['nullable','uuid'],

            'articlesnew.*.designation' => ['nullable', 'string'],
            //'articlesnew.*.description' => ['nullable', 'string'],
            'articlesnew.*.quantity' => ['nullable', 'integer'],
            'articlesnew.*.prix_unitaire' => ['nullable', 'numeric','digits_between:1,20'],
            //'articlesnew.*.montant_ht' => ['nullable', 'numeric'],
            'articlesnew.*.remise' => ['nullable','numeric','digits_between:1,20']
        ];
    }
}
