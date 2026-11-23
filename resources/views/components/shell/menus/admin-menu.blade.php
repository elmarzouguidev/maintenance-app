{{-- Mirrors components/sidebar/admin-menu. Keep both in sync until the legacy sidebar is deleted (MIGRATION_AUDIT.md §11). --}}
@props(['new_tickets_diagnostic' => 0])

@hasanyrole('Admin|SuperAdmin|ASSISTANTE DIRECTEUR')
    <x-shell.sidebar.item icon="bx bx-task" :label="__('navbar.diagnostic')" :href="route('admin:diagnostic.index')" :badge="$new_tickets_diagnostic ? '.' : null" />
@endhasanyrole
