<?php

namespace App\Http\Controllers\Administration\Invoice;

use App\Http\Controllers\Controller;
use App\Models\Finance\Invoice;
use Illuminate\Http\Request;

class PDFBuilderController extends Controller
{


    public function build(Invoice $invoice)
    {

        $invoice->load('articles', 'company', 'client');

        $pdf = \PDF::loadView('theme.invoices_template.template1.index', compact('invoice'));

        return $pdf->stream($invoice->uuid . '-facture.pdf');
        /*return $pdf
            ->save(storage_path('invoices/' . $invoice->client->entreprise . '-invoice.pdf'))
            ->download($invoice->uuid . '-invoice.pdf');*/
        //return $pdf->stream();
        //return view('theme.invoices_template.template1.index');
    }
}
