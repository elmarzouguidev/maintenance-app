<?php

namespace App\Http\Requests\Application\Reparation;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ReparationFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //'ticket' => ['required', 'string','unique:reports,ticket'],
            'content' => ['required', 'string', 'min:5'],
           // 'type' => ['required', 'string'],
            //'etat' => ['required', 'string', Rule::in(['reparable', 'non-reparable'])],
        ];
    }
}
