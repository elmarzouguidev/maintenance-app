<?php

use Illuminate\Routing\Route as RouteDefinition;
use Illuminate\Support\Facades\Route;

/*
 * Routes that pointed to controller methods which do not exist answered with a 500
 * (BL-82, BL-84, BL-89, BL-90): every controller route must reach a real method.
 */

it('points every route to an existing controller method', function () {
    $broken = collect(Route::getRoutes()->getRoutes())
        ->map(fn (RouteDefinition $route): string => $route->getActionName())
        ->filter(fn (string $action): bool => str_contains($action, '@'))
        ->reject(function (string $action): bool {
            [$class, $method] = explode('@', $action);

            return class_exists($class) && method_exists($class, $method);
        })
        ->values()
        ->all();

    expect($broken)->toBe([]);
});

it('no longer accepts the anonymous CSV upload', function () {
    $this->post('/upload')->assertNotFound();
});
