{{-- Mirrors components/sidebar/dashboard-item. Keep both in sync until the legacy sidebar is deleted (MIGRATION_AUDIT.md §11). --}}
<x-shell.sidebar.item
    icon="bx bx-home-circle"
    :label="__('navbar.dashboard')"
    :href="route('admin:home').'?appFilter[DateBetween]='.now()->startOfYear()->format('Y-m-d').','.now()->endOfYear()->format('Y-m-d').'&appFilter[GetCompany]=1&currentYear=true'"
/>
