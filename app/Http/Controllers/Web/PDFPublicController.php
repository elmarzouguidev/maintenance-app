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

        /**/
        $invoice->load('articles', 'company:id,name', 'client:id,entreprise');

        $logo = substr($request->logo, strrpos($request->logo, '/') + 1);

        $companyLogo = public_path('storage/company-logo/' . $logo) ?? "";

        $pdf = \PDF::loadView('theme.invoices_template.template1.index', compact('invoice', 'companyLogo'));

        $fileName = $invoice->invoice_date->format('d-m-Y') . "-[ {$invoice->client->entreprise} ]-" . 'FACTURE-' . "{$invoice->code}" . '.pdf';

        return $pdf->stream($fileName);
    }

    public function showEstimate(Request $request, Estimate $estimate)
    {

        $estimate->load('articles', 'company', 'client');

        $logo = substr($request->logo, strrpos($request->logo, '/') + 1);

        $companyLogo = public_path('storage/company-logo/' . $logo);

        $pdf = \PDF::loadView('theme.estimates_template.template1.index', compact('estimate', 'companyLogo'));

        $fileName = $estimate->estimate_date->format('d-m-Y') . "-[ {$estimate->client->entreprise} ]-" . 'DEVIS-' . "{$estimate->code}" . '.pdf';

        return $pdf->stream($fileName);
    }

    public function showBCommand(Request $request, BCommand $command)
    {
        $command->load('articles', 'company', 'provider');

        $logo = substr($request->logo, strrpos($request->logo, '/') + 1);

        $companyLogo = public_path('storage/company-logo/' . $logo);

        $pdf = \PDF::loadView('theme.bons_template.template1.index', compact('command', 'companyLogo'));

        $fileName = $command->date_command->format('d-m-Y') . "-[ {$command->provider->entreprise} ]-" . 'BON-COMMAND-' . "{$command->code}" . '.pdf';

        return $pdf->stream($fileName);
    }
}
