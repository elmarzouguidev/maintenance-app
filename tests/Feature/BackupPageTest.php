<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;

/*
 * K2: the backup manager (theme/pages/Excel) on the UI kit layout.
 */

uses(RefreshDatabase::class);

it('shows the backup manager with its actions to a SuperAdmin', function () {
    useFakeViteServer();
    Storage::fake('backup');
    $admin = User::factory()->create();
    $admin->assignRole(Role::findOrCreate('SuperAdmin', 'admin'));

    $this->actingAs($admin)
        ->get(route('admin:backup:manager.index'))
        ->assertOk()
        ->assertSee('<title>Sauvegardes - CASAMAINTENANCE</title>', false)
        ->assertSee('action="'.route('admin:backup:make').'"', false)
        ->assertSee('Aucune sauvegarde');
});
