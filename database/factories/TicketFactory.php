<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Ticket;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TicketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $article = $this->faker->sentence;
        $etat = ['non-traite', 'reparable', 'non-reparable'];
        $status = ['new', 'ouvert', 'envoyer', 'annuler', 'attent-devis', 'confirme', 'encours', 'finished'];
        return [
            'article' => $article,
            'slug' => Str::slug($article),
            'description' => $this->faker->words(4, true),
            'active' => $this->faker->boolean(),
            'published' => $this->faker->boolean(),
            'etat' => $etat[array_rand($etat)],
            'status' =>  $status[array_rand($status)],
            'client_id' => Client::factory(),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Ticket $item) {
            $url = 'https://source.unsplash.com/random/1200x800';
            $item
                ->addMediaFromUrl($url)
                ->toMediaCollection('tickets-images');
        });
    }
}
