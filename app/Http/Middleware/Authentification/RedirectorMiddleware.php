<?php

namespace App\Http\Middleware\Authentification;

use Closure;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;

class RedirectorMiddleware
{
    /**
     * @return Application|RedirectResponse|Redirector|mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->is('app', 'app/')) {
            return redirect()->route('admin:home');
        }

        return $next($request);
    }
}
