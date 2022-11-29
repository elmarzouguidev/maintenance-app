<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class GeneralSettings extends Settings
{

    public string $logo;
    public bool $site_active;
    public string $app_api;
    public string $app_api_token;


    public static function group(): string
    {
        return 'general';
    }
}
