<?php

declare(strict_types=1);

namespace App\Http\Requests\Commercial\Provider;

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
            'secend_phone' => ['required', 'phone:MA', Rule::unique('telephones', 'telephone')],
        ];
    }
}
