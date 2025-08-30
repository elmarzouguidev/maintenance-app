<?php

namespace App\Http\Requests\Application\Ticket;

use Illuminate\Foundation\Http\FormRequest;

class TicketUpdateFormRequest extends FormRequest
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
            'article' => 'required|string',
            'description' => 'required|string',
            'created_at' => 'nullable|date_format:d-m-Y',
            'client_id' => 'required|exists:clients,id',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'client_id.required' => 'Le client est obligatoire.',
            'client_id.exists' => 'Le client sélectionné n\'existe pas.',
            'article.required' => 'L\'article est obligatoire.',
            'description.required' => 'La description est obligatoire.',
        ];
    }
}
