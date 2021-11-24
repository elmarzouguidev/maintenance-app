<?php

namespace Database\Factories\Authentification;

use App\Models\Authentification\Reception;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ReceptionFactory extends Factory
{

    protected $model = Reception::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'nom' => $this->faker->name(),
            'prenom' => $this->faker->name(),
            'telephone' => $this->faker->phoneNumber,
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ];
    }
}