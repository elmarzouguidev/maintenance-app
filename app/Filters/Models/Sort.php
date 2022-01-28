<?php


namespace App\Filters\Models;

use Closure;

class Sort
{
    public function handle($request, Closure $next)
    {
        if (!request()->has('sort')) {
            return $next($request);
        }

        return $next($request)->orderBy('name', $request->input('sort'));
    }
}
