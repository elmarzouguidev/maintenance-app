<?php

declare(strict_types=1);

namespace App\Http\Requests\Tools\MailTemplate;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MailFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', Rule::unique('mail_templates')],
            'content' => ['required', 'string'],
        ];
    }
}
