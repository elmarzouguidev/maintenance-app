<?php

namespace App\Providers;

use App\Http\Controllers\Authentification\AuthController;
use Illuminate\Support\ServiceProvider;

class LoginGuardServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->when(AuthController::class)

            ->needs('$appGuard')
            ->give($this->app->make('config')->get('auth.authUser'));
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
