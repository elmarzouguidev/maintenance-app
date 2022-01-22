<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    protected $permissions = [

        ['name' => 'ticket.browse', 'guard_name' => 'admin'],
        ['name' => 'ticket.read', 'guard_name' => 'admin'],
        ['name' => 'ticket.create', 'guard_name' => 'admin'],
        ['name' => 'ticket.edit', 'guard_name' => 'admin'],
        ['name' => 'ticket.delete', 'guard_name' => 'admin'],

        ['name' => 'client.browse', 'guard_name' => 'admin'],
        ['name' => 'client.read', 'guard_name' => 'admin'],
        ['name' => 'client.create', 'guard_name' => 'admin'],
        ['name' => 'client.edit', 'guard_name' => 'admin'],
        ['name' => 'client.delete', 'guard_name' => 'admin'],

        ['name' => 'admin.browse', 'guard_name' => 'admin'],
        ['name' => 'admin.read', 'guard_name' => 'admin'],
        ['name' => 'admin.create', 'guard_name' => 'admin'],
        ['name' => 'admin.edit', 'guard_name' => 'admin'],
        ['name' => 'admin.delete', 'guard_name' => 'admin'],

        ['name' => 'technicien.browse', 'guard_name' => 'admin'],
        ['name' => 'technicien.read', 'guard_name' => 'admin'],
        ['name' => 'technicien.create', 'guard_name' => 'admin'],
        ['name' => 'technicien.edit', 'guard_name' => 'admin'],
        ['name' => 'technicien.delete', 'guard_name' => 'admin'],

        ['name' => 'reception.browse', 'guard_name' => 'admin'],
        ['name' => 'reception.read', 'guard_name' => 'admin'],
        ['name' => 'reception.create', 'guard_name' => 'admin'],
        ['name' => 'reception.edit', 'guard_name' => 'admin'],
        ['name' => 'reception.delete', 'guard_name' => 'admin'],

        /**** Default Technicien Permissions */

        /*['name' => 'ticket.browse', 'guard_name' => 'technicien'],
        ['name' => 'ticket.read', 'guard_name' => 'technicien'],
        ['name' => 'ticket.create', 'guard_name' => 'technicien'],
        ['name' => 'ticket.edit', 'guard_name' => 'technicien'],
        ['name' => 'ticket.delete', 'guard_name' => 'technicien'],

        ['name' => 'client.browse', 'guard_name' => 'admin'],
        ['name' => 'reception.browse', 'guard_name' => 'admin'],*/

    ];
    

    public function run()
    {
        foreach ($this->permissions as $permission) {

            Permission::create($permission);
        }

        $permissionsItems = Permission::all();

        $adminRole = Role::whereName('SuperAdmin')->first();

        $adminRole->syncPermissions($permissionsItems);
    }
}
