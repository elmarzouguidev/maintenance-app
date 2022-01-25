<?php

namespace App\Http\Requests\Commercial\Invoice;

use Illuminate\Foundation\Http\FormRequest;

class InvoiceUpdateFormRequest extends FormRequest
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
            'client' => ['required', 'integer'],
            'company' => ['required', 'integer'],
            'ticket' => ['nullable', 'integer'],

            'date_invoice' => ['required', 'date'],
            'date_due' => ['required', 'date'],
            'payment_method' => ['required', 'string', 'in:espece,virement,cheque'],

            'note_admin' => ['nullable', 'string'],
            'client_note' => ['nullable', 'string'],
            'condition' => ['nullable', 'string'],

            'articles' => ['nullable', 'array'],
            'articles.*.designation' => ['required', 'string'],
            'articles.*.description' => ['nullable', 'string'],
            'articles.*.quantity' => ['required', 'integer'],
            'articles.*.prix_unitaire' => ['required', 'numeric'],
            //'articles.*.montant_ht' => ['nullable', 'numeric'],
        ];
    }
}
