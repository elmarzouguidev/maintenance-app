<?php

use App\Constants\Etat;
use App\Constants\Status;
use App\Models\Client;
use App\Models\Finance\Estimate;
use App\Models\Finance\Invoice;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

/*
 * K2: the estimates pages (theme/pages/Commercial/Estimate) on the UI kit layout, without
 * Livewire, jQuery or DataTables, and the invoice made from an estimate.
 */

uses(RefreshDatabase::class);

beforeEach(function () {
    useFakeViteServer();

    $user = User::factory()->create();
    $user->assignRole(Role::findOrCreate('SuperAdmin', 'admin'));
    $this->actingAs($user);

    $this->companyId = DB::table('companies')->insertGetId([
        'id' => 1, 'uuid' => (string) Str::uuid(), 'name' => 'CASA MAINTENANCE', 'addresse' => 'Casablanca', 'ice' => '001234567890123',
        'prefix_estimate' => 'DEV-', 'estimate_start_number' => 1, 'prefix_invoice' => 'FAC-', 'invoice_start_number' => 1,
    ]);
    $this->client = Client::factory()->create(['entreprise' => 'Atlas Services'])->refresh();
});

/**
 * An estimate with its articles, inserted directly: EstimateFactory is an empty stub (BL-31).
 *
 * @param  list<array{designation: string, quantity: int, prix_unitaire: float, montant_ht: float}>  $articles
 */
function makeEstimate(int $companyId, int $clientId, string $code, int $status = 0, array $articles = []): Estimate
{
    $total = array_sum(array_column($articles, 'montant_ht'));
    $id = DB::table('estimates')->insertGetId([
        'uuid' => (string) Str::uuid(), 'code' => $code, 'full_number' => 'DEV-'.$code, 'company_id' => $companyId, 'client_id' => $clientId,
        'status' => $status, 'estimate_date' => '2026-11-10', 'due_date' => '2026-12-10', 'payment_mode' => 'Virement',
        'price_ht' => $total, 'price_tva' => $total * 0.2, 'price_total' => $total * 1.2, 'created_at' => '2026-11-10 09:00:00', 'updated_at' => '2026-11-10 09:00:00',
    ]);

    foreach ($articles as $article) {
        DB::table('articles')->insert(['uuid' => (string) Str::uuid(), 'articleable_id' => $id, 'articleable_type' => Estimate::class, 'remise' => 0, ...$article]);
    }

    return Estimate::findOrFail($id);
}

it('lists the estimates on the kit layout with their status and invoice action', function () {
    $estimate = makeEstimate($this->companyId, $this->client->id, '00042', 1);

    $this->get(route('commercial:estimates.index'))
        ->assertOk()
        ->assertSee('<title>Devis - CASAMAINTENANCE</title>', false)
        ->assertDontSee('dataTables', false)
        ->assertSee('00042')
        ->assertSee('Accepté')
        ->assertSee('href="'.$estimate->create_invoice_url.'"', false);
});

it('filters the estimates by status', function () {
    makeEstimate($this->companyId, $this->client->id, '00001', 1);
    makeEstimate($this->companyId, $this->client->id, '00002', 2);

    $this->get(route('commercial:estimates.index', ['appFilter' => ['GetStatus' => 2]]))
        ->assertOk()
        ->assertSee('00002')
        ->assertDontSee('00001');
});

it('pages the estimates thirty at a time', function () {
    foreach (range(1, 31) as $number) {
        makeEstimate($this->companyId, $this->client->id, sprintf('%05d', $number));
    }

    $this->get(route('commercial:estimates.index'))
        ->assertOk()
        ->assertSee('href="'.route('commercial:estimates.index', ['page' => 2]).'"', false);
});

it('shows the next estimate number and loads the client tickets waiting for an estimate', function () {
    makeEstimate($this->companyId, $this->client->id, '00007');
    $waiting = DB::table('tickets')->insertGetId(['uuid' => (string) Str::uuid(), 'code' => 'TCK-1', 'article' => 'PC', 'description' => 'Panne', 'client_id' => $this->client->id, 'etat' => Etat::REPARABLE, 'status' => Status::EN_ATTENTE_DE_DEVIS]);
    DB::table('tickets')->insert(['uuid' => (string) Str::uuid(), 'code' => 'TCK-2', 'article' => 'PC', 'description' => 'Panne', 'client_id' => $this->client->id, 'etat' => Etat::REPARABLE, 'status' => Status::NON_TRAITE]);

    $this->get(route('commercial:estimates.create'))
        ->assertOk()
        ->assertSee('DEV-00008')
        ->assertDontSee('wire:', false);

    $this->getJson(route('commercial:estimates.client.tickets', $this->client->id))
        ->assertOk()
        ->assertExactJson([['id' => $waiting, 'code' => 'TCK-1']]);
});

it('fixes the client and the ticket on the estimate-from-ticket form', function () {
    $ticketUuid = (string) Str::uuid();
    $ticketId = DB::table('tickets')->insertGetId(['uuid' => $ticketUuid, 'code' => 'TCK-9', 'article' => 'Imprimante', 'description' => 'Panne', 'client_id' => $this->client->id]);

    $this->get(route('commercial:estimates.create.ticket', $ticketUuid))
        ->assertOk()
        ->assertSee('Devis du ticket TCK-9')
        ->assertSee('name="ticket" value="'.$ticketId.'"', false)
        ->assertSee('name="client" value="'.$this->client->id.'"', false);
});

it('creates an estimate with its articles and totals', function () {
    $response = $this->post(route('commercial:estimates.store'), [
        'company' => $this->companyId, 'client' => $this->client->id,
        'estimate_date' => '2026-11-25', 'due_date' => '2026-12-25', 'payment_mode' => 'Virement',
        'articles' => [['designation' => 'Réparation', 'quantity' => 2, 'prix_unitaire' => 100, 'remise' => 0]],
    ]);

    $estimate = Estimate::latest('id')->first();
    $response->assertRedirect($estimate->edit_url);
    expect((float) $estimate->price_total)->toBe(240.0);
});

it('shows the edit page and updates one article and the totals', function () {
    $estimate = makeEstimate($this->companyId, $this->client->id, '00042', 0, [['designation' => 'Diagnostic', 'quantity' => 1, 'prix_unitaire' => 100, 'montant_ht' => 100]]);
    $article = $estimate->articles()->first();

    $this->get($estimate->edit_url)
        ->assertOk()
        ->assertSee('form="article-form-'.$article->uuid.'"', false)
        ->assertSee('<dialog id="send-estimate"', false)
        ->assertSee('name="estimater" value="'.$estimate->uuid.'"', false)
        ->assertDontSee('wire:', false);

    $this->from($estimate->edit_url)
        ->put(route('commercial:estimates.update.article', [$estimate->uuid, $article->uuid]), ['designation' => 'Diagnostic', 'quantity' => 3, 'prix_unitaire' => 100])
        ->assertRedirect($estimate->edit_url);

    expect((float) $estimate->fresh()->price_ht)->toBe(300.0);
});

it('fills the invoice form from the estimate and marks the estimate invoiced on save', function () {
    $estimate = makeEstimate($this->companyId, $this->client->id, '00042', 1, [['designation' => 'Diagnostic', 'quantity' => 1, 'prix_unitaire' => 100, 'montant_ht' => 100]]);

    $this->get($estimate->create_invoice_url)
        ->assertOk()
        ->assertSee('Facture du devis DEV-00042')
        ->assertSee('name="estimated" value="'.$estimate->uuid.'"', false)
        ->assertSee('FAC-00001')
        ->assertSee('Diagnostic');

    $this->post(route('commercial:invoices.store'), [
        'company' => $this->companyId, 'client' => $this->client->id, 'estimated' => $estimate->uuid,
        'invoice_date' => '2026-11-25', 'due_date' => '2026-12-25', 'payment_mode' => 'Virement',
        'articles' => [['designation' => 'Diagnostic', 'quantity' => 1, 'prix_unitaire' => 100, 'remise' => 0]],
    ])->assertRedirect();

    expect($estimate->fresh()->is_invoiced)->toBeTruthy()
        ->and(Invoice::count())->toBe(1);
});

it('renders the estimate overview', function () {
    $estimate = makeEstimate($this->companyId, $this->client->id, '00042', 0, [['designation' => 'Diagnostic', 'quantity' => 1, 'prix_unitaire' => 100, 'montant_ht' => 100]]);

    $this->get($estimate->url)
        ->assertOk()
        ->assertSee('Devis DEV-00042')
        ->assertSee('Diagnostic')
        ->assertSee('<dialog id="print-estimate"', false);
});
