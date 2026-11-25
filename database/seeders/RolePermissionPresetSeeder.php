<?php

namespace Database\Seeders;

use App\Support\RolePresets;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

/**
 * Applies App\Support\RolePresets: creates the missing permissions and roles, then adds each
 * preset's permissions to its role. Additive: it never removes a permission, so it can run on
 * every deploy and never undoes what an administrator granted.
 */
class RolePermissionPresetSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        foreach (RolePresets::PERMISSIONS as $name => $publicName) {
            Permission::query()->firstOrCreate(['name' => $name, 'guard_name' => 'admin'], ['public_name' => $publicName]);
        }

        foreach (RolePresets::ROLES as $roleName => $permissions) {
            Role::findOrCreate($roleName, 'admin')->givePermissionTo($permissions);
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
