<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Request;

class RequestServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Request::macro('filterHoneypot', function () {
            
            return collect(request()->except('_token', 'valid_from'))->reject(function ($item, $key) {
                if (strpos($key, config('honeypot.name_field_name')) !== false) {
                    return true;
                } else {
                    return false;
                }
            })->toArray();
        });
    }
}
