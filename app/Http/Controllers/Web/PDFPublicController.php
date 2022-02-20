<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Finance\BCommand;
use App\Models\Finance\Estimate;
use App\Models\Finance\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class PDFPublicController extends Controller
{


    public function showInvoice(Request $request, Invoice $invoice)
    {

        $invoice->load('articles', 'company:id,name', 'client:id,entreprise');

        $logo = substr($request->logo, strrpos($request->logo, '/') + 1);

        $companyLogo = public_path('storage/company-logo/' . $logo) ?? "";

        $pdf = \PDF::loadView('theme.invoices_template.template1.index', compact('invoice', 'companyLogo'));

        return $pdf->stream($invoice->invoice_date . "-[ {$invoice->client->entreprise} ]-" . 'FACTURE.pdf');
    }

    public function showEstimate(Request $request , Estimate $estimate)
    {

        $estimate->load('articles', 'company', 'client');

        $logo = substr($request->logo, strrpos($request->logo, '/') + 1);

        $companyLogo = public_path('storage/company-logo/' . $logo);

        $pdf = \PDF::loadView('theme.estimates_template.template1.index', compact('estimate', 'companyLogo'));

        return $pdf->stream($estimate->estimate_date . "-[ {$estimate->client->entreprise} ]-" . 'DEVIS.pdf');
    }

    public function showBCommand(BCommand $command)
    {
        $command->load('articles', 'company', 'provider');

        $companyLogo = public_path('storage/company-logo/default.png');

        $pdf = \PDF::loadView('theme.bons_template.template1.index', compact('command', 'companyLogo'));

        return $pdf->stream($command->date_command . "-[ {$command->provider->entreprise} ]-" . 'BON-COMMAND.pdf');
    }
}
