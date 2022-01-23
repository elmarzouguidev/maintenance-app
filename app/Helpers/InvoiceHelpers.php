<?php


namespace App\Helpers;

use App\Models\Finance\Invoice;

trait InvoiceHelpers
{

    public function nextInvoiceNumber()
    {

        return (Invoice::max('invoice_code') + 1);
    }

    public function invoicePrefix()
    {

        return "FACTURE-";
    }
}
