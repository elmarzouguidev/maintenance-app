<?php

namespace Database\Factories\Authentification;

use App\Models\Authentification\Technicien;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TechnicienFactory extends Factory
{

    protected $model = Technicien::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'nom' => 'ELouahabi',
            'prenom' => 'Ahmed',
            'telephone' => "0677512756",
            'email' => "ahmed@gmail.com",
            'email_verified_at' => now(),
            'password' => Hash::make('123456789@'),
            'remember_token' => Str::random(10),
        ];
    }


    public function unverified(): TechnicienFactory
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
