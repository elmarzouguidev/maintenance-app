<?php

declare(strict_types=1);

namespace App\Http\Requests\Application\Client;

use Illuminate\Foundation\Http\FormRequest;

class ImportClientFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'file' => ['required', 'file', 'mimes:csv,xls,xlsx'],
        ];
    }
}
