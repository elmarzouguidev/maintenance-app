[<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   *
   * @return void
   */
  public function run()
  {

    $this->call(CompanySeeder::class);
    $this->call(RoleSeeder::class);
    $this->call(PermissionSeeder::class);
    $this->call(AdminSeeder::class);
    $this->call(TechnicienSeeder::class);
    $this->call(ReceptionSeeder::class);
    
    \App\Models\Ticket::factory(30)->create();
  }
}
]