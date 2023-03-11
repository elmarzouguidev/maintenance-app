<?php

declare(strict_types=1);

namespace App\Http\Requests\Commercial\Estimate;

use Illuminate\Foundation\Http\FormRequest;

class ArticleUpdateFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'estimate' => ['required', 'uuid'],
            //'estimateid' => ['required', 'integer'],

            'articleuuid' => ['required', 'uuid'],

            'designation' => ['required', 'string'],
            'quantity' => ['required', 'integer'],
            'prix_unitaire' => ['required', 'numeric', 'digits_between:1,20'],
            'remise' => ['nullable', 'numeric', 'digits_between:1,20'],
        ];
    }
}
