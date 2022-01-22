<?php

namespace App\Http\Requests\Application\Ticket;

use Illuminate\Foundation\Http\FormRequest;

class TicketFormRequest extends FormRequest
{

    //protected $redirectRoute = 'dashboard';
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
            'article' => 'required|string|unique:tickets',
            'description' => 'required|string',
            'photo' => 'required|file|mimes:png,jpg,jpeg',
            'photos' => 'nullable|file|mimes:png,jpg,jpeg',
            'client' => 'nullable|integer'
        ];
    }
}
