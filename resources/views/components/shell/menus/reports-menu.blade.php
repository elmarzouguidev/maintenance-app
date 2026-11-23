{{-- Mirrors components/sidebar/reports-menu. Keep both in sync until the legacy sidebar is deleted (MIGRATION_AUDIT.md §11). --}}
<x-shell.sidebar.item icon="bx bx-task" label="Rapports">
    @can('report.browse')
        <x-shell.sidebar.subitem icon="bx bx-task" :href="route('admin:rapports.index')">List</x-shell.sidebar.subitem>
    @endcan

    @can('report.edit')
        <x-shell.sidebar.subitem icon="bx bx-task" :href="route('admin:rapports.editions.index')">Edition</x-shell.sidebar.subitem>
    @endcan
</x-shell.sidebar.item>
