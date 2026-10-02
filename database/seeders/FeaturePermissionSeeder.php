<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class FeaturePermissionSeeder extends Seeder
{
    /**
     * Add permissions for sidebar features that were not covered by the original
     * PermissionSeeder. This seeder deliberately does not change role or user
     * assignments.
     */
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissions = [
            ['name' => 'companies.browse', 'public_name' => 'Voir la liste des sociétés'],
            ['name' => 'companies.create', 'public_name' => 'Créer une société'],
            ['name' => 'companies.edit', 'public_name' => 'Modifier une société'],
            ['name' => 'companies.delete', 'public_name' => 'Supprimer une société'],

            ['name' => 'ticket.delivery.browse', 'public_name' => 'Voir les tickets à livrer'],
            ['name' => 'ticket.delivery.confirm', 'public_name' => 'Confirmer une livraison'],
            ['name' => 'ticket.delivery.admin_confirm', 'public_name' => 'Confirmer une livraison en tant qu’administrateur'],
            ['name' => 'ticket.reassign', 'public_name' => 'Réaffecter un ticket'],

            ['name' => 'diagnostic.browse', 'public_name' => 'Voir les diagnostics'],
            ['name' => 'diagnostic.edit', 'public_name' => 'Modifier un diagnostic'],
            ['name' => 'diagnostic.send_report', 'public_name' => 'Envoyer un rapport de diagnostic'],
            ['name' => 'diagnostic.confirm', 'public_name' => 'Confirmer un diagnostic'],

            ['name' => 'reparations.browse', 'public_name' => 'Voir les réparations'],
            ['name' => 'reparations.edit', 'public_name' => 'Modifier une réparation'],
            ['name' => 'reparations.complete', 'public_name' => 'Terminer une réparation'],

            ['name' => 'categories.browse', 'public_name' => 'Voir les catégories'],
            ['name' => 'categories.create', 'public_name' => 'Créer une catégorie'],
            ['name' => 'categories.delete', 'public_name' => 'Supprimer une catégorie'],

            ['name' => 'client.import', 'public_name' => 'Importer des clients'],
            ['name' => 'report.clients.browse', 'public_name' => 'Voir le rapport clients'],

            ['name' => 'warranty.browse', 'public_name' => 'Voir les garanties'],
            ['name' => 'warranty.create', 'public_name' => 'Créer une garantie'],

            ['name' => 'roles_permissions.browse', 'public_name' => 'Voir les rôles et permissions'],
            ['name' => 'roles_permissions.roles.create', 'public_name' => 'Créer un rôle'],
            ['name' => 'roles_permissions.roles.delete', 'public_name' => 'Supprimer un rôle'],
            ['name' => 'roles_permissions.permissions.create', 'public_name' => 'Créer une permission'],
            ['name' => 'roles_permissions.permissions.delete', 'public_name' => 'Supprimer une permission'],

            ['name' => 'imports.csv.browse', 'public_name' => 'Accéder à l’import CSV'],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission['name'], 'guard_name' => 'admin'],
                ['public_name' => $permission['public_name']],
            );
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
