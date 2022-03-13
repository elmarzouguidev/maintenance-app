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
            'article' => 'required|string',
            'description' => 'required|string',
            'photo' => ['required','file','mimes:png,jpg,jpeg','max:1024'],
            'client' => 'required|integer'
        ];
    }

    public function messages()
    {
        return [
            'photo.required' => "You must use the 'Choose file' button to select which file you wish to upload",
            'photo.max' => "Maximum file size to upload is 1MB (1024 KB)."
        ];
    }
}
