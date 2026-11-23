{{--
    Dark sidebar of the Tailwind layout. Mirrors components/sidebar/main-sidebar (Skote .vertical-menu).
    Desktop: 250px, collapsible to 70px (Alpine store "sidebar", remembered in localStorage).
    Mobile (< lg / 992px): off-canvas, opened from the topbar button.
--}}
@props([
    'new_tickets' => 0,
    'tickets_livrable' => 0,
    'new_tickets_diagnostic_tech' => 0,
    'new_tickets_diagnostic' => 0,
    'estimates_not_send' => 0,
])

<aside
    data-test="sidebar"
    x-bind:data-open="$store.sidebar.open"
    class="fixed bottom-0 start-0 top-topbar z-[1001] w-sidebar bg-sidebar shadow-card transition-transform max-lg:-translate-x-full max-lg:data-open:translate-x-0 lg:sidebar-collapsed:w-sidebar-collapsed"
>
    <nav class="h-full overflow-y-auto pt-2.5 pb-[30px] lg:sidebar-collapsed:overflow-visible" aria-label="Menu principal">
        <ul>
            <x-shell.sidebar.title>Menu</x-shell.sidebar.title>

            <x-shell.menus.dashboard-item />
            <x-shell.menus.reception-menu :tickets_livrable="$tickets_livrable" />
            <x-shell.menus.commercial-menu :estimates_not_send="$estimates_not_send" />
            <x-shell.menus.ticket-menu :new_tickets="$new_tickets" :tickets_livrable="$tickets_livrable" />
            <x-shell.menus.technician-menu :new_tickets_diagnostic_tech="$new_tickets_diagnostic_tech" />
            <x-shell.menus.reports-menu />
            <x-shell.menus.admin-menu :new_tickets_diagnostic="$new_tickets_diagnostic" />
            <x-shell.menus.settings-menu />
            <x-shell.menus.developer-menu />
        </ul>
    </nav>
</aside>

<div
    data-test="sidebar-backdrop"
    x-show="$store.sidebar.open"
    x-transition.opacity
    x-on:click="$store.sidebar.open = false"
    style="display: none"
    class="fixed inset-0 top-topbar z-[1000] bg-black/40 lg:hidden"
    aria-hidden="true"
></div>
