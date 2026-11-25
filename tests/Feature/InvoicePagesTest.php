<?php

use App\Constants\Etat;
use App\Models\Client;
use App\Models\Finance\Invoice;
use App\Models\User;
use App\Support\AmountInWords;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

/*
 * K2: the invoices pages (theme/pages/Commercial/Invoice) on the UI kit layout, without
 * Livewire, jQuery or DataTables: the filtered list, the create form (client tickets loaded
 * from JSON), the edit page (article updates replacing the Livewire EditArticle) and the
 * overview, which crashed before (BL-75).
 */

uses(RefreshDatabase::class);

beforeEach(function () {
    useFakeViteServer();

    $this->superAdmin = User::factory()->create();
    $this->superAdmin->assignRole(Role::findOrCreate('SuperAdmin', 'admin'));
    $this->actingAs($this->superAdmin);

    // The list only shows the invoices of company 1 (InvoiceController::indexFilter()).
    $this->companyId = DB::table('companies')->insertGetId([
        'id' => 1, 'uuid' => (string) Str::uuid(), 'name' => 'CASA MAINTENANCE', 'addresse' => 'Casablanca', 'ice' => '001234567890123',
        'prefix_invoice' => 'FAC-', 'invoice_start_number' => 1,
    ]);
    $this->client = Client::factory()->create(['entreprise' => 'Atlas Services'])->refresh();
});

/**
 * An invoice with its articles, inserted directly: InvoiceFactory is an empty stub (BL-31).
 *
 * @param  list<array{designation: string, quantity: int, prix_unitaire: float, montant_ht: float}>  $articles
 */
function makeInvoice(int $companyId, int $clientId, string $code, string $status = 'non-paid', array $articles = []): Invoice
{
    $total = array_sum(array_column($articles, 'montant_ht'));
    $id = DB::table('invoices')->insertGetId([
        'uuid' => (string) Str::uuid(), 'code' => $code, 'full_number' => 'FAC-'.$code, 'company_id' => $companyId, 'client_id' => $clientId,
        'status' => $status, 'invoice_date' => '2026-11-10', 'due_date' => '2026-12-10', 'payment_mode' => 'Virement',
        'price_ht' => $total, 'price_tva' => $total * 0.2, 'price_total' => $total * 1.2, 'created_at' => '2026-11-10 09:00:00', 'updated_at' => '2026-11-10 09:00:00',
    ]);

    foreach ($articles as $article) {
        DB::table('articles')->insert(['uuid' => (string) Str::uuid(), 'articleable_id' => $id, 'articleable_type' => Invoice::class, 'remise' => 0, ...$article]);
    }

    return Invoice::findOrFail($id);
}

function makeTicket(int $clientId, string $code, int $etat = Etat::REPARABLE): int
{
    return DB::table('tickets')->insertGetId([
        'uuid' => (string) Str::uuid(), 'code' => $code, 'article' => 'Imprimante', 'description' => 'Panne', 'client_id' => $clientId, 'etat' => $etat,
    ]);
}

it('lists the invoices on the kit layout, with their status and payment action', function () {
    $invoice = makeInvoice($this->companyId, $this->client->id, '00042');

    $this->get(route('commercial:invoices.index'))
        ->assertOk()
        ->assertSee('<title>Factures - CASAMAINTENANCE</title>', false)
        ->assertSee('http://vite.test/resources/css/ui-kit.css', false)
        ->assertDontSee('dataTables', false)
        ->assertSee('00042')
        ->assertSee('Atlas Services')
        ->assertSee('À régler')
        ->assertSee('Ajouter un règlement')
        ->assertSee('<dialog id="add-payment"', false)
        ->assertSee('href="'.$invoice->edit_url.'"', false);
});

it('filters the invoices by status and keeps the filters in the period links', function () {
    makeInvoice($this->companyId, $this->client->id, '00001', 'paid');
    makeInvoice($this->companyId, $this->client->id, '00002', 'non-paid');

    $response = $this->get(route('commercial:invoices.index', ['appFilter' => ['GetStatus' => 'paid']]));

    $response->assertOk()
        ->assertSee('00001')
        ->assertDontSee('00002')
        ->assertSee('Payée')
        ->assertSee(e(route('commercial:invoices.index', ['appFilter' => ['GetStatus' => 'paid', 'GetPeriod' => 1]])), false);
});

it('shows the next invoice number of each company and the client list on the create form', function () {
    makeInvoice($this->companyId, $this->client->id, '00007');

    $this->get(route('commercial:invoices.create'))
        ->assertOk()
        ->assertSee('action="'.route('commercial:invoices.store').'"', false)
        ->assertSee('FAC-00008')
        ->assertSee('Atlas Services')
        ->assertDontSee('wire:', false);
});

it('returns the client tickets that can be invoiced', function () {
    $repairable = makeTicket($this->client->id, 'TCK-1');
    makeTicket($this->client->id, 'TCK-2', Etat::NON_REPARABLE);
    $invoiced = makeTicket($this->client->id, 'TCK-3');
    $invoice = makeInvoice($this->companyId, $this->client->id, '00009');
    DB::table('invoices')->where('id', $invoice->id)->update(['ticket_id' => $invoiced]);

    $this->getJson(route('commercial:invoices.client.tickets', $this->client->id))
        ->assertOk()
        ->assertExactJson([['id' => $repairable, 'code' => 'TCK-1']]);
});

it('creates an invoice with its articles, totals and tickets', function () {
    $ticket = makeTicket($this->client->id, 'TCK-1');

    $response = $this->post(route('commercial:invoices.store'), [
        'company' => $this->companyId, 'client' => $this->client->id, 'tickets' => [$ticket],
        'invoice_date' => '2026-11-25', 'due_date' => '2026-12-25', 'payment_mode' => 'Virement',
        'articles' => [
            ['designation' => 'Réparation imprimante', 'quantity' => 2, 'prix_unitaire' => 100, 'remise' => 10],
            ['designation' => 'Pièce', 'quantity' => 1, 'prix_unitaire' => 50, 'remise' => 0],
        ],
    ]);

    $invoice = Invoice::latest('id')->first();
    $response->assertRedirect($invoice->edit_url);
    expect((float) $invoice->price_ht)->toBe(230.0)
        ->and((float) $invoice->price_total)->toBe(276.0)
        ->and($invoice->articles()->count())->toBe(2)
        ->and($invoice->tickets()->pluck('tickets.id')->all())->toBe([$ticket]);
});

it('shows the existing articles as their own forms on the edit page', function () {
    $invoice = makeInvoice($this->companyId, $this->client->id, '00042', 'non-paid', [['designation' => 'Diagnostic', 'quantity' => 1, 'prix_unitaire' => 100, 'montant_ht' => 100]]);
    $article = $invoice->articles()->first();

    $this->get($invoice->edit_url)
        ->assertOk()
        ->assertSee('action="'.$invoice->update_url.'"', false)
        ->assertSee('id="article-form-'.$article->uuid.'"', false)
        ->assertSee('form="article-form-'.$article->uuid.'"', false)
        ->assertSee('action="'.route('commercial:invoices.update.article', [$invoice->uuid, $article->uuid]).'"', false)
        ->assertSee('Enregistrer la facture')
        ->assertDontSee('wire:', false);
});

it('makes a paid invoice read-only', function () {
    $invoice = makeInvoice($this->companyId, $this->client->id, '00042', 'paid');
    DB::table('bills')->insert(['uuid' => (string) Str::uuid(), 'code' => 'R1', 'full_number' => 'REGL-R1', 'billable_id' => $invoice->id, 'billable_type' => Invoice::class, 'price_total' => 0]);

    $this->get($invoice->edit_url)
        ->assertOk()
        ->assertSee('Cette facture a un règlement')
        ->assertDontSee('Enregistrer la facture');
});

it('updates one article and the invoice totals', function () {
    $invoice = makeInvoice($this->companyId, $this->client->id, '00042', 'non-paid', [
        ['designation' => 'Diagnostic', 'quantity' => 1, 'prix_unitaire' => 100, 'montant_ht' => 100],
        ['designation' => 'Pièce', 'quantity' => 1, 'prix_unitaire' => 50, 'montant_ht' => 50],
    ]);
    $article = $invoice->articles()->where('designation', 'Diagnostic')->first();

    $this->from($invoice->edit_url)
        ->put(route('commercial:invoices.update.article', [$invoice->uuid, $article->uuid]), ['designation' => 'Diagnostic complet', 'quantity' => 2, 'prix_unitaire' => 100, 'remise' => 50])
        ->assertRedirect($invoice->edit_url);

    $invoice->refresh();
    expect($article->fresh()->designation)->toBe('Diagnostic complet')
        ->and((float) $article->fresh()->montant_ht)->toBe(100.0)
        ->and((float) $invoice->price_ht)->toBe(150.0)
        ->and((float) $invoice->price_total)->toBe(180.0)
        ->and($invoice->histories()->where('detail', 'a modifié un article de la facture')->exists())->toBeTrue();
});

it('rejects an article update without a designation, in its own error bag', function () {
    $invoice = makeInvoice($this->companyId, $this->client->id, '00042', 'non-paid', [['designation' => 'Diagnostic', 'quantity' => 1, 'prix_unitaire' => 100, 'montant_ht' => 100]]);
    $article = $invoice->articles()->first();

    $this->from($invoice->edit_url)
        ->put(route('commercial:invoices.update.article', [$invoice->uuid, $article->uuid]), ['designation' => '', 'quantity' => 1, 'prix_unitaire' => 100])
        ->assertSessionHasErrors('designation', null, 'article');

    expect($article->fresh()->designation)->toBe('Diagnostic');
});

it('adds new articles on update', function () {
    $invoice = makeInvoice($this->companyId, $this->client->id, '00042', 'non-paid', [['designation' => 'Diagnostic', 'quantity' => 1, 'prix_unitaire' => 100, 'montant_ht' => 100]]);

    $this->post($invoice->update_url, [
        'company' => $this->companyId, 'client' => $this->client->id, 'invoice_date' => '2026-11-10', 'due_date' => '2026-12-10', 'payment_mode' => 'Virement',
        'articlesnew' => [['designation' => 'Pièce', 'quantity' => 1, 'prix_unitaire' => 50, 'remise' => 0]],
    ])->assertRedirect($invoice->edit_url);

    expect($invoice->articles()->count())->toBe(2)
        ->and((float) $invoice->fresh()->price_ht)->toBe(150.0);
});

it('deletes an article with the request the edit page sends', function () {
    $invoice = makeInvoice($this->companyId, $this->client->id, '00042', 'non-paid', [['designation' => 'Diagnostic', 'quantity' => 1, 'prix_unitaire' => 100, 'montant_ht' => 100]]);
    $article = $invoice->articles()->first();

    $this->deleteJson(route('commercial:invoices.delete.article'), ['invoice' => $invoice->uuid, 'article' => $article->uuid])
        ->assertOk();

    expect($invoice->articles()->count())->toBe(0);
});

it('renders the invoice overview, which crashed on an undefined $logo (BL-75)', function () {
    $invoice = makeInvoice($this->companyId, $this->client->id, '00042', 'non-paid', [['designation' => 'Diagnostic', 'quantity' => 1, 'prix_unitaire' => 100, 'montant_ht' => 100]]);

    $this->get($invoice->url)
        ->assertOk()
        ->assertSee('Facture FAC-00042')
        ->assertSee('Diagnostic')
        ->assertSee("120,00\u{00A0}DH")
        ->assertSee('<dialog id="print-invoice"', false);
});

it('serves the invoice PDF with and without the letterhead, and from the build route', function () {
    $invoice = makeInvoice($this->companyId, $this->client->id, '00042', 'non-paid', [
        ['designation' => 'Automate Siemens S7-1200', 'quantity' => 1, 'prix_unitaire' => 3000, 'montant_ht' => 3000],
    ]);

    foreach ([1, 0] as $hasHeader) {
        $this->get(route('public.show.invoice', [$invoice->uuid, 'has_header' => $hasHeader]))
            ->assertOk()
            ->assertHeader('Content-Type', 'application/pdf');
    }

    $this->get(route('commercial:invoices.pdf.build', $invoice->uuid))
        ->assertOk()
        ->assertHeader('Content-Type', 'application/pdf');
});

it('states an amount in French words, with centimes', function () {
    expect(AmountInWords::dirhams(32400))->toBe('trente-deux mille quatre cents dirhams')
        ->and(AmountInWords::dirhams('1250.50'))->toBe('mille deux cent cinquante dirhams et cinquante centimes')
        ->and(AmountInWords::dirhams(1))->toBe('un dirham');
});
