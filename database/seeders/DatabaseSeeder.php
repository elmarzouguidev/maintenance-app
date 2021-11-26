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
       // \App\Models\Authentification\Admin::factory(10)->create();
        \App\Models\Authentification\Admin::factory(50)->superAdmin()->create();

         \App\Models\Authentification\Technicien::factory(30)->create();
    }
}
