<?php

namespace App\Http\Requests\Application\Technicien;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class TechnicienFormRequest extends FormRequest
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
            'nom' => 'required|string',
            'prenom' => 'required|string',
            'telephone' => 'required|numeric',
            'email' => 'required|email',
            'password' => 'required|string',
            //'super_admin' => ['nullable', Rule::in([1, '1', true, 'on', 'yes'])]
        ];
    }
}
