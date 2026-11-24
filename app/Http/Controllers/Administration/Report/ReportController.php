<?php

declare(strict_types=1);

namespace App\Http\Controllers\Administration\Report;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Finance\Bill;
use App\Models\Finance\Invoice;
use App\Support\MonthlyTotals;
use Illuminate\Contracts\View\View;

class ReportController extends Controller
{
    public function index(): View
    {
        $invoiceChart = MonthlyTotals::chart("Chiffre d'affaire", 'line', Invoice::query(), 'price_total');

        $billChart = MonthlyTotals::chart('Encaissements', 'bar', Bill::query(), 'price_total');

        $clientsData = Client::has('invoices')
            ->withSum('invoices', 'price_total')
            ->withSum(['invoices as price_total_paid' => function ($query) {
                $query->has('bill');
            }], 'price_total')
            ->get();

        $clients = $clientsData->sortBy([['invoices_sum_price_total', 'desc']]);

        return view('theme.pages.Report.index', compact('clients', 'invoiceChart', 'billChart'));
    }
}
