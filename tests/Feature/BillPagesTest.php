<?php

use App\Models\Client;
use App\Models\Finance\Bill;
use App\Models\Finance\Invoice;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

/*
 * K2: the payment pages (theme/pages/Commercial/Bill) on the UI kit layout, without jQuery,
 * DataTables or Bootstrap modals.
 */

uses(RefreshDatabase::class);

beforeEach(function () {
    useFakeViteServer();

    $user = User::factory()->create();
    $user->assignRole(Role::findOrCreate('SuperAdmin', 'admin'));
    $this->actingAs($user);

    $this->companyId = DB::table('companies')->insertGetId([
        'id' => 1, 'uuid' => (string) Str::uuid(), 'name' => 'CASA MAINTENANCE', 'addresse' => 'Casablanca', 'ice' => '001234567890123',
    ]);
    $this->client = Client::factory()->create(['entreprise' => 'Atlas Services'])->refresh();
    $this->invoice = makeBillInvoice($this->companyId, $this->client->id, '00042');
});

function makeBillInvoice(int $companyId, int $clientId, string $code): Invoice
{
    $id = DB::table('invoices')->insertGetId([
        'uuid' => (string) Str::uuid(), 'code' => $code, 'full_number' => 'FAC-'.$code, 'company_id' => $companyId, 'client_id' => $clientId,
        'status' => 'non-paid', 'invoice_date' => '2026-11-10', 'due_date' => '2026-12-10', 'price_ht' => 100, 'price_tva' => 20, 'price_total' => 120,
    ]);

    return Invoice::findOrFail($id);
}

function makeBill(Invoice $invoice, int $companyId): Bill
{
    $id = DB::table('bills')->insertGetId([
        'uuid' => (string) Str::uuid(), 'code' => '00007', 'full_number' => 'REGL-00007', 'billable_id' => $invoice->id, 'billable_type' => Invoice::class,
        'company_id' => $companyId, 'bill_date' => '2026-11-20', 'bill_mode' => 'Chèque', 'reference' => 'CHQ-881', 'price_total' => 120, 'added_by' => 'Sara',
        'created_at' => '2026-11-20 09:00:00', 'updated_at' => '2026-11-20 09:00:00',
    ]);

    return Bill::findOrFail($id);
}

it('lists the payments and offers the unpaid invoices in the add dialog', function () {
    makeBill($this->invoice, $this->companyId);
    makeBillInvoice($this->companyId, $this->client->id, '00043');

    $this->withViewErrors([])
        ->get(route('commercial:bills.index'))
        ->assertOk()
        ->assertSee('<title>Règlements - CASAMAINTENANCE</title>', false)
        ->assertDontSee('dataTables', false)
        ->assertSee('00007')
        ->assertSee('CHQ-881')
        ->assertSee('Atlas Services')
        ->assertSee('<dialog id="add-bill"', false)
        ->assertSee('FAC-00043 · ')
        ->assertDontSee('FAC-00042 · ');
});

it('adds a payment from the list dialog and marks the invoice paid', function () {
    $this->post(route('commercial:bills.store.normal'), [
        'invoice' => $this->invoice->uuid, 'bill_date' => '2026-11-25', 'bill_mode' => 'Virement', 'reference' => 'VIR-12',
    ])->assertRedirect(route('commercial:bills.index'));

    expect($this->invoice->fresh()->status)->toBe('paid')
        ->and((float) $this->invoice->bill()->first()->price_total)->toBe(120.0);
});

it('renders the payment page of one invoice', function () {
    $this->withViewErrors([])
        ->get(route('commercial:bills.addBill', $this->invoice->uuid))
        ->assertOk()
        ->assertSee('Règlement de la facture FAC-00042')
        ->assertSee('action="'.route('commercial:bills.storeBill', $this->invoice->uuid).'"', false);
});

it('updates a payment without posting the amount', function () {
    $bill = makeBill($this->invoice, $this->companyId);

    $this->withViewErrors([])
        ->get($bill->edit_url)
        ->assertOk()
        ->assertSee('CHQ-881');

    $this->post(route('commercial:bills.update', $bill->uuid), [
        'bill_date' => '2026-11-21', 'bill_mode' => 'Virement', 'reference' => 'VIR-99', 'notes' => 'Reçu',
    ])->assertRedirect(route('commercial:bills.index'))->assertSessionHasNoErrors();

    expect($bill->fresh()->reference)->toBe('VIR-99')
        ->and($bill->fresh()->bill_mode)->toBe('Virement');
});

it('sends the former new payment page to the list', function () {
    $this->get(route('commercial:bills.create'))->assertRedirect(route('commercial:bills.index'));
});
