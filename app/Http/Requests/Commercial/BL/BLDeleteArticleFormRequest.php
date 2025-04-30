<?php

declare(strict_types=1);

namespace App\Http\Requests\Commercial\BL;

use Illuminate\Foundation\Http\FormRequest;

class BLDeleteArticleFormRequest extends FormRequest
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
            //
        ];
    }
}
