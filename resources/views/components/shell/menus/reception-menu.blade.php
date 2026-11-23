{{-- Mirrors components/sidebar/reception-menu. Keep both in sync until the legacy sidebar is deleted (MIGRATION_AUDIT.md §11). --}}
@props(['tickets_livrable' => 0])

@if (auth()->user()->hasRole('Reception'))
    <x-shell.sidebar.item icon="bx bx-user" :label="__('navbar.clients')" :href="route('admin:clients.index')" />

    <x-shell.sidebar.item label="Prét a la livraison" :href="route('admin:tickets.livrable')" :badge="$tickets_livrable ? '.' : null" />
@endif
