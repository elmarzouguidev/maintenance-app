{{-- Mirrors components/sidebar/developer-menu. Keep both in sync until the legacy sidebar is deleted (MIGRATION_AUDIT.md §11). --}}
@if (auth()->user()->hasRole('Developper'))
    <x-shell.sidebar.item icon="bx bx-lock-alt" :label="__('navbar.roles_permissions')">
        <x-shell.sidebar.subitem :href="route('admin:permissions-roles.index')">{{ __('navbar.roles') }}</x-shell.sidebar.subitem>
    </x-shell.sidebar.item>
@endif
