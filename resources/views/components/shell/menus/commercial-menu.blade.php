{{-- Mirrors components/sidebar/commercial-menu. Keep both in sync until the legacy sidebar is deleted (MIGRATION_AUDIT.md §11). --}}
@props(['estimates_not_send' => 0])

<x-shell.sidebar.title>Gestion commercial</x-shell.sidebar.title>

<x-shell.sidebar.item icon="bx bx-grid-alt" :label="__('navbar.commercial')">
    @can('estimates.browse')
        <x-shell.sidebar.subitem icon="bx bx-file-blank" :href="route('commercial:estimates.index')">{{ __('navbar.estimates') }}</x-shell.sidebar.subitem>
    @endcan

    @can('invoices.browse')
        <x-shell.sidebar.subitem icon="bx bx-food-menu" :href="route('commercial:invoices.index')">{{ __('navbar.invoices') }}</x-shell.sidebar.subitem>
    @endcan

    @can('invoices.browse')
        <x-shell.sidebar.subitem icon="bx bx-food-menu" :href="route('commercial:invoices.index.avoir')">Avoirs</x-shell.sidebar.subitem>
    @endcan

    @can('payments.browse')
        <x-shell.sidebar.subitem icon="bx bx-money" :href="route('commercial:bills.index')">Règlements</x-shell.sidebar.subitem>
    @endcan

    @can('bcommandes.browse')
        <x-shell.sidebar.subitem icon="bx bx-file" :href="route('commercial:bcommandes.index')">{{ __('navbar.bc') }}</x-shell.sidebar.subitem>
    @endcan

    @can('blivraison.browse')
        <x-shell.sidebar.subitem icon="bx bx-file" :href="route('commercial:blivraison.index')">Bon de livraison</x-shell.sidebar.subitem>
    @endcan

    @can('providers.browse')
        <x-shell.sidebar.subitem icon="bx bx-user" :href="route('commercial:providers.index')">Fournisseurs</x-shell.sidebar.subitem>
    @endcan

    @can('client.browse')
        <x-shell.sidebar.subitem icon="bx bx-user" :href="route('admin:clients.index')">{{ __('navbar.clients') }}</x-shell.sidebar.subitem>
    @endcan

    @hasanyrole('Admin|SuperAdmin')
        <x-shell.sidebar.subitem icon="bx bx-line-chart" :href="route('commercial:reports.index')">Rapport Clients</x-shell.sidebar.subitem>
    @endhasanyrole
</x-shell.sidebar.item>
