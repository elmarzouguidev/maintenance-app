<?php

use Database\Seeders\RolePermissionPresetSeeder;
use Illuminate\Database\Migrations\Migration;

/**
 * Roles become permission presets (App\Support\RolePresets): give each role the permissions the
 * code used to grant by its name, before the checks switch to permissions. Additive only.
 */
return new class extends Migration
{
    public function up(): void
    {
        (new RolePermissionPresetSeeder)->run();
    }

    public function down(): void
    {
        // Additive data: the permissions stay, administrators may have granted them since.
    }
};
