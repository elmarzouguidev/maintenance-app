<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    protected $permissions = [

        ['name' => 'ticket.browse', 'guard_name' => 'admin', 'public_name' => 'Voir la liste des tickets'],
        ['name' => 'ticket.read', 'guard_name' => 'admin', 'public_name' => 'Voir un ticket'],
        ['name' => 'ticket.create', 'guard_name' => 'admin', 'public_name' => 'Créer un ticket'],
        ['name' => 'ticket.edit', 'guard_name' => 'admin', 'public_name' => 'Modifier un ticket'],
        ['name' => 'ticket.delete', 'guard_name' => 'admin', 'public_name' => 'Supprimer un ticket'],

        ['name' => 'client.browse', 'guard_name' => 'admin', 'public_name' => 'Voir la liste des clients'],
        ['name' => 'client.read', 'guard_name' => 'admin', 'public_name' => 'Voir un client'],
        ['name' => 'client.create', 'guard_name' => 'admin', 'public_name' => 'Créer un client'],
        ['name' => 'client.edit', 'guard_name' => 'admin', 'public_name' => 'Modifier un client'],
        ['name' => 'client.delete', 'guard_name' => 'admin', 'public_name' => 'Supprimer un client'],

        ['name' => 'admin.browse', 'guard_name' => 'admin', 'public_name' => 'Voir la liste des administrateurs'],
        ['name' => 'admin.read', 'guard_name' => 'admin', 'public_name' => 'Voir un administrateur'],
        ['name' => 'admin.create', 'guard_name' => 'admin', 'public_name' => 'Créer un administrateur'],
        ['name' => 'admin.edit', 'guard_name' => 'admin', 'public_name' => 'Modifier un administrateur'],
        ['name' => 'admin.delete', 'guard_name' => 'admin', 'public_name' => 'Supprimer un administrateur'],

        ['name' => 'invoices.browse', 'guard_name' => 'admin', 'public_name' => 'Voir la liste des factures'],
        ['name' => 'invoices.read', 'guard_name' => 'admin', 'public_name' => 'Voir une facture'],
        ['name' => 'invoices.create', 'guard_name' => 'admin', 'public_name' => 'Créer une facture'],
        ['name' => 'invoices.edit', 'guard_name' => 'admin', 'public_name' => 'Modifier une facture'],
        ['name' => 'invoices.delete', 'guard_name' => 'admin', 'public_name' => 'Supprimer une facture'],

        ['name' => 'estimates.browse', 'guard_name' => 'admin', 'public_name' => 'Voir la liste des devis'],
        ['name' => 'estimates.read', 'guard_name' => 'admin', 'public_name' => 'Voir un devis'],
        ['name' => 'estimates.create', 'guard_name' => 'admin', 'public_name' => 'Créer un devis'],
        ['name' => 'estimates.edit', 'guard_name' => 'admin', 'public_name' => 'Modifier un devis'],
        ['name' => 'estimates.delete', 'guard_name' => 'admin', 'public_name' => 'Supprimer un devis'],

        ['name' => 'bcommandes.browse', 'guard_name' => 'admin', 'public_name' => 'Voir la liste des bons de commande'],
        ['name' => 'bcommandes.read', 'guard_name' => 'admin', 'public_name' => 'Voir un bon de commande'],
        ['name' => 'bcommandes.create', 'guard_name' => 'admin', 'public_name' => 'Créer un bon de commande'],
        ['name' => 'bcommandes.edit', 'guard_name' => 'admin', 'public_name' => 'Modifier un bon de commande'],
        ['name' => 'bcommandes.delete', 'guard_name' => 'admin', 'public_name' => 'Supprimer un bon de commande'],

        ['name' => 'blivraison.browse', 'guard_name' => 'admin', 'public_name' => 'Voir la liste des bons de livraison'],
        ['name' => 'blivraison.read', 'guard_name' => 'admin', 'public_name' => 'Voir un bon de livraison'],
        ['name' => 'blivraison.create', 'guard_name' => 'admin', 'public_name' => 'Créer un bon de livraison'],
        ['name' => 'blivraison.edit', 'guard_name' => 'admin', 'public_name' => 'Modifier un bon de livraison'],
        ['name' => 'blivraison.delete', 'guard_name' => 'admin', 'public_name' => 'Supprimer un bon de livraison'],

        ['name' => 'providers.browse', 'guard_name' => 'admin', 'public_name' => 'Voir la liste des fournisseurs'],
        ['name' => 'providers.read', 'guard_name' => 'admin', 'public_name' => 'Voir un fournisseur'],
        ['name' => 'providers.create', 'guard_name' => 'admin', 'public_name' => 'Créer un fournisseur'],
        ['name' => 'providers.edit', 'guard_name' => 'admin', 'public_name' => 'Modifier un fournisseur'],
        ['name' => 'providers.delete', 'guard_name' => 'admin', 'public_name' => 'Supprimer un fournisseur'],

        ['name' => 'payments.browse', 'guard_name' => 'admin', 'public_name' => 'Voir la liste des paiements'],
        ['name' => 'payments.read', 'guard_name' => 'admin', 'public_name' => 'Voir un paiement'],
        ['name' => 'payments.create', 'guard_name' => 'admin', 'public_name' => 'Créer un paiement'],
        ['name' => 'payments.edit', 'guard_name' => 'admin', 'public_name' => 'Modifier un paiement'],
        ['name' => 'payments.delete', 'guard_name' => 'admin', 'public_name' => 'Supprimer un paiement'],

        ['name' => 'report.browse', 'guard_name' => 'admin', 'public_name' => 'Voir la liste des rapports'],
        ['name' => 'report.read', 'guard_name' => 'admin', 'public_name' => 'Voir un rapport'],
        ['name' => 'report.create', 'guard_name' => 'admin', 'public_name' => 'Créer un rapport'],
        ['name' => 'report.edit', 'guard_name' => 'admin', 'public_name' => 'Modifier un rapport'],
        ['name' => 'report.delete', 'guard_name' => 'admin', 'public_name' => 'Supprimer un rapport'],

    ];


    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        foreach ($this->permissions as $permission) {
            Permission::create($permission);
        }

        $permissionsItems = Permission::all();

        $adminRole = Role::whereName('SuperAdmin')->first();

        $adminRole->syncPermissions($permissionsItems);
    }
}
