{{-- Mirrors components/sidebar/settings-menu. Keep both in sync until the legacy sidebar is deleted (MIGRATION_AUDIT.md §11). --}}
@hasanyrole('Admin|SuperAdmin')
    <x-shell.sidebar.title>{{ __('Paramètres') }}</x-shell.sidebar.title>

    <x-shell.sidebar.item icon="bx bx-building" :label="__('navbar.companies')" :href="route('commercial:companies.index')" />

    <x-shell.sidebar.item icon="bx bx-user-circle" label="Utilisateurs" :href="route('admin:admins')" />
@endhasanyrole
