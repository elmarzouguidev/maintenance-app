<?php

use App\Exports\ProvidersListExport;
use App\Models\Finance\Provider;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Models\Role;

/*
 * K2: the suppliers pages (theme/pages/Commercial/Provider) on the UI kit layout: a server-side
 * list with search and exports (replacing DataTables), the create and edit forms with the
 * extra phones and e-mails.
 */

uses(RefreshDatabase::class);

beforeEach(function () {
    useFakeViteServer();

    $this->superAdmin = User::factory()->create();
    $this->superAdmin->assignRole(Role::findOrCreate('SuperAdmin', 'admin'));
});

it('lists the suppliers on the kit layout, with the exports and the row actions', function () {
    $provider = Provider::factory()->create(['entreprise' => 'Atlas Pièces'])->refresh();

    $this->actingAs($this->superAdmin)
        ->get(route('commercial:providers.index'))
        ->assertOk()
        ->assertSee('<title>Fournisseurs - CASAMAINTENANCE</title>', false)
        ->assertSee('http://vite.test/resources/css/ui-kit.css', false)
        ->assertDontSee('dataTables', false)
        ->assertSee('Atlas Pièces')
        ->assertSee('href="'.route('commercial:providers.export', ['format' => 'xlsx']).'"', false)
        ->assertSee('href="'.$provider->edit.'"', false)
        ->assertSee('action="'.route('commercial:providers.delete', ['providerId' => $provider->uuid]).'"', false);
});

it('filters the suppliers and exports the same search', function () {
    Excel::fake();
    Provider::factory()->create(['entreprise' => 'Atlas Pièces']);
    Provider::factory()->create(['entreprise' => 'Souss Électronique']);

    $this->actingAs($this->superAdmin)
        ->get(route('commercial:providers.index', ['q' => 'Atlas']))
        ->assertOk()
        ->assertSee('Atlas Pièces')
        ->assertDontSee('Souss Électronique');

    $this->actingAs($this->superAdmin)
        ->get(route('commercial:providers.export', ['format' => 'xlsx', 'q' => 'Atlas']))
        ->assertOk();

    Excel::assertDownloaded('fournisseurs.xlsx', function (ProvidersListExport $export): bool {
        $rows = $export->query()->get()->map(fn ($provider): array => $export->map($provider));

        return $rows->count() === 1 && $rows[0][1] === 'Atlas Pièces';
    });
});

it('creates a supplier', function () {
    $this->actingAs($this->superAdmin)
        ->get(route('commercial:providers.create'))
        ->assertOk()
        ->assertSee('action="'.route('commercial:providers.createPost').'"', false)
        ->assertDontSee('name="logo"', false);

    $this->actingAs($this->superAdmin)
        ->followingRedirects()
        ->from(route('commercial:providers.create'))
        ->post(route('commercial:providers.createPost'), [
            'entreprise' => 'Nour Pièces', 'contact' => 'Omar Tazi', 'telephone' => '0522123456', 'email' => 'nour@example.com',
            'addresse' => 'Casablanca', 'ice' => '001234567890123',
        ])
        ->assertOk()
        ->assertSee("L'ajoute a éte effectuer avec success");

    expect(Provider::where('email', 'nour@example.com')->exists())->toBeTrue();
});

it('shows the extra phones and e-mails on the edit page and deletes a phone with the page\'s request', function () {
    $provider = Provider::factory()->create()->refresh();
    $phone = $provider->telephones()->create(['telephone' => '0612345678'])->refresh();
    $provider->emails()->create(['email' => 'achats@example.com']);

    $this->actingAs($this->superAdmin)
        ->get($provider->edit)
        ->assertOk()
        ->assertSee('action="'.route('commercial:providers.update', $provider->uuid).'"', false)
        ->assertSee('0612345678')
        ->assertSee('achats@example.com')
        ->assertSee('name="secend_phone"', false)
        ->assertSee($phone->uuid);

    $this->actingAs($this->superAdmin)
        ->deleteJson(route('commercial:providers.delete.phone'), ['provider' => $provider->uuid, 'phone' => $phone->uuid])
        ->assertOk();

    expect($provider->telephones()->count())->toBe(0);
});

it('keeps the delete action as before: success message, supplier kept (BL-70)', function () {
    $provider = Provider::factory()->create()->refresh();

    $this->actingAs($this->superAdmin)
        ->followingRedirects()
        ->from(route('commercial:providers.index'))
        ->delete(route('commercial:providers.delete', ['providerId' => $provider->uuid]))
        ->assertOk()
        ->assertSee('La supprission a été effectué  avec success');

    expect(Provider::whereKey($provider->id)->exists())->toBeTrue();
});
