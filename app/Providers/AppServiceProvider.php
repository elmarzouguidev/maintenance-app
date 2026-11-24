<?php

namespace App\Providers;

use App\Support\UiKit\Format;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Carbon::setLocale(config('app.locale'));

        // UI kit display formats (x-admin.amount, x-admin.date): French defaults, with the app's currency label.
        Format::$currency = 'DH';

        Schema::defaultStringLength(125); // On MySQL 8.0 use defaultStringLength(125)
        //  Schema::defaultStringLength(191);

        // Schema::enableForeignKeyConstraints();

        // Schema::disableForeignKeyConstraints();
    }
}
