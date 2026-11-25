<?php

use App\Support\RolePresets;
use Database\Seeders\RolePermissionPresetSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

/*
 * Roles are permission presets (App\Support\RolePresets), applied by the
 * apply_role_permission_presets migration through RolePermissionPresetSeeder.
 */

uses(RefreshDatabase::class);

it('gives every role its preset permissions after the migrations', function () {
    foreach (RolePresets::ROLES as $role => $permissions) {
        expect(Role::findByName($role, 'admin')->permissions->pluck('name')->all())->toEqualCanonicalizing($permissions);
    }
});

it('never removes a permission granted by hand when it runs again', function () {
    $extra = Permission::findOrCreate('invoices.browse', 'admin');
    Role::findByName('Technicien', 'admin')->givePermissionTo($extra);

    (new RolePermissionPresetSeeder)->run();

    expect(Role::findByName('Technicien', 'admin')->hasPermissionTo('invoices.browse'))->toBeTrue()
        ->and(Role::findByName('Technicien', 'admin')->hasPermissionTo('ticket.work'))->toBeTrue();
});
