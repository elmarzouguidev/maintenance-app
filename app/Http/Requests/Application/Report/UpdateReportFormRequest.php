<?php

declare(strict_types=1);

namespace App\Http\Requests\Application\Report;

use Illuminate\Foundation\Http\FormRequest;

class UpdateReportFormRequest extends FormRequest
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
            'report_diagnose' => ['required', 'string'],
            'report_reparation' => ['required', 'string'],
        ];
    }
}
