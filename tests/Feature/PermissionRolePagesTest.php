<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

/*
 * K1: the roles and permissions pages (theme/pages/PermissionRole) run on the UI kit layout
 * (first on Tailwind in B3).
 */

uses(RefreshDatabase::class);

beforeEach(function () {
    useFakeViteServer();

    $user = User::factory()->create();
    $user->assignRole(Role::create(['name' => 'SuperAdmin', 'guard_name' => $user->guard_name]));

    $this->actingAs($user);
});

it('renders the roles page on the kit layout', function () {
    Role::findOrCreate('Reception', 'admin');

    $this->get(route('admin:permissions-roles.index'))
        ->assertOk()
        ->assertSee('<title>Rôles - CASAMAINTENANCE</title>', false)
        ->assertSee('http://vite.test/resources/css/ui-kit.css', false)
        ->assertDontSee('http://vite.test/resources/css/app.css', false)
        ->assertDontSee('legacy', false)
        ->assertSee('<h1 class="admin-page-title">Rôles</h1>', false)
        ->assertSee('Reception')
        ->assertSee('Ajouter un rôle')
        ->assertSee('action="'.route('admin:permissions-roles.add').'"', false);
});

it('renders the permissions page on the kit layout', function () {
    Permission::create(['name' => 'invoices.browse', 'guard_name' => 'admin']);

    $this->get(route('admin:permissions-roles.permissions'))
        ->assertOk()
        ->assertSee('<title>Permissions - CASAMAINTENANCE</title>', false)
        ->assertSee('http://vite.test/resources/css/ui-kit.css', false)
        ->assertDontSee('legacy', false)
        ->assertSee('invoices.browse')
        ->assertSee('Ajouter une permission')
        ->assertSee('action="'.route('admin:permissions-roles.add.permissions').'"', false);
});

it('adds a role and shows the success message', function () {
    $this->followingRedirects()
        ->from(route('admin:permissions-roles.index'))
        ->post(route('admin:permissions-roles.add'), ['name' => 'Comptable'])
        ->assertOk()
        ->assertSee('Le role  a éte ajouter  avec success')
        ->assertSee('Comptable');

    expect(Role::where('name', 'Comptable')->where('guard_name', 'admin')->exists())->toBeTrue();
});

it('shows the validation error next to the role name', function () {
    $roles = Role::count();

    $this->followingRedirects()
        ->from(route('admin:permissions-roles.index'))
        ->post(route('admin:permissions-roles.add'), ['name' => ''])
        ->assertOk()
        ->assertSee('aria-invalid="true"', false);

    expect(Role::count())->toBe($roles);
});

it('paginates the roles ten per page with the kit pager', function () {
    foreach (range(1, 11) as $number) {
        Role::create(['name' => "Role {$number}", 'guard_name' => 'admin']);
    }

    $this->get(route('admin:permissions-roles.index'))
        ->assertOk()
        ->assertSee('aria-current="page"', false)
        ->assertSee(route('admin:permissions-roles.index').'?page=2', false);
});

it('shows the empty state when there is no permission', function () {
    Permission::query()->delete();

    $this->get(route('admin:permissions-roles.permissions'))
        ->assertOk()
        ->assertSee('Aucune permission')
        ->assertDontSee('admin-table', false);
});
