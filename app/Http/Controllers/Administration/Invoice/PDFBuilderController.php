<?php

namespace App\Http\Controllers\Administration\Invoice;

use App\Http\Controllers\Controller;
use App\Models\Finance\Invoice;
use App\Models\Finance\InvoiceAvoir;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class PDFBuilderController extends Controller
{


    public function build(Request $request, Invoice $invoice)
    {

        $invoice->load('articles', 'company', 'client');

        $logo = substr($request->logo, strrpos($request->logo, '/') + 1);

        $companyLogo = public_path('storage/company-logo/' . $logo) ?? "";
        //$companyLogo = public_path('storage/company-logo/default.png');
        //dd($companyLogo);
        $pdf = \PDF::loadView('theme.invoices_template.template1.index', compact('invoice', 'companyLogo'));

        return $pdf->stream($invoice->date_invoice . "-[ {$invoice->client->entreprise} ]-" . 'facture.pdf');
    }

    public function buildAvoir(Request $request, InvoiceAvoir $invoice)
    {

        $invoice->load('articles', 'company', 'client');

        $logo = substr($request->logo, strrpos($request->logo, '/') + 1);

        $companyLogo = public_path('storage/company-logo/' . $logo) ?? "";
        //$companyLogo = public_path('storage/company-logo/default.png');
        //dd($companyLogo);
        $pdf = \PDF::loadView('theme.invoices_template.avoirs.index', compact('invoice', 'companyLogo'));

        return $pdf->stream($invoice->date_invoice . "-[ {$invoice->client->entreprise} ]-" . 'facture.pdf');
    }
}
