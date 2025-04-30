<?php

declare(strict_types=1);

namespace App\Http\Requests\Commercial\BL;

use Illuminate\Foundation\Http\FormRequest;

class BLFormRequest extends FormRequest
{
    /**
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [

            'client' => ['required', 'integer'],
            'company' => ['required', 'integer'],

            //'code' => ['required', 'string', 'unique:b_commands'],
            'date_bl' => ['required', 'date', 'date_format:Y-m-d'],
            // 'date_due' => ['required', 'date', 'date_format:d-m-Y'],

            'admin_notes' => ['nullable', 'string'],
            //'client_notes' => ['nullable', 'string'],
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
