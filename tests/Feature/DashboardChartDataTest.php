<?php

use App\Http\Controllers\Administration\Admin\DashboardController;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/*
 * Monthly totals behind the dashboard charts (MONTH() + whereYear on MySQL).
 */

uses(RefreshDatabase::class);

function chartCompany(): int
{
    return DB::table('companies')->insertGetId([
        'uuid' => (string) Str::uuid(),
        'name' => 'CASA MAINTENANCE',
        'addresse' => 'Casablanca',
        'ice' => '000000000000000',
    ]);
}

function chartInvoice(int $companyId, string $date, string $priceHt): void
{
    $code = Str::random(8);

    DB::table('invoices')->insert([
        'uuid' => (string) Str::uuid(),
        'code' => $code,
        'full_number' => 'FAC-'.$code,
        'company_id' => $companyId,
        'invoice_date' => $date,
        'price_ht' => $priceHt,
    ]);
}

function chartBill(string $date, string $priceTotal): void
{
    $code = Str::random(8);

    DB::table('bills')->insert([
        'uuid' => (string) Str::uuid(),
        'code' => $code,
        'full_number' => 'REGL-'.$code,
        'billable_id' => 1,
        'billable_type' => 'App\\Models\\Finance\\Invoice',
        'bill_date' => $date,
        'price_total' => $priceTotal,
    ]);
}

/**
 * @return array<int, float>
 */
function monthlyTotals(Collection $chart): array
{
    return $chart->mapWithKeys(fn (array $point) => [$point['month'] => (float) $point['price']])->all();
}

it('sums the invoice amounts per month for the current year only', function () {
    $this->travelTo(Carbon::create(2026, 6, 15, 10, 0, 0, 'Africa/Casablanca'));

    $company = chartCompany();
    chartInvoice($company, '2026-03-05', '1000.50');
    chartInvoice($company, '2026-03-20', '499.50');
    chartInvoice($company, '2026-07-01', '200.00');
    chartInvoice($company, '2025-03-10', '999.00');

    $totals = monthlyTotals(app(DashboardController::class)->getChartData());

    expect($totals)->toHaveCount(12)
        ->and($totals[3])->toBe(1500.0)
        ->and($totals[7])->toBe(200.0)
        ->and($totals[1])->toBe(0.0)
        ->and(array_sum($totals))->toBe(1700.0);
});

it('sums the payments per month for the current year only', function () {
    $this->travelTo(Carbon::create(2026, 6, 15, 10, 0, 0, 'Africa/Casablanca'));

    chartBill('2026-02-01', '1200.25');
    chartBill('2026-02-28', '0.75');
    chartBill('2025-02-14', '5000.00');

    $totals = monthlyTotals(app(DashboardController::class)->getChartDataBills());

    expect($totals)->toHaveCount(12)
        ->and($totals[2])->toBe(1201.0)
        ->and(array_sum($totals))->toBe(1201.0);
});

it('uses the new year as soon as it starts in Casablanca', function () {
    $this->travelTo(Carbon::create(2027, 1, 1, 0, 30, 0, 'Africa/Casablanca'));

    $company = chartCompany();
    chartInvoice($company, '2027-01-01', '300.00');
    chartInvoice($company, '2026-12-31', '700.00');
    chartBill('2027-01-01', '50.00');
    chartBill('2026-12-31', '80.00');

    $controller = app(DashboardController::class);

    expect(monthlyTotals($controller->getChartData()))->toMatchArray([1 => 300.0, 12 => 0.0])
        ->and(monthlyTotals($controller->getChartDataBills()))->toMatchArray([1 => 50.0, 12 => 0.0]);
});
