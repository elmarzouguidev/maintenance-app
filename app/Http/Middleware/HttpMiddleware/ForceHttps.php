<?php

namespace App\Http\Middleware\HttpMiddleware;

use Closure;

use Illuminate\Http\Request;

class ForceHttps
{


    public function handle($request, Closure $next)
    {
        if (app()->environment('production') && !$request->secure()) {
            return redirect()->secure($request->getRequestUri());
        }

        return $next($request);
    }
}
