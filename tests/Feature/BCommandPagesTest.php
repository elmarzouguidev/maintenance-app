<?php

use App\Models\Finance\BCommand;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

/*
 * K2: the purchase order pages (theme/pages/Commercial/BC) on the UI kit layout, without
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
        'prefix_bcommand' => 'BC-', 'bcommand_start_number' => 1,
    ]);
    $this->providerId = DB::table('providers')->insertGetId([
        'uuid' => (string) Str::uuid(), 'code' => 'F-0001', 'entreprise' => 'Pièces Atlas', 'email' => 'contact@pieces-atlas.test', 'addresse' => 'Casablanca',
    ]);
});

/**
 * A purchase order with its articles, inserted directly: there is no BCommand factory.
 *
 * @param  list<array{designation: string, quantity: int, prix_unitaire: float, montant_ht: float}>  $articles
 */
function makeBCommand(int $companyId, int $providerId, string $code, array $articles = [], ?string $adminNotes = null): BCommand
{
    $total = array_sum(array_column($articles, 'montant_ht'));
    $id = DB::table('b_commands')->insertGetId([
        'uuid' => (string) Str::uuid(), 'code' => $code, 'full_number' => 'BC-'.$code, 'company_id' => $companyId, 'provider_id' => $providerId,
        'date_command' => '2026-11-10', 'admin_notes' => $adminNotes, 'price_ht' => $total, 'price_tva' => $total * 0.2, 'price_total' => $total * 1.2,
        'created_at' => '2026-11-10 09:00:00', 'updated_at' => '2026-11-10 09:00:00',
    ]);

    foreach ($articles as $article) {
        DB::table('articles')->insert(['uuid' => (string) Str::uuid(), 'articleable_id' => $id, 'articleable_type' => BCommand::class, 'remise' => 0, ...$article]);
    }

    return BCommand::findOrFail($id);
}

it('lists the purchase orders on the kit layout', function () {
    $command = makeBCommand($this->companyId, $this->providerId, '00003');

    $this->get(route('commercial:bcommandes.index'))
        ->assertOk()
        ->assertSee('<title>Bons de commande - CASAMAINTENANCE</title>', false)
        ->assertDontSee('dataTables', false)
        ->assertSee('BC-00003')
        ->assertSee('Pièces Atlas')
        ->assertSee('href="'.$command->edit_url.'"', false);
});

it('shows the number the next purchase order will get', function () {
    makeBCommand($this->companyId, $this->providerId, '00003');

    $this->withViewErrors([])
        ->get(route('commercial:bcommandes.create'))
        ->assertOk()
        ->assertSee('BC-00004')
        ->assertDontSee('wire:', false);
});

it('creates a purchase order', function () {
    $this->post(route('commercial:bcommandes.createPost'), [
        'company' => $this->companyId, 'provider' => $this->providerId, 'date_command' => '2026-11-25',
        'articles' => [['designation' => 'Écran 15 pouces', 'quantity' => 2, 'prix_unitaire' => 50]],
    ])->assertRedirect();

    $command = BCommand::latest('id')->first();
    expect($command->full_number)->toBe('BC-00001')
        ->and((float) $command->price_total)->toBe(120.0);
});

it('updates one article of a purchase order', function () {
    $command = makeBCommand($this->companyId, $this->providerId, '00003', [['designation' => 'Écran', 'quantity' => 1, 'prix_unitaire' => 50, 'montant_ht' => 50]]);
    $article = $command->articles()->first();

    $this->withViewErrors([])
        ->get($command->edit_url)
        ->assertOk()
        ->assertSee('form="article-form-'.$article->uuid.'"', false);

    $this->from($command->edit_url)
        ->put(route('commercial:bcommandes.update.article', [$command->uuid, $article->uuid]), ['designation' => 'Écran', 'quantity' => 3, 'prix_unitaire' => 50])
        ->assertRedirect($command->edit_url);

    expect((float) $article->fresh()->montant_ht)->toBe(150.0)
        ->and((float) $command->fresh()->price_total)->toBe(180.0);
});

it('saves the details without new articles and keeps the administration note', function () {
    $command = makeBCommand($this->companyId, $this->providerId, '00003', [['designation' => 'Écran', 'quantity' => 1, 'prix_unitaire' => 50, 'montant_ht' => 50]], 'Livraison urgente');

    $this->withViewErrors([])
        ->get($command->edit_url)
        ->assertSee('Livraison urgente');

    $this->post(route('commercial:bcommandes.update', $command->uuid), [
        'company' => $this->companyId, 'provider' => $this->providerId, 'date_command' => '2026-11-20', 'admin_notes' => 'Livraison urgente',
    ])->assertRedirect($command->edit_url)->assertSessionHasNoErrors();

    expect($command->fresh()->date_command->format('Y-m-d'))->toBe('2026-11-20')
        ->and($command->articles()->count())->toBe(1);
});

it('renders the purchase order overview', function () {
    $command = makeBCommand($this->companyId, $this->providerId, '00003', [['designation' => 'Écran', 'quantity' => 1, 'prix_unitaire' => 50, 'montant_ht' => 50]]);

    $this->get($command->url)
        ->assertOk()
        ->assertSee('Bon de commande BC-00003')
        ->assertSee('Pièces Atlas')
        ->assertSee('<dialog id="print-bc"', false);
});
