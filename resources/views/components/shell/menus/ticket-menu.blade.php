{{-- Mirrors components/sidebar/ticket-menu. Keep both in sync until the legacy sidebar is deleted (MIGRATION_AUDIT.md §11). --}}
@props(['new_tickets' => 0, 'tickets_livrable' => 0])

<x-shell.sidebar.title>Gestion de ticket</x-shell.sidebar.title>

<x-shell.sidebar.item icon="bx bx-task" :label="__('navbar.tickets')" :badge="$new_tickets">
    @can('ticket.browse')
        <x-shell.sidebar.subitem :href="route('admin:tickets.list.old')" :badge="$new_tickets">{{ __('navbar.tickets') }}</x-shell.sidebar.subitem>
    @endcan

    @if (auth()->user()->hasAnyRole('SuperAdmin', 'Admin'))
        <x-shell.sidebar.subitem :href="route('admin:tickets.livrable')" :badge="$tickets_livrable ? '.' : null">Livraison</x-shell.sidebar.subitem>
    @endif
</x-shell.sidebar.item>
