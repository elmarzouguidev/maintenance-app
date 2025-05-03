<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class AddNewRolesSeeder extends Seeder
{
    protected array $roles = [

        ['name' => 'ASSISTANTE DIRECTEUR', 'guard_name' => 'admin'],
        ['name' => 'SECRETAIRE', 'guard_name' => 'admin'],
        ['name' => 'ADMINISTRATEUR', 'guard_name' => 'admin'],
    ];

    public function run()
    {
        foreach ($this->roles as $role) {
            Role::create($role);
        }
    }
}
