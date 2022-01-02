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

            $this->devlopperRoutes();

            $this->adminRoutes();
            $this->technicienRoutes();
            $this->receptionRoutes();
            $this->globalRoutes();

            $this->rolesAndPermissionsRoutes();
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

    private function technicienRoutes()
    {
        /*** technicien ***/
        Route::middleware(['web'])
            ->prefix('app-tech')
            ->name('technicien:auth:')
            ->namespace($this->namespace)
            ->group(base_path('routes/technicien/login.php'));

        Route::middleware(['web', 'auth:technicien'])
            ->prefix('app-tech')
            ->name('technicien:')
            ->namespace($this->namespace)
            ->group(base_path('routes/technicien/routes.php'));
    }

    private function adminRoutes()
    {
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
    }

    private function receptionRoutes()
    {

        /*** reception ***/
        Route::middleware(['web'])
            ->prefix('app-reception')
            ->name('reception:auth:')
            ->namespace($this->namespace)
            ->group(base_path('routes/reception/login.php'));

        Route::middleware(['web', 'auth:reception'])
            ->prefix('app-reception')
            ->name('reception:')
            ->namespace($this->namespace)
            ->group(base_path('routes/reception/routes.php'));
    }

    private function rolesAndPermissionsRoutes()
    {
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
    }

    private function globalRoutes()
    {
        Route::middleware(['web', 'auth:admin,reception'])
            ->prefix('app-global')
            ->name('global:')
            ->namespace($this->namespace)
            ->group(base_path('routes/globalRoutes/routes.php'));
    }

    private function devlopperRoutes()
    {

        Route::middleware('web')
            ->prefix('dev')
            ->namespace($this->namespace)
            ->group(base_path('routes/developper/routes.php'));
    }
}
