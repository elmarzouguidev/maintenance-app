<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    
    /**
     * Run the database seeds.
     *
     * @return void
     */

    protected $roles = [

        ['name' => 'SuperAdmin', 'guard_name' => 'admin'],
        ['name' => 'Admin', 'guard_name' => 'admin'],
        ['name' => 'Technicien', 'guard_name' => 'technicien'],
        ['name' => 'Reception', 'guard_name' => 'reception'],
       
    ];

    public function run()
    {
        foreach ($this->roles as $role) {
            Role::create($role);
        }
    }
}
