<?php

use App\Constants\Etat;
use App\Constants\Status;
use App\Models\User;
use App\Support\AdminNavigation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

/*
 * Navigation of the UI kit layout (App\Support\AdminNavigation): the entries, permissions and
 * roles of the former sidebar, the current entry, and the phone shortcuts.
 */

uses(RefreshDatabase::class);

/**
 * @param  list<string>  $roles
 * @param  list<string>  $permissions
 */
function navigationUser(array $roles = [], array $permissions = []): User
{
    $user = User::factory()->create();

    foreach ($roles as $role) {
        $user->assignRole(Role::findOrCreate($role, 'admin'));
    }

    foreach ($permissions as $permission) {
        $user->givePermissionTo(Permission::findOrCreate($permission, 'admin'));
    }

    return $user;
}

/**
 * @param  array{navigation: list<array{items: list<array{label: string}>}>}  $layout
 * @return list<string>
 */
function navigationLabels(array $layout): array
{
    return collect($layout['navigation'])->flatMap(fn (array $group): array => array_column($group['items'], 'label'))->all();
}

/**
 * @param  array{navigation: list<array{items: list<array{label: string, active: bool}>}>}  $layout
 * @return list<string>
 */
function activeNavigationLabels(array $layout): array
{
    return collect($layout['navigation'])->flatMap(fn (array $group): array => $group['items'])->where('active', true)->pluck('label')->values()->all();
}

it('shows only the dashboard to a user without role or permission', function () {
    $layout = app(AdminNavigation::class)->forUser(navigationUser(), 'app/admin');

    expect(navigationLabels($layout))->toBe(['Tableau de bord'])
        ->and($layout['bottomBar'])->toHaveCount(1)
        ->and($layout['bottomBar'][0])->toMatchArray(['label' => 'Accueil', 'current' => true]);
});

it('shows the commercial entries the user has a permission for, in their group', function () {
    $layout = app(AdminNavigation::class)->forUser(navigationUser(permissions: ['estimates.browse', 'invoices.browse']), 'app/admin');

    expect($layout['navigation'])->toHaveCount(2)
        ->and($layout['navigation'][1]['label'])->toBe('Gestion commerciale')
        ->and(array_column($layout['navigation'][1]['items'], 'label'))->toBe(['Devis', 'Factures', 'Avoirs']);
});

it('shows the entries of the :dataset role', function (string $role, array $expectedLabels) {
    $layout = app(AdminNavigation::class)->forUser(navigationUser([$role]), 'app/admin');

    expect(navigationLabels($layout))->toBe($expectedLabels);
})->with([
    'reception' => ['Reception', ['Tableau de bord', 'Prêt à la livraison']],
    'admin' => ['Admin', ['Tableau de bord', 'Rapport clients', 'Livraison', 'Diagnostiques', 'Sociétés']],
    'technician' => ['Technicien', ['Tableau de bord', 'Tickets', 'Mes diagnostiques']],
    'assistant' => ['ASSISTANTE DIRECTEUR', ['Tableau de bord', 'Diagnostiques']],
    'developer' => ['Developper', ['Tableau de bord']],
]);

it('shows every entry once to a SuperAdmin', function () {
    $layout = app(AdminNavigation::class)->forUser(navigationUser(['SuperAdmin']), 'app/admin');

    expect(navigationLabels($layout))->toBe([
        'Tableau de bord',
        'Devis', 'Factures', 'Avoirs', 'Règlements', 'Bon de commande', 'Bons de livraison', 'Fournisseurs', 'Clients', 'Rapport clients',
        'Tickets', 'Livraison', 'Diagnostiques',
        'Liste', 'Édition',
        'Sociétés', 'Utilisateurs',
        'Roles', 'Permissions',
    ]);
});

it('lists a destination once when two rules grant it', function () {
    $layout = app(AdminNavigation::class)->forUser(navigationUser(['Reception'], ['client.browse']), 'app/admin');

    expect(navigationLabels($layout))->toBe(['Tableau de bord', 'Clients', 'Prêt à la livraison']);
});

it('adds the count of new tickets to the tickets label', function () {
    foreach (['TCK-1', 'TCK-2', 'TCK-3'] as $index => $code) {
        DB::table('tickets')->insert([
            'uuid' => (string) Str::uuid(),
            'code' => $code,
            'article' => 'Imprimante',
            'description' => 'Ne démarre plus',
            'etat' => Etat::NON_DIAGNOSTIQUER,
            // The third ticket is already being diagnosed, so it is not new.
            'status' => $index < 2 ? Status::NON_TRAITE : Status::EN_COURS_DE_DIAGNOSTIC,
        ]);
    }

    $layout = app(AdminNavigation::class)->forUser(navigationUser(permissions: ['ticket.browse']), 'app/admin');

    expect(navigationLabels($layout))->toContain('Tickets (2)');
});

it('marks the most specific entry as current on :dataset', function (string $path, array $expectedActive) {
    $layout = app(AdminNavigation::class)->forUser(navigationUser(['SuperAdmin']), $path);

    expect(activeNavigationLabels($layout))->toBe($expectedActive);
})->with([
    'the dashboard' => ['app/admin', ['Tableau de bord']],
    'a page below an entry' => ['app/commercial/invoices/create', ['Factures']],
    'a nested entry' => ['app/commercial/invoices/invoices-avoir', ['Avoirs']],
    'a page below the dashboard path' => ['app/admin/tickets-livrable', ['Livraison']],
    'a ticket page' => ['app/tickets/42', ['Tickets']],
    'a page with no entry' => ['app/profile', []],
]);

it('lists at most four phone shortcuts in order of preference', function () {
    $layout = app(AdminNavigation::class)->forUser(navigationUser(['SuperAdmin']), 'app/diagnostic');

    expect(array_column($layout['bottomBar'], 'label'))->toBe(['Accueil', 'Tickets', 'Diagnostics', 'Livraison'])
        ->and(array_column($layout['bottomBar'], 'current'))->toBe([false, false, true, false]);
});
