<?php

namespace Database\Factories\Authentification;

use App\Models\Authentification\Admin;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    protected $model = Admin::class;

    public function definition(): array
    {
        return [
            'nom' => 'Elmarzougui',
            'prenom' => 'Abdelghafour',
            'telephone' => '0677512753',
            'email' => 'abdelgha4or@gmail.com',
            //'email_verified_at' => now(),
            'password' => Hash::make('123456789@'),
            'remember_token' => Str::random(10),
        ];
    }


    /**
     * @return AdminFactory
     */
    public function unverified(): AdminFactory
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }

    public function verified(): AdminFactory
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => now(),
            ];
        });
    }

    public function superAdmin(): AdminFactory
    {
        return $this->state(function (array $attributes) {
            return [
                'super_admin' => true,
            ];
        });
    }
}
