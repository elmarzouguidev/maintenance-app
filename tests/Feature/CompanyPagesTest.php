<?php

use App\Models\Finance\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/*
 * K2: the companies pages (theme/pages/Commercial/Company) on the UI kit layout.
 */

uses(RefreshDatabase::class);

beforeEach(function () {
    useFakeViteServer();

    $this->actingAs(User::factory()->create());
});

/**
 * A company inserted directly: CompanyFactory is an empty stub (BL-31).
 */
function makeCompany(string $name = 'CASA MAINTENANCE'): Company
{
    $id = DB::table('companies')->insertGetId([
        'uuid' => (string) Str::uuid(), 'name' => $name, 'addresse' => 'Casablanca', 'ice' => '001234567890123',
        'city' => 'Casablanca', 'prefix_invoice' => 'FAC',
    ]);

    return Company::findOrFail($id);
}

it('lists the companies on the kit layout, without the disabled add button', function () {
    $company = makeCompany();

    $this->get(route('commercial:companies.index'))
        ->assertOk()
        ->assertSee('<title>Sociétés - CASAMAINTENANCE</title>', false)
        ->assertSee('http://vite.test/resources/css/ui-kit.css', false)
        ->assertSee('CASA MAINTENANCE')
        ->assertSee('href="'.$company->edit_url.'"', false)
        ->assertSee('action="'.route('commercial:companies.delete', ['companyId' => $company->uuid]).'"', false)
        ->assertDontSee('href="'.route('commercial:companies.create').'"', false);
});

it('shows the edit form in sections with the company values', function () {
    $company = makeCompany();

    $this->get($company->edit_url)
        ->assertOk()
        ->assertSee('action="'.route('commercial:companies.update', $company->uuid).'"', false)
        ->assertSeeInOrder(['Identité', 'Identifiants légaux', 'Logo', 'Numérotation des documents'])
        ->assertSee('value="FAC"', false)
        ->assertSee('name="blivraison_start_number"', false);

    expect($this->get($company->edit_url)->getContent())->toMatch('/name="city"[^>]*required/');
});

it('updates the company', function () {
    $company = makeCompany();

    $this->followingRedirects()
        ->from($company->edit_url)
        ->post(route('commercial:companies.update', $company->uuid), [
            'name' => 'CASA MAINTENANCE SARL', 'city' => 'Casablanca', 'addresse' => 'Casablanca', 'ice' => '001234567890123',
            'prefix_invoice' => 'FCT', 'invoice_start_number' => '100', 'prefix_invoice_avoir' => 'AV', 'invoice_avoir_start_number' => '1',
            'prefix_estimate' => 'DEV', 'estimate_start_number' => '1', 'prefix_bcommand' => 'BC', 'bcommand_start_number' => '1',
            'prefix_blivraison' => 'BL', 'blivraison_start_number' => '1',
        ])
        ->assertOk()
        ->assertSee('La modification a éte effectuer avec success');

    expect($company->fresh())
        ->name->toBe('CASA MAINTENANCE SARL')
        ->prefix_invoice->toBe('FCT');
});

it('shows the validation error next to the field', function () {
    $company = makeCompany();

    $this->followingRedirects()
        ->from($company->edit_url)
        ->post(route('commercial:companies.update', $company->uuid), ['name' => 'CASA MAINTENANCE', 'addresse' => 'Casablanca', 'ice' => ''])
        ->assertOk()
        ->assertSee('id="ice-error"', false);
});

it('keeps the delete action as before: success message, company kept (BL-73)', function () {
    $company = makeCompany();

    $this->followingRedirects()
        ->from(route('commercial:companies.index'))
        ->delete(route('commercial:companies.delete', ['companyId' => $company->uuid]))
        ->assertOk()
        ->assertSee('La Société  a éte supprimer  avec success');

    expect(Company::whereKey($company->id)->exists())->toBeTrue();
});
