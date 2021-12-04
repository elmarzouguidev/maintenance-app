<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/';

    /**
     * The controller namespace for the application.
     *
     * When present, controller route declarations will automatically be prefixed with this namespace.
     *
     * @var string|null
     */
    // protected $namespace = 'App\\Http\\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::prefix('api')
                ->middleware('api')
                ->namespace($this->namespace)
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/web.php'));

            Route::middleware('web')
                ->prefix('dev')
                ->namespace($this->namespace)
                ->group(base_path('routes/developper/routes.php'));

            /*** admin ***/
            Route::middleware(['web'])
                ->prefix('app')
                ->name('admin:auth:')
                ->namespace($this->namespace)
                ->group(base_path('routes/admin/login.php'));

            Route::middleware(['web', 'auth:admin'])
                ->prefix('app')
                ->name('admin:')
                ->namespace($this->namespace)
                ->group(base_path('routes/admin/routes.php'));

            /*** technicien ***/
            Route::middleware(['web'])
                ->prefix('technicien')
                ->name('technicien:auth:')
                ->namespace($this->namespace)
                ->group(base_path('routes/technicien/login.php'));

            Route::middleware(['web', 'auth:technicien'])
                ->prefix('technicien')
                ->name('technicien:')
                ->namespace($this->namespace)
                ->group(base_path('routes/technicien/routes.php'));


            /****Roles Routes **/
            Route::middleware(['web'])
                ->prefix('roles')
                ->name('role:')
                ->namespace($this->namespace)
                ->group(base_path('routes/roles/routes.php'));

            /****Permissions Routes **/
            Route::middleware(['web'])
                ->prefix('permissions')
                ->name('permission:')
                ->namespace($this->namespace)
                ->group(base_path('routes/permissions/routes.php'));
        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
        });
    }
}
