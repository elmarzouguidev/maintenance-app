<?php

use App\Models\Finance\Company;
use App\Settings\GeneralSettings;
use Illuminate\Support\Str;

if (! function_exists('defaultCompany')) {
    function defaultCompany()
    {
        return Company::select(['name'])->where('is_default', true)->first() ?? null;
    }
}

if (! function_exists('getCompany')) {
    function getCompany()
    {
        return app(GeneralSettings::class);
    }
}

if (! function_exists('getDomainName')) {
    function getDomainName()
    {
        return request()->getSchemeAndHttpHost().'/';
    }
}

/*****Auth guard helpers *****/

if (! function_exists('isAdmin')) {
    function isAdmin()
    {
        return auth()->check() && auth()->user()->hasAnyRole('SuperAdmin', 'Admin') ? true : false;
    }
}

/******************* */

if (! function_exists('getUuid')) {
    function getUuid()
    {
        return Str::uuid()->toString();
    }
}
