<?php

namespace App\Macros;

class RequestMixin
{


    public function withoutHoneypot()
    {
        return function () {

            return collect(request()->except('_token', 'valid_from'))->reject(function ($item, $key) {
                if (strpos($key, config('honeypot.name_field_name')) !== false) {
                    return true;
                } else {
                    return false;
                }
            })->toArray();
        };
    }
}
