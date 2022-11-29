<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

class AddLogoToGeneralSettings extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('general.logo', 'logo.png');

    }
}