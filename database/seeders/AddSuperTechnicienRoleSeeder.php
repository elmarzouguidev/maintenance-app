<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class AddSuperTechnicienRoleSeeder extends Seeder
{
    protected array $roles = [

        ['name' => 'SuperTechnicien', 'guard_name' => 'admin'],

    ];

    public function run(): void
    {
        foreach ($this->roles as $role) {
            Role::create($role);
        }
    }
}
