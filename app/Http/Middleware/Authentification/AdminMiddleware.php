<?php

namespace App\Http\Middleware\Authentification;

use Closure;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;

class AdminMiddleware
{

    /**
     * @param Request $request
     * @param Closure $next
     * @return Application|RedirectResponse|Redirector|mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!\Auth::guard('admin')::user()->super_admin) {
            return redirect('/');
        }

        return $next($request);
    }
}
