<?php

namespace App\Providers;

use App\View\Composers\AdminLayoutComposer;
use App\View\Composers\EstimateComposer;
use App\View\Composers\TicketComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewComposerServiceProvider extends ServiceProvider
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
        View::composer([
            'theme.layouts.app',
            'theme.layouts.tailwind',
            'theme.pages.Commercial.Invoice.index',
        ], TicketComposer::class);

        View::composer('layouts.admin', AdminLayoutComposer::class);

        // View::composer(['theme.layouts._parts._leftSidebar', 'theme.layouts._parts._leftSidebar_commercial'], EstimateComposer::class);
    }
}
