<?php

namespace App\Http\Controllers\Administration\Invoice;

use App\Http\Controllers\Controller;
use App\Models\Finance\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class PDFBuilderController extends Controller
{


    public function build(Invoice $invoice)
    {

        $invoice->load('articles', 'company', 'client');

        //$companyLogo = $invoice->company->logo ?? public_path('storage/company-logo/default.png');
        $companyLogo = public_path('storage/company-logo/default.png');
        $url = URL::to($invoice->company->logo);

        $pdf = \PDF::loadView('theme.invoices_template.template1.index', compact('invoice', 'companyLogo'));

        return $pdf->stream($invoice->date_invoice . "-[ {$invoice->client->entreprise} ]-" . 'facture.pdf');
    }
}
