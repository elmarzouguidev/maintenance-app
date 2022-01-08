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
    //\App\Models\User::factory(10)->create();
    \App\Models\Authentification\Admin::factory()->create();
    //\App\Models\Authentification\Admin::factory(4)->superAdmin()->create();

    \App\Models\Authentification\Technicien::factory(5)->create();
    \App\Models\Authentification\Reception::factory(5)->create();

    \App\Models\Category::factory(10)->create();
    \App\Models\Client::factory(10)->create();
  }
}
