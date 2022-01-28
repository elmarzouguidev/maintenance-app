<?php

namespace App\Filters\Models;

use Closure;

class Active
{
    public function handle($request, Closure $next)
    {
        if (!request()->has('active')) {
            
            return $next($request);
        }

        return $next($request)->where('active', request()->input('active'));
    }
}
