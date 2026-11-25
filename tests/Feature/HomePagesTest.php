<?php

use App\Models\Client;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

/*
 * K2: the dashboard (theme/pages/Home) on the UI kit layout, without Livewire: the top clients
 * table of the former Livewire component is computed by DashboardController.
 */

uses(RefreshDatabase::class);

beforeEach(function () {
    useFakeViteServer();

    DB::table('companies')->insert(['id' => 1, 'uuid' => (string) Str::uuid(), 'name' => 'CASA MAINTENANCE', 'addresse' => 'Casablanca', 'ice' => '001234567890123']);
    $this->client = Client::factory()->create(['entreprise' => 'Atlas Services'])->refresh();
    DB::table('invoices')->insert([
        'uuid' => (string) Str::uuid(), 'code' => '00042', 'full_number' => 'FAC-00042', 'company_id' => 1, 'client_id' => $this->client->id,
        'status' => 'non-paid', 'invoice_date' => now()->format('Y-m-d'), 'due_date' => now()->addMonth()->format('Y-m-d'),
        'price_ht' => 1000, 'price_tva' => 200, 'price_total' => 1200, 'created_at' => now(), 'updated_at' => now(),
    ]);
});

it('shows the admins the figures, the charts and the top clients', function () {
    $admin = User::factory()->create();
    $admin->assignRole(Role::findOrCreate('SuperAdmin', 'admin'));

    $this->actingAs($admin)
        ->withViewErrors([])
        ->get(route('admin:home'))
        ->assertOk()
        ->assertSee('Chiffre d’affaires HT')
        ->assertSee('Chiffre d’affaires par client')
        ->assertSee('Atlas Services')
        ->assertSee('id="chart-chiffre-daffaires-ht-'.now()->year.'"', false)
        ->assertDontSee('wire:', false)
        ->assertDontSee('livewire', false);
});

it('applies a period filter', function () {
    $admin = User::factory()->create();
    $admin->assignRole(Role::findOrCreate('SuperAdmin', 'admin'));

    $this->actingAs($admin)
        ->withViewErrors([])
        ->get(route('admin:home', ['appFilter' => ['GetPeriod' => 1, 'GetCompany' => 1]]))
        ->assertOk()
        ->assertSee('Chiffres de la période choisie.');
});

it('shows a technician only the tickets', function () {
    $technician = User::factory()->create();
    $technician->assignRole(Role::findOrCreate('Technicien', 'admin'));

    $this->actingAs($technician)
        ->get(route('admin:home'))
        ->assertOk()
        ->assertSee('Nouveaux tickets')
        ->assertDontSee('Chiffre d’affaires');
});
