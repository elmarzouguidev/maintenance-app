{{-- Mirrors components/sidebar/technician-menu. Keep both in sync until the legacy sidebar is deleted (MIGRATION_AUDIT.md §11). --}}
@props(['new_tickets_diagnostic_tech' => 0])

@role('Technicien')
    <x-shell.sidebar.item icon="bx bx-task" :label="__('navbar.diagnostic_tech')" :badge="$new_tickets_diagnostic_tech ? '.' : null">
        <x-shell.sidebar.subitem :href="route('admin:diagnostic.index')">{{ __('navbar.diagnostic_tech') }}</x-shell.sidebar.subitem>
    </x-shell.sidebar.item>
@endrole
