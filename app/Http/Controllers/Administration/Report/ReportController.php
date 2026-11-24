<?php

declare(strict_types=1);

namespace App\Http\Controllers\Administration\Report;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Finance\Bill;
use App\Models\Finance\Invoice;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class ReportController extends Controller
{
    public function index(): View
    {
        $invoiceChart = $this->monthlyTotalsChart("Chiffre d'affaire", 'line', Invoice::query(), 'price_total');

        $billChart = $this->monthlyTotalsChart('Encaissements', 'bar', Bill::query(), 'price_total');

        $clientsData = Client::has('invoices')
            ->withSum('invoices', 'price_total')
            ->withSum(['invoices as price_total_paid' => function ($query) {
                $query->has('bill');
            }], 'price_total')
            ->get();

        $clients = $clientsData->sortBy([['invoices_sum_price_total', 'desc']]);

        return view('theme.pages.Report.index', compact('clients', 'invoiceChart', 'billChart'));
    }

    /**
     * Sum of a column per month of created_at, over all time, oldest month first: the same data
     * the former laraveldaily/laravel-charts "group_by_date" charts showed (BACKLOG.md BL-12).
     *
     * @param  Builder<Invoice>|Builder<Bill>  $query
     * @return array{title: string, type: string, labels: list<string>, values: list<float>}
     */
    private function monthlyTotalsChart(string $title, string $type, Builder $query, string $column): array
    {
        $rows = $query->toBase()
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as period, SUM({$column}) as total")
            ->whereNotNull('created_at')
            ->groupBy('period')
            ->orderBy('period')
            ->get();

        return [
            'title' => $title,
            'type' => $type,
            'labels' => $rows->map(fn (object $row): string => Str::ucfirst(Carbon::createFromFormat('!Y-m', $row->period)->translatedFormat('F Y')))->all(),
            'values' => $rows->map(fn (object $row): float => round((float) $row->total, 2))->all(),
        ];
    }
}
