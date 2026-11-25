<?php

use App\Models\Finance\Invoice;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;

/*
 * The client report page (commercial:reports.index): monthly charts and the client ranking, on
 * the UI kit layout since K1. BACKLOG.md BL-12: the page crashed after laraveldaily/laravel-charts
 * was removed.
 */

uses(RefreshDatabase::class);

beforeEach(function () {
    useFakeViteServer();

    $user = User::factory()->create();
    $user->givePermissionTo(Permission::findOrCreate('report.clients.browse', 'admin'));
    $this->actingAs($user);
});

it('refuses the client report to a user without report.clients.browse', function () {
    $this->actingAs(User::factory()->create())
        ->get(route('commercial:reports.index'))
        ->assertForbidden();
});

function reportCompany(): int
{
    return DB::table('companies')->insertGetId([
        'uuid' => (string) Str::uuid(), 'name' => 'CASA MAINTENANCE', 'addresse' => 'Casablanca', 'ice' => '000000000000000',
    ]);
}

function reportClient(string $name): int
{
    return DB::table('clients')->insertGetId([
        'uuid' => (string) Str::uuid(), 'entreprise' => $name, 'addresse' => 'Casablanca', 'ice' => Str::random(15), 'telephone' => '06'.random_int(10000000, 99999999),
    ]);
}

function reportInvoice(int $companyId, ?int $clientId, string $createdAt, string $total): int
{
    $code = Str::random(8);

    return DB::table('invoices')->insertGetId([
        'uuid' => (string) Str::uuid(), 'code' => $code, 'full_number' => 'FAC-'.$code,
        'company_id' => $companyId, 'client_id' => $clientId, 'price_total' => $total, 'created_at' => $createdAt,
    ]);
}

function reportBill(int $invoiceId, string $createdAt, string $total): void
{
    $code = Str::random(8);

    DB::table('bills')->insert([
        'uuid' => (string) Str::uuid(), 'code' => $code, 'full_number' => 'REGL-'.$code,
        'billable_id' => $invoiceId, 'billable_type' => Invoice::class, 'price_total' => $total, 'created_at' => $createdAt,
    ]);
}

it('renders the report page with monthly invoice and payment totals', function () {
    $company = reportCompany();
    $client = reportClient('Atlas Services');

    $january = reportInvoice($company, $client, '2026-01-10 09:00:00', '1000.50');
    reportInvoice($company, $client, '2026-01-25 16:30:00', '499.50');
    reportInvoice($company, null, '2026-03-02 11:00:00', '200.00');
    reportBill($january, '2026-02-01 10:00:00', '1000.50');

    $this->get(route('commercial:reports.index'))
        ->assertOk()
        ->assertSee("Chiffre d'affaire")
        ->assertSee('Encaissements')
        ->assertViewHas('invoiceChart', fn (array $chart): bool => $chart['type'] === 'line'
            && $chart['labels'] === ['Janvier 2026', 'Mars 2026']
            && $chart['values'] === [1500.0, 200.0])
        ->assertViewHas('billChart', fn (array $chart): bool => $chart['type'] === 'bar'
            && $chart['labels'] === ['Février 2026']
            && $chart['values'] === [1000.5])
        ->assertSee('assets/libs/apexcharts/apexcharts.min.js', false)
        ->assertSeeInOrder(['Voir les données', 'Janvier 2026', "1\u{00A0}500,00\u{00A0}DH", 'Mars 2026', "200,00\u{00A0}DH"], false);
});

it('renders on the kit layout without DataTables', function () {
    $this->get(route('commercial:reports.index'))
        ->assertOk()
        ->assertSee('<title>Rapport clients - CASAMAINTENANCE</title>', false)
        ->assertSee('http://vite.test/resources/css/ui-kit.css', false)
        ->assertDontSee('http://vite.test/resources/css/app.css', false)
        ->assertDontSee('legacy', false)
        ->assertDontSee('datatables', false)
        ->assertSee('<h1 class="admin-page-title">Rapport clients</h1>', false);
});

it('ranks the clients by invoiced total with the paid part', function () {
    $company = reportCompany();
    $atlas = reportClient('Atlas Services');
    $souss = reportClient('Souss Agro');

    $paid = reportInvoice($company, $atlas, '2026-01-10 09:00:00', '300.00');
    reportInvoice($company, $atlas, '2026-02-10 09:00:00', '200.00');
    reportInvoice($company, $souss, '2026-02-12 09:00:00', '900.00');
    reportBill($paid, '2026-02-01 10:00:00', '300.00');

    $this->get(route('commercial:reports.index'))
        ->assertOk()
        ->assertSeeInOrder(['Souss Agro', "900,00\u{00A0}DH", "0,00\u{00A0}DH", 'Atlas Services', "500,00\u{00A0}DH", "300,00\u{00A0}DH"], false);
});

it('renders the charts when there is no data yet', function () {
    $this->get(route('commercial:reports.index'))
        ->assertOk()
        ->assertViewHas('invoiceChart', fn (array $chart): bool => $chart['labels'] === [] && $chart['values'] === [])
        ->assertSee('Aucune donnée pour le moment.')
        ->assertSee('Aucun client facturé')
        ->assertDontSee('apexcharts.min.js', false);
});
