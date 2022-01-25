<?php


namespace App\Helpers;

use App\Models\Finance\Invoice;

trait InvoiceHelpers
{

    public function nextInvoiceNumber()
    {

        return Invoice::max('invoice_code') + 1;
    }

    public function invoicePrefix()
    {

        return config('app-config.invoices.prefix');
    }

    public function invoiceDueDate($days = null)
    {
        return  now()->addDays(config('app-config.invoices.due_date_after'))->format('d-m-Y');
    }
}
