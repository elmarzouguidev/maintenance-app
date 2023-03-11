<?php

declare(strict_types=1);

namespace App\Http\Requests\Commercial\Invoice;

use Illuminate\Foundation\Http\FormRequest;

class SendEmailFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'invoice' => 'required|uuid',
            'emails.*.*' => ['nullable', 'email'],
        ];
    }
}
