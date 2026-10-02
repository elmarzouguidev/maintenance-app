<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Finance\Provider;
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
        $this->call(FeaturePermissionSeeder::class);
        $this->call(AdminSeeder::class);
        $this->call(TechnicienSeeder::class);
        // $this->call(ReceptionSeeder::class);
        $this->call(StatusSeeder::class);
        $this->call(AddSuperTechnicienRoleSeeder::class);

        $this->call(AddNewRolesSeeder::class);

        Provider::factory(10)->create();
        Client::factory(20)->create();

        // Seed tickets with realistic data
        $this->call(TicketSeeder::class);

        // \App\Models\Ticket::factory(25)->create();

        // $this->call(MailTemplateSeeder::class);
    }
}
