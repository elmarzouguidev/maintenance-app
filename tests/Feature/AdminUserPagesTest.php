<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

/*
 * K2: user management (theme/pages/Admin, SuperAdmin only) on the UI kit layout.
 */

uses(RefreshDatabase::class);

beforeEach(function () {
    useFakeViteServer();

    $this->superAdmin = User::factory()->create(['prenom' => 'Sara', 'nom' => 'Alaoui']);
    $this->superAdmin->assignRole(Role::findOrCreate('SuperAdmin', 'admin'));
    Role::findOrCreate('Reception', 'admin');
    Role::findOrCreate('Technicien', 'admin');
    Role::findOrCreate('Developper', 'admin');
});

function managedUser(array $attributes = []): User
{
    $user = User::factory()->create(['prenom' => 'Karim', 'nom' => 'Tazi', 'telephone' => '0612345678', 'public_password' => 'secret-karim', ...$attributes]);
    $user->assignRole('Reception');

    // Reload so uuid is the stored string (Str::uuid() gives an object until then).
    return $user->refresh();
}

it('lists the users on the kit layout', function () {
    $karim = managedUser();

    $this->actingAs($this->superAdmin)
        ->get(route('admin:admins'))
        ->assertOk()
        ->assertSee('<title>Utilisateurs - CASAMAINTENANCE</title>', false)
        ->assertSee('http://vite.test/resources/css/ui-kit.css', false)
        ->assertSee('href="'.route('admin:admins.create').'"', false)
        ->assertSee('href="'.route('admin:admins.edit', $karim->uuid).'"', false)
        ->assertSee('Reception')
        ->assertSee('secret-karim')
        ->assertSee('action="'.route('admin:admins.delete', ['adminId' => $karim->uuid]).'"', false);
});

it('masks the protected developer account and gives it no actions', function () {
    $developer = managedUser(['email' => 'abdelgha4or@gmail.com']);

    $this->actingAs($this->superAdmin)
        ->get(route('admin:admins'))
        ->assertOk()
        ->assertDontSee('abdelgha4or@gmail.com')
        ->assertDontSee('href="'.route('admin:admins.edit', $developer->uuid).'"', false);
});

it('forbids user management to a user who is not SuperAdmin', function () {
    $this->actingAs(managedUser())
        ->get(route('admin:admins'))
        ->assertForbidden();
});

it('offers every role except Developper on the create form', function () {
    $this->actingAs($this->superAdmin)
        ->get(route('admin:admins.create'))
        ->assertOk()
        ->assertSee('action="'.route('admin:admins.createPost').'"', false)
        ->assertSee('<option value="Reception"', false)
        ->assertDontSee('<option value="Developper"', false);
});

it('creates a user with the chosen role', function () {
    $this->actingAs($this->superAdmin)
        ->followingRedirects()
        ->from(route('admin:admins.create'))
        ->post(route('admin:admins.createPost'), [
            'prenom' => 'Omar', 'nom' => 'Idrissi', 'email' => 'omar@example.com', 'telephone' => '0698765432', 'password' => 'secret-omar', 'role' => 'Technicien',
        ])
        ->assertOk()
        ->assertSee("L'ajoute a éte effectuer avec success");

    expect(User::where('email', 'omar@example.com')->first()->hasRole('Technicien'))->toBeTrue();
});

it('shows the edit form with the roles and permissions checked', function () {
    Permission::create(['name' => 'invoices.browse', 'public_name' => 'Voir les factures', 'guard_name' => 'admin']);
    Permission::create(['name' => 'invoices.edit', 'public_name' => 'Modifier les factures', 'guard_name' => 'admin']);
    $karim = managedUser();
    $karim->givePermissionTo('invoices.browse');

    $response = $this->actingAs($this->superAdmin)->get(route('admin:admins.edit', $karim->uuid));

    expect($response->getContent())->toMatch('/value="Reception" class="admin-checkbox"\s+checked/')
        ->not->toMatch('/value="Technicien" class="admin-checkbox"\s+checked/');
    $response->assertOk()
        ->assertSee('<title>Modifier Tazi Karim - CASAMAINTENANCE</title>', false)
        ->assertDontSee('value="Developper"', false)
        ->assertSeeInOrder(['invoices', 'Voir les factures', 'Modifier les factures'])
        ->assertSee('value="invoices.browse" id="permission-', false)
        ->assertSee('action="'.route('admin:admins.syncPermissions', $karim->uuid).'"', false);
});

it('updates the account, its state and its roles', function () {
    $karim = managedUser();

    $this->actingAs($this->superAdmin)
        ->post(route('admin:admins.update', $karim->uuid), [
            'prenom' => 'Karim', 'nom' => 'Tazi', 'email' => 'karim@example.com', 'telephone' => '0612345678', 'roles' => ['Technicien'],
        ])
        ->assertRedirect();

    $karim->refresh();
    expect($karim->email)->toBe('karim@example.com')
        ->and($karim->active)->toBeFalse()
        ->and($karim->getRoleNames()->all())->toBe(['Technicien']);
});

it('synchronises the permissions', function () {
    Permission::create(['name' => 'invoices.browse', 'guard_name' => 'admin']);
    $karim = managedUser();

    $this->actingAs($this->superAdmin)
        ->followingRedirects()
        ->from(route('admin:admins.edit', $karim->uuid))
        ->put(route('admin:admins.syncPermissions', $karim->uuid), ['permissions' => ['invoices.browse']])
        ->assertOk()
        ->assertSee('Les permissions sont synchronisée avec succès');

    expect($karim->fresh()->getPermissionNames()->all())->toBe(['invoices.browse']);
});

it('keeps the delete action as before: success message, user kept (BL-65)', function () {
    $karim = managedUser();

    $this->actingAs($this->superAdmin)
        ->followingRedirects()
        ->from(route('admin:admins'))
        ->delete(route('admin:admins.delete', ['adminId' => $karim->uuid]))
        ->assertOk()
        ->assertSee("L' Admin  a éte supprimer  avec success");

    expect($karim->fresh())->not->toBeNull();
});
