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


    public function showInvoice(Invoice $invoice)
    {

        $invoice->load('articles', 'company', 'client');

        $companyLogo = public_path('storage/company-logo/default.png');
        $url = URL::to($invoice->company->logo);
        //dd($url);

        $pdf = \PDF::loadView('theme.invoices_template.template1.index', compact('invoice', 'companyLogo'));

        return $pdf->stream($invoice->date_invoice . "-[ {$invoice->client->entreprise} ]-" . 'facture.pdf');
    }

    public function showEstimate(Estimate $estimate)
    {

        $estimate->load('articles', 'company', 'client');

        $companyLogo = public_path('storage/company-logo/default.png');

        $pdf = \PDF::loadView('theme.estimates_template.template1.index', compact('estimate', 'companyLogo'));

        return $pdf->stream($estimate->estimate_date . "-[ {$estimate->client->entreprise} ]-" . 'devis.pdf');
    }

    public function showBCommand(BCommand $command)
    {
        $command->load('articles', 'company', 'provider');

        $companyLogo = public_path('storage/company-logo/default.png');

        $pdf = \PDF::loadView('theme.bons_template.template1.index', compact('command', 'companyLogo'));

        return $pdf->stream($command->date_command . "-[ {$command->provider->entreprise} ]-" . 'BON-COMMAND.pdf');
    }
}
