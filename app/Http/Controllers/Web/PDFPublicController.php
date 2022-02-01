<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Finance\Estimate;
use App\Models\Finance\Invoice;
use Illuminate\Http\Request;

class PDFPublicController extends Controller
{


    public function showInvoice(Invoice $invoice)
    {

        $invoice->load('articles', 'company', 'client');

        $companyLogo = $invoice->company->logo;

        $pdf = \PDF::loadView('theme.invoices_template.template1.index', compact('invoice', 'companyLogo'));

        return $pdf->stream($invoice->date_invoice . "-[ {$invoice->client->entreprise} ]-" . 'facture.pdf');
    }

    public function showEstimate(Estimate $estimate)
    {

        $estimate->load('articles', 'company', 'client');

        $companyLogo = $estimate->company->logo;

        $pdf = \PDF::loadView('theme.estimates_template.template1.index', compact('estimate', 'companyLogo'));

        return $pdf->stream($estimate->estimate_date . "-[ {$estimate->client->entreprise} ]-" . 'devis.pdf');
    }
}
