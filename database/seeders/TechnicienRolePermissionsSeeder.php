<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class TechnicienRolePermissionsSeeder extends Seeder
{
    private const PERMISSIONS = [
        'ticket.browse' => 'Voir la liste des tickets',
        'ticket.read' => 'Voir un ticket',
        'ticket.work' => 'Recevoir des tickets à diagnostiquer ou réparer',
        'diagnostic.assigned.browse' => 'Voir les diagnostics affectés à soi',
        'diagnostic.edit' => 'Modifier un diagnostic',
        'diagnostic.send_report' => 'Envoyer un rapport de diagnostic',
        'reparations.assigned.browse' => 'Voir les réparations affectées à soi',
        'reparations.edit' => 'Modifier une réparation',
        'reparations.complete' => 'Terminer une réparation',
    ];

    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $technicienRole = Role::findOrCreate('Technicien', 'admin');
        $permissions = collect(self::PERMISSIONS)
            ->map(fn (string $publicName, string $name) => Permission::firstOrCreate(
                ['name' => $name, 'guard_name' => 'admin'],
                ['public_name' => $publicName],
            ));

        // Add the baseline without removing any existing role permissions.
        $technicienRole->givePermissionTo($permissions);

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
