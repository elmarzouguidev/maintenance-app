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

            'entreprise' => 'required|string',
            'contact' => 'required|string',
            'telephone' => 'required|phone:MA|unique:clients',
            'email' => 'nullable|email|unique:clients',
            'addresse' => 'nullable|string',
            'rc' => 'nullable|numeric',
            'ice' => 'required|numeric',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:1024',
            'category' => 'nullable|integer',

            'clients.*' => 'nullable|array',
            'clients.*.telephones' => 'nullable|array',
            'clients.*.telephones.*.telephone' => 'nullable|phone:MA|unique:telephones',
        ];
    }
}
