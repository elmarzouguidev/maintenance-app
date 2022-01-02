<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ClientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $nom = $this->faker->firstName('male');
        return [
            'nom' => $nom,
            'prenom' => $this->faker->lastName,
            'slug' => Str::slug($nom),
            'address' => $this->faker->address,
            'gsm' => $this->faker->phoneNumber,
            'telephone' => $this->faker->phoneNumber,
            'email' => $this->faker->unique()->safeEmail(),
            'ste_name' => $this->faker->company,
            'ste_ice' => $this->faker->unique()->regexify('[0-9]{10}'),
            'ste_rc' => $this->faker->unique()->regexify('[0-9]{5}'),
            //'ste_logo'
        ];
    }
}
