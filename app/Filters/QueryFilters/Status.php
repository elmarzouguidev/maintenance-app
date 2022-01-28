<?php

namespace App\Filters\QueryFilters;

class Status
{ 
    public function handle($query, $next)
    {
        if (request()->has('status')) {
            $query->where('status', request('status'));
        }

        $next($builder);
    }
}
