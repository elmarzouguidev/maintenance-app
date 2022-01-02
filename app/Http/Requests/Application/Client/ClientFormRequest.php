<?php

namespace App\Http\Requests\Application\Client;

use Illuminate\Foundation\Http\FormRequest;

class ClientFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'nom' => 'required|string',
            'prenom' => 'required|string',
            'address' => 'nullable|string',
            'email' => 'required|email',
            'gsm' => 'nullable|numeric',
            'telephone' => 'required|numeric',
            'ste_name' => 'required|string',
            'ste_ice' => 'nullable|numeric',
            'ste_rc' => 'nullable|numeric',
            'ste_logo' => 'nullable|image|mimes:jpeg,png,jpg|max:1024',
            'published_at' => 'nullable|string'
        ];
    }
}
