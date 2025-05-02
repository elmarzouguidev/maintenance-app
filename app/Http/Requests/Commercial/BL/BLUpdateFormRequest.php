<?php

declare(strict_types=1);

namespace App\Http\Requests\Commercial\BL;

use Illuminate\Foundation\Http\FormRequest;

class BLUpdateFormRequest extends FormRequest
{
    /**
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    public function getArticles()
    {
        $articles = $this->articles ?? [];

        return collect($articles)
            ->whereNull('montant_ht')
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

            'bc_number' => ['required', 'string', 'max:255'],
            'date_bl' => ['required', 'date'],
            //'date_due' => ['required', 'date'],

            'admin_notes' => ['nullable', 'string'],
            // 'client_notes' => ['nullable', 'string'],
            'condition_general' => ['nullable', 'string'],

            'articles' => ['required', 'array'],
            'articles.*.designation' => ['required', 'string'],
            'articles.*.description' => ['nullable', 'string'],
            'articles.*.quantity' => ['required', 'integer'],
            'articles.*.prix_unitaire' => ['required', 'numeric', 'digits_between:1,20'],
            //'articles.*.montant_ht' => ['nullable', 'numeric'],
        ];
    }
}
