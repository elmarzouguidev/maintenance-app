<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{


    private $repositories = [
        [
            'abstract' => "App\Repositories\Admin\AdminInterface",
            'concrete' => "App\Repositories\Admin\AdminRepository"
        ]
    ];
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        foreach ($this->repositories as $repo) {
            $this->app->bind(
                $repo['abstract'],
                $repo['concrete'],
            );
        }
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
