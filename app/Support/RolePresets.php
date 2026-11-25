<?php

namespace App\Support;

/**
 * Roles are presets: named bundles of permissions given to users in one step. The code checks
 * permissions only; SuperAdmin is the one role that means something (it passes every check,
 * AuthServiceProvider's Gate::before).
 *
 * Each preset is what the code granted the role by its name until 2026-11-25, expressed as
 * permissions, so switching the checks changes nobody's access. Applied (added, never removed)
 * by RolePermissionPresetSeeder, run on deploy by the apply_role_permission_presets migration.
 *
 * Work on tickets has two levels: "assigned" permissions act on the tickets assigned to the
 * user, "manage_assigned" ones on every technician's tickets. ticket.work marks the people who
 * receive tickets; check it, and the work permissions, with checkPermissionTo() where they decide
 * who is a technician (SuperAdmin passes can() for everything without being one).
 */
final class RolePresets
{
    /**
     * Permissions the presets use, created when missing: name => public name.
     *
     * @var array<string, string>
     */
    public const PERMISSIONS = [
        'ticket.browse' => 'Voir la liste des tickets',
        'ticket.read' => 'Voir un ticket',
        'ticket.work' => 'Recevoir des tickets à diagnostiquer ou réparer',
        'ticket.reassign' => 'Réaffecter un ticket',
        'ticket.photos.manage' => 'Ajouter et supprimer les photos d’un ticket',
        'ticket.delivery.browse' => 'Voir les tickets à livrer',
        'ticket.delivery.browse_all' => 'Voir tous les tickets à livrer',
        'ticket.delivery.confirm' => 'Confirmer une livraison',
        'ticket.delivery.admin_confirm' => 'Confirmer une livraison en tant qu’administrateur',
        'diagnostic.assigned.browse' => 'Voir les diagnostics affectés à soi',
        'diagnostic.edit' => 'Modifier un diagnostic',
        'diagnostic.send_report' => 'Envoyer un rapport de diagnostic',
        'diagnostic.manage_assigned' => 'Gérer les diagnostics affectés aux techniciens',
        'diagnostic.browse' => 'Voir les diagnostics',
        'diagnostic.confirm' => 'Confirmer un diagnostic',
        'reparations.assigned.browse' => 'Voir les réparations affectées à soi',
        'reparations.edit' => 'Modifier une réparation',
        'reparations.complete' => 'Terminer une réparation',
        'reparations.manage_assigned' => 'Gérer les réparations affectées aux techniciens',
        'dashboard.analytics.browse' => 'Voir les indicateurs financiers et analytiques',
        'report.read' => 'Voir un rapport',
        'report.clients.browse' => 'Voir le rapport clients',
        'companies.browse' => 'Voir la liste des sociétés',
        'companies.edit' => 'Modifier une société',
        'account.password.reset' => 'Réinitialiser son mot de passe par e-mail',
    ];

    /**
     * Role => the permissions it gives.
     *
     * @var array<string, list<string>>
     */
    public const ROLES = [
        'Technicien' => [
            'ticket.work', 'ticket.browse', 'ticket.read',
            'diagnostic.assigned.browse', 'diagnostic.edit', 'diagnostic.send_report',
            'reparations.assigned.browse', 'reparations.edit', 'reparations.complete',
        ],
        'SuperTechnicien' => [
            'diagnostic.manage_assigned', 'diagnostic.edit', 'diagnostic.send_report',
            'reparations.manage_assigned', 'reparations.edit', 'reparations.complete',
        ],
        'Reception' => [
            'ticket.photos.manage', 'ticket.delivery.browse', 'ticket.delivery.confirm',
        ],
        'Admin' => [
            'ticket.delivery.browse_all', 'ticket.delivery.admin_confirm',
            'diagnostic.browse', 'diagnostic.confirm',
            'dashboard.analytics.browse', 'report.read', 'report.clients.browse',
            'companies.browse', 'companies.edit',
        ],
        'ASSISTANTE DIRECTEUR' => ['diagnostic.browse', 'account.password.reset'],
        'SECRETAIRE' => [],
        'Developper' => ['account.password.reset'],
        'ADMINISTRATEUR' => ['account.password.reset'],
    ];
}
