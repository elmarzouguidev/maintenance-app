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
        return [

            'contact' => $this->faker->name('male'),
            'addresse' => $this->faker->address,
            'telephone' => $this->faker->unique()->phoneNumber,
            'email' => $this->faker->unique()->safeEmail(),
            'entreprise' => $this->faker->company,
            'ice' => $this->faker->unique()->regexify('[0-9]{10}'),
            'rc' => $this->faker->unique()->regexify('[0-9]{5}'),
        ];
    }
}
