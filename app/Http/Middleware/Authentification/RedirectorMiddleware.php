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
     * @param Request $request
     * @param Closure $next
     * @return Application|RedirectResponse|Redirector|mixed
     */
    public function handle(Request $request, Closure $next)
    {

       /* if (!\Auth::guard('admin')::user()->super_admin) {
            return redirect('/');
        }*/

        if ($request->is('app', 'app/')) {

            return redirect()->route('admin:home');
        }

        if ($request->is('app-tech', 'app-tech/')) {

            return redirect()->route('technicien:home');
        }

        return $next($request);
    }
}
