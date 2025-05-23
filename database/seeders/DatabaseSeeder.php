<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(CategorySeeder::class);
        $this->call(CompanySeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(PermissionSeeder::class);
        $this->call(AdminSeeder::class);
        //$this->call(TechnicienSeeder::class);
        //$this->call(ReceptionSeeder::class);
        $this->call(StatusSeeder::class);
        $this->call(AddSuperTechnicienRoleSeeder::class);

        $this->call(AddNewRolesSeeder::class);

        \App\Models\Finance\Provider::factory(10)->create();
        \App\Models\Client::factory(20)->create();

        // \App\Models\Ticket::factory(25)->create();

        //$this->call(MailTemplateSeeder::class);
    }
}
