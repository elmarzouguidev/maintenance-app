<?php

namespace App\Support;

use App\Models\Ticket;
use App\Models\User;
use Closure;

/**
 * Navigation of the UI kit tenant layout (resources/views/layouts/admin.blade.php) for one user:
 * the sidebar groups and the phone bottom bar.
 *
 * Same entries, permissions and roles as the former sidebar (components/shell/menus). The kit's
 * navigation has no badge, so the ticket counts are part of the label: "Tickets (3)".
 */
class AdminNavigation
{
    /** Phone shortcuts in order of preference, with their short label: the first four the user can see. */
    private const BOTTOM_BAR = [
        'dashboard' => 'Accueil',
        'tickets' => 'Tickets',
        'technician-diagnostics' => 'Diagnostics',
        'diagnostics' => 'Diagnostics',
        'ready-for-delivery' => 'Livraison',
        'delivery' => 'Livraison',
        'clients' => 'Clients',
    ];

    private const BOTTOM_BAR_SIZE = 4;

    /**
     * The layout data for $user on the page at $currentPath (request()->path()).
     *
     * @return array{navigation: list<array{label: ?string, items: list<array{href: string, label: string, icon: string, active: bool}>}>, bottomBar: list<array{href: string, label: string, icon: string, current: bool}>}
     */
    public function forUser(User $user, string $currentPath): array
    {
        $groups = $this->visibleGroups($user);
        $activeKey = $this->activeKey($groups, trim($currentPath, '/'));

        return [
            'navigation' => array_map(fn (array $group): array => [
                'label' => $group['label'],
                'items' => array_map(fn (array $item): array => [
                    'href' => $item['href'],
                    'label' => $item['label'],
                    'icon' => $item['icon'],
                    'active' => $item['key'] === $activeKey,
                ], $group['items']),
            ], $groups),
            'bottomBar' => $this->bottomBar($groups, $activeKey),
        ];
    }

    /**
     * The groups and entries $user may see, without empty groups or repeated destinations.
     *
     * @return list<array{label: ?string, items: list<array{key: string, href: string, label: string, icon: string, paths: list<string>, exact: bool}>}>
     */
    private function visibleGroups(User $user): array
    {
        $deliversAll = $user->can('ticket.delivery.browse_all');
        $delivers = $deliversAll || $user->can('ticket.delivery.browse');
        // Work permissions with checkPermissionTo: a SuperAdmin is not a technician (RolePresets).
        $hasOwnDiagnostics = $user->checkPermissionTo('diagnostic.assigned.browse') || $user->checkPermissionTo('diagnostic.manage_assigned');
        $deliverableCount = $this->once(fn (): int => Ticket::query()->ticketsLivrable(! $deliversAll));

        $dashboardUrl = route('admin:home').'?appFilter[DateBetween]='.now()->startOfYear()->format('Y-m-d').','.now()->endOfYear()->format('Y-m-d').'&appFilter[GetCompany]=1&currentYear=true';

        $groups = [
            ['label' => null, 'items' => [
                $this->item('dashboard', $dashboardUrl, __('navbar.dashboard'), 'home', exact: true),
            ]],
            ['label' => 'Gestion commerciale', 'items' => [
                $user->can('estimates.browse') ? $this->item('estimates', route('commercial:estimates.index'), __('navbar.estimates'), 'document') : null,
                $user->can('invoices.browse') ? $this->item('invoices', route('commercial:invoices.index'), __('navbar.invoices'), 'banknotes') : null,
                $user->can('invoices.browse') ? $this->item('credit-notes', route('commercial:invoices.index.avoir'), 'Avoirs', 'document') : null,
                $user->can('payments.browse') ? $this->item('payments', route('commercial:bills.index'), 'Règlements', 'card') : null,
                $user->can('bcommandes.browse') ? $this->item('purchase-orders', route('commercial:bcommandes.index'), __('navbar.bc'), 'list') : null,
                $user->can('blivraison.browse') ? $this->item('delivery-notes', route('commercial:blivraison.index'), 'Bons de livraison', 'truck') : null,
                $user->can('providers.browse') ? $this->item('providers', route('commercial:providers.index'), 'Fournisseurs', 'building') : null,
                $user->can('client.browse') ? $this->item('clients', route('admin:clients.index'), __('navbar.clients'), 'users') : null,
                $user->can('report.clients.browse') ? $this->item('client-report', route('commercial:reports.index'), 'Rapport clients', 'chart') : null,
            ]],
            ['label' => 'Gestion des tickets', 'items' => [
                $user->can('ticket.browse') ? $this->item('tickets', route('admin:tickets.list.old'), $this->withCount(__('navbar.tickets'), Ticket::query()->newTickets()), 'wrench', alsoActiveUnder: ['app/tickets']) : null,
                $delivers ? $this->item('delivery', route('admin:tickets.livrable'), $this->withCount($deliversAll ? 'Livraison' : 'Prêt à la livraison', $deliverableCount()), 'truck') : null,
                $hasOwnDiagnostics ? $this->item('technician-diagnostics', route('admin:diagnostic.index'), $this->withCount(__('navbar.diagnostic_tech'), Ticket::query()->newTicketsDiagnosticTech()), 'list') : null,
                $user->can('diagnostic.browse')
                    ? $this->item('diagnostics', route('admin:diagnostic.index'), $this->withCount(__('navbar.diagnostic'), $user->can('diagnostic.confirm') ? Ticket::query()->newTicketsDiagnostic() : 0), 'list')
                    : null,
            ]],
            ['label' => 'Rapports', 'items' => [
                $user->can('report.browse') ? $this->item('reports', route('admin:rapports.index'), 'Liste', 'list') : null,
                $user->can('report.edit') ? $this->item('report-editions', route('admin:rapports.editions.index'), 'Édition', 'pencil') : null,
            ]],
            ['label' => __('Paramètres'), 'items' => [
                $user->can('companies.browse') ? $this->item('companies', route('commercial:companies.index'), __('navbar.companies'), 'building') : null,
                // The users pages are SuperAdmin only (role:SuperAdmin on their routes).
                $user->hasRole('SuperAdmin') ? $this->item('users', route('admin:admins'), 'Utilisateurs', 'users') : null,
            ]],
            ['label' => __('navbar.roles_permissions'), 'items' => [
                $user->can('roles_permissions.browse') ? $this->item('roles', route('admin:permissions-roles.index'), __('navbar.roles'), 'key') : null,
                $user->can('roles_permissions.browse') ? $this->item('permissions', route('admin:permissions-roles.permissions'), __('navbar.permissions'), 'shield') : null,
            ]],
        ];

        $seenHrefs = [];
        $visible = [];

        foreach ($groups as $group) {
            $items = [];

            foreach (array_filter($group['items']) as $item) {
                if (isset($seenHrefs[$item['href']])) {
                    continue;
                }

                $seenHrefs[$item['href']] = true;
                $items[] = $item;
            }

            if ($items !== []) {
                $visible[] = ['label' => $group['label'], 'items' => $items];
            }
        }

        return $visible;
    }

    /**
     * The entry whose path is the most specific match for the current path. An entry matches its
     * own path and, unless it is exact, the pages below it ("app/commercial/invoices/create").
     *
     * @param  list<array{label: ?string, items: list<array{key: string, paths: list<string>, exact: bool}>}>  $groups
     */
    private function activeKey(array $groups, string $currentPath): ?string
    {
        $activeKey = null;
        $longestMatch = -1;

        foreach ($groups as $group) {
            foreach ($group['items'] as $item) {
                foreach ($item['paths'] as $path) {
                    $matches = $currentPath === $path || (! $item['exact'] && str_starts_with($currentPath, $path.'/'));

                    if ($matches && strlen($path) > $longestMatch) {
                        $activeKey = $item['key'];
                        $longestMatch = strlen($path);
                    }
                }
            }
        }

        return $activeKey;
    }

    /**
     * @param  list<array{label: ?string, items: list<array{key: string, href: string, icon: string}>}>  $groups
     * @return list<array{href: string, label: string, icon: string, current: bool}>
     */
    private function bottomBar(array $groups, ?string $activeKey): array
    {
        $items = collect($groups)->flatMap(fn (array $group): array => $group['items'])->keyBy('key');
        $shortcuts = [];

        foreach (self::BOTTOM_BAR as $key => $shortLabel) {
            if (count($shortcuts) === self::BOTTOM_BAR_SIZE) {
                break;
            }

            if ($items->has($key)) {
                $shortcuts[] = [
                    'href' => $items[$key]['href'],
                    'label' => $shortLabel,
                    'icon' => $items[$key]['icon'],
                    'current' => $key === $activeKey,
                ];
            }
        }

        return $shortcuts;
    }

    /**
     * @param  list<string>  $alsoActiveUnder  other path prefixes whose pages keep this entry current
     * @return array{key: string, href: string, label: string, icon: string, paths: list<string>, exact: bool}
     */
    private function item(string $key, string $href, string $label, string $icon, array $alsoActiveUnder = [], bool $exact = false): array
    {
        return [
            'key' => $key,
            'href' => $href,
            'label' => $label,
            'icon' => $icon,
            'paths' => [trim((string) parse_url($href, PHP_URL_PATH), '/'), ...$alsoActiveUnder],
            'exact' => $exact,
        ];
    }

    private function withCount(string $label, int $count): string
    {
        return $count > 0 ? $label.' ('.$count.')' : $label;
    }

    /**
     * Run $callback the first time the returned closure is called, then reuse its result.
     *
     * @param  Closure(): int  $callback
     * @return Closure(): int
     */
    private function once(Closure $callback): Closure
    {
        $result = null;

        return function () use ($callback, &$result): int {
            return $result ??= $callback();
        };
    }
}
