<?php

use App\Models\Client;
use App\Models\Finance\BLivraison;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

/*
 * K2: the delivery note pages (theme/pages/Commercial/BL) on the UI kit layout, without
 * Livewire, jQuery or DataTables.
 */

uses(RefreshDatabase::class);

beforeEach(function () {
    useFakeViteServer();

    $user = User::factory()->create();
    $user->assignRole(Role::findOrCreate('SuperAdmin', 'admin'));
    $this->actingAs($user);

    $this->companyId = DB::table('companies')->insertGetId([
        'id' => 1, 'uuid' => (string) Str::uuid(), 'name' => 'CASA MAINTENANCE', 'addresse' => 'Casablanca', 'ice' => '001234567890123',
        'prefix_bcommand' => 'BC-', 'bcommand_start_number' => 1, 'prefix_blivraison' => 'BL-', 'blivraison_start_number' => 1,
    ]);
    $this->client = Client::factory()->create(['entreprise' => 'Atlas Services'])->refresh();
});

/**
 * A delivery note with its articles, inserted directly: there is no BLivraison factory.
 *
 * @param  list<array{designation: string, quantity: int, prix_unitaire: float, montant_ht: float}>  $articles
 */
function makeBLivraison(int $companyId, int $clientId, string $code, array $articles = []): BLivraison
{
    $total = array_sum(array_column($articles, 'montant_ht'));
    $id = DB::table('b_livraisons')->insertGetId([
        'uuid' => (string) Str::uuid(), 'code' => $code, 'full_number' => 'BL-'.$code, 'bc_number' => 'BC-CLIENT-77', 'company_id' => $companyId, 'client_id' => $clientId,
        'date_bl' => '2026-11-10', 'price_ht' => $total, 'price_tva' => $total * 0.2, 'price_total' => $total * 1.2,
        'created_at' => '2026-11-10 09:00:00', 'updated_at' => '2026-11-10 09:00:00',
    ]);

    foreach ($articles as $article) {
        DB::table('articles')->insert(['uuid' => (string) Str::uuid(), 'articleable_id' => $id, 'articleable_type' => BLivraison::class, 'remise' => 0, ...$article]);
    }

    return BLivraison::findOrFail($id);
}

it('lists the delivery notes on the kit layout', function () {
    $note = makeBLivraison($this->companyId, $this->client->id, '00003');

    $this->get(route('commercial:blivraison.index'))
        ->assertOk()
        ->assertSee('<title>Bons de livraison - CASAMAINTENANCE</title>', false)
        ->assertDontSee('dataTables', false)
        ->assertSee('BL-00003')
        ->assertSee('BC-CLIENT-77')
        ->assertSee('href="'.$note->edit_url.'"', false);
});

it('shows the number the next delivery note will get, from the delivery note counter', function () {
    makeBLivraison($this->companyId, $this->client->id, '00003');
    $providerId = DB::table('providers')->insertGetId(['uuid' => (string) Str::uuid(), 'code' => 'F-0001', 'entreprise' => 'Pièces Atlas', 'addresse' => 'Casablanca']);
    DB::table('b_commands')->insert([
        'uuid' => (string) Str::uuid(), 'code' => '00041', 'full_number' => 'BC-00041', 'company_id' => $this->companyId, 'provider_id' => $providerId, 'date_command' => '2026-11-10',
    ]);

    $this->withViewErrors([])
        ->get(route('commercial:blivraison.create'))
        ->assertOk()
        ->assertSee('BL-00004')
        ->assertDontSee('BL-00042')
        ->assertDontSee('wire:', false);
});

it('creates a delivery note', function () {
    $this->post(route('commercial:blivraison.createPost'), [
        'company' => $this->companyId, 'client' => $this->client->id, 'bc_number' => 'BC-CLIENT-78', 'date_bl' => '2026-11-25',
        'articles' => [['designation' => 'Écran 15 pouces', 'quantity' => 2, 'prix_unitaire' => 50]],
    ])->assertRedirect();

    $note = BLivraison::latest('id')->first();
    expect($note->full_number)->toBe('BL-00001')
        ->and($note->bc_number)->toBe('BC-CLIENT-78')
        ->and((float) $note->price_total)->toBe(120.0);
});

it('updates one article of a delivery note', function () {
    $note = makeBLivraison($this->companyId, $this->client->id, '00003', [['designation' => 'Écran', 'quantity' => 1, 'prix_unitaire' => 50, 'montant_ht' => 50]]);
    $article = $note->articles()->first();

    $this->withViewErrors([])
        ->get($note->edit_url)
        ->assertOk()
        ->assertSee('form="article-form-'.$article->uuid.'"', false)
        ->assertSee('value="BC-CLIENT-77"', false);

    $this->from($note->edit_url)
        ->put(route('commercial:blivraison.update.article', [$note->uuid, $article->uuid]), ['designation' => 'Écran', 'quantity' => 3, 'prix_unitaire' => 50])
        ->assertRedirect($note->edit_url);

    expect((float) $article->fresh()->montant_ht)->toBe(150.0)
        ->and((float) $note->fresh()->price_total)->toBe(180.0);
});

it('saves the details without new articles', function () {
    $note = makeBLivraison($this->companyId, $this->client->id, '00003', [['designation' => 'Écran', 'quantity' => 1, 'prix_unitaire' => 50, 'montant_ht' => 50]]);

    $this->post(route('commercial:blivraison.update', $note->uuid), [
        'company' => $this->companyId, 'client' => $this->client->id, 'bc_number' => 'BC-CLIENT-79', 'date_bl' => '2026-11-20',
    ])->assertRedirect($note->edit_url)->assertSessionHasNoErrors();

    expect($note->fresh()->bc_number)->toBe('BC-CLIENT-79')
        ->and($note->articles()->count())->toBe(1);
});

it('renders the delivery note overview', function () {
    $note = makeBLivraison($this->companyId, $this->client->id, '00003', [['designation' => 'Écran', 'quantity' => 1, 'prix_unitaire' => 50, 'montant_ht' => 50]]);

    $this->get($note->url)
        ->assertOk()
        ->assertSee('Bon de livraison BL-00003')
        ->assertSee('Atlas Services')
        ->assertSee('<dialog id="print-bl"', false);
});
