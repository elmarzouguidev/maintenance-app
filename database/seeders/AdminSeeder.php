<?php

namespace Database\Seeders;

use App\Models\Authentification\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $user =  [
            'nom' => 'Elmarzougui',
            'prenom' => 'Abdelghafour',
            'telephone' => '0677512753',
            'email' => 'abdelgha4or@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('123456789@'),
            'remember_token' => Str::random(10),
            'super_admin' => true
        ];

        $admin = Admin::whereEmail('abdelgha4or@gmail.com')->first();

        if (!$admin) {

            $newAdmin =  Admin::create($user);
            $newAdmin->assignRole('SuperAdmin');

        } else {

            $admin->assignRole('SuperAdmin');
        }
    }
}
