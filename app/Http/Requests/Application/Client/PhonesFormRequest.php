<?php

declare(strict_types=1);

namespace App\Http\Requests\Application\Client;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PhonesFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'telephone' => ['required', 'phone:MA', Rule::unique('telephones', 'telephone')],
            'type' => ['required', 'string'],
        ];
    }
}
