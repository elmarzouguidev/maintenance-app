<?php

use App\Models\Client;
use App\Models\Finance\InvoiceAvoir;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

/*
 * K2: the credit notes pages (theme/pages/Commercial/InvoiceAvoir) on the UI kit layout,
 * without Livewire, jQuery or DataTables.
 */

uses(RefreshDatabase::class);

beforeEach(function () {
    useFakeViteServer();

    $user = User::factory()->create();
    $user->assignRole(Role::findOrCreate('SuperAdmin', 'admin'));
    $this->actingAs($user);

    $this->companyId = DB::table('companies')->insertGetId([
        'id' => 1, 'uuid' => (string) Str::uuid(), 'name' => 'CASA MAINTENANCE', 'addresse' => 'Casablanca', 'ice' => '001234567890123',
        'prefix_invoice' => 'FAC-', 'invoice_start_number' => 1, 'prefix_invoice_avoir' => 'AV-', 'invoice_avoir_start_number' => 1,
    ]);
    $this->client = Client::factory()->create(['entreprise' => 'Atlas Services'])->refresh();
    $this->invoiceId = DB::table('invoices')->insertGetId([
        'uuid' => (string) Str::uuid(), 'code' => '00042', 'full_number' => 'FAC-00042', 'company_id' => $this->companyId, 'client_id' => $this->client->id,
        'status' => 'paid', 'price_ht' => 100, 'price_tva' => 20, 'price_total' => 120,
    ]);
});

/**
 * A credit note with its articles, inserted directly: InvoiceAvoirFactory is an empty stub (BL-31).
 *
 * @param  list<array{designation: string, quantity: int, prix_unitaire: float, montant_ht: float}>  $articles
 */
function makeAvoir(int $companyId, int $clientId, int $invoiceId, string $code, array $articles = []): InvoiceAvoir
{
    $total = array_sum(array_column($articles, 'montant_ht'));
    $id = DB::table('invoice_avoirs')->insertGetId([
        'uuid' => (string) Str::uuid(), 'code' => $code, 'full_number' => 'AV-'.$code, 'company_id' => $companyId, 'client_id' => $clientId,
        'invoice_id' => $invoiceId, 'invoice_number' => '00042', 'invoice_date' => '2026-11-10', 'payment_mode' => 'Virement', 'status' => 'paid',
        'price_ht' => $total, 'price_tva' => $total * 0.2, 'price_total' => $total * 1.2, 'created_at' => '2026-11-10 09:00:00', 'updated_at' => '2026-11-10 09:00:00',
    ]);

    foreach ($articles as $article) {
        DB::table('articles')->insert(['uuid' => (string) Str::uuid(), 'articleable_id' => $id, 'articleable_type' => InvoiceAvoir::class, 'remise' => 0, ...$article]);
    }

    return InvoiceAvoir::findOrFail($id);
}

it('lists the credit notes on the kit layout', function () {
    $avoir = makeAvoir($this->companyId, $this->client->id, $this->invoiceId, '00003');

    $this->get(route('commercial:invoices.index.avoir'))
        ->assertOk()
        ->assertSee('<title>Factures d’avoir - CASAMAINTENANCE</title>', false)
        ->assertDontSee('dataTables', false)
        ->assertSee('AV-00003')
        ->assertSee('Payée')
        ->assertSee('href="'.$avoir->edit_url.'"', false);
});

it('offers the invoices without a credit note, with their company, client and next number', function () {
    makeAvoir($this->companyId, $this->client->id, $this->invoiceId, '00003');
    DB::table('invoices')->insert([
        'uuid' => (string) Str::uuid(), 'code' => '00043', 'full_number' => 'FAC-00043', 'company_id' => $this->companyId, 'client_id' => $this->client->id,
    ]);

    $this->get(route('commercial:invoices.create.avoir'))
        ->assertOk()
        ->assertSee('FAC-00043 · Atlas Services')
        ->assertDontSee('FAC-00042 · Atlas Services')
        ->assertSee('AV-00004')
        ->assertDontSee('wire:', false);
});

it('creates a credit note on an invoice', function () {
    $free = DB::table('invoices')->insertGetId([
        'uuid' => (string) Str::uuid(), 'code' => '00043', 'full_number' => 'FAC-00043', 'company_id' => $this->companyId, 'client_id' => $this->client->id,
    ]);

    $this->post(route('commercial:invoices.store.avoir'), [
        'invoice_number' => $free, 'company' => $this->companyId, 'client' => $this->client->id,
        'invoice_date' => '2026-11-25', 'payment_mode' => 'Virement',
        'articles' => [['designation' => 'Remboursement', 'quantity' => 1, 'prix_unitaire' => 50]],
    ])->assertRedirect();

    $avoir = InvoiceAvoir::latest('id')->first();
    expect($avoir->invoice_id)->toBe($free)
        ->and((float) $avoir->price_total)->toBe(60.0);
});

it('updates one article of a credit note without a discount', function () {
    $avoir = makeAvoir($this->companyId, $this->client->id, $this->invoiceId, '00003', [['designation' => 'Remboursement', 'quantity' => 1, 'prix_unitaire' => 50, 'montant_ht' => 50]]);
    $article = $avoir->articles()->first();

    $this->get($avoir->edit_url)
        ->assertOk()
        ->assertSee('form="article-form-'.$article->uuid.'"', false)
        ->assertSee('name="invoice_number" value="00042"', false)
        ->assertDontSee('-remise"', false);

    $this->from($avoir->edit_url)
        ->put(route('commercial:invoices.update.article.avoir', [$avoir->uuid, $article->uuid]), ['designation' => 'Remboursement', 'quantity' => 2, 'prix_unitaire' => 50, 'remise' => 50])
        ->assertRedirect($avoir->edit_url);

    expect((float) $article->fresh()->montant_ht)->toBe(100.0)
        ->and((float) $avoir->fresh()->price_ht)->toBe(100.0);
});

it('renders the credit note overview', function () {
    $avoir = makeAvoir($this->companyId, $this->client->id, $this->invoiceId, '00003', [['designation' => 'Remboursement', 'quantity' => 1, 'prix_unitaire' => 50, 'montant_ht' => 50]]);

    $this->get($avoir->url)
        ->assertOk()
        ->assertSee('Avoir AV-00003')
        ->assertSee('Remboursement')
        ->assertSee('<dialog id="print-avoir"', false);
});
