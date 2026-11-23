{{-- Sidebar section title (Skote .menu-title). Hidden when the sidebar is collapsed. --}}
<li {{ $attributes->class('pointer-events-none px-5 py-3 text-11 font-semibold uppercase tracking-[.05em] text-sidebar-icon lg:sidebar-collapsed:hidden') }}>
    {{ $slot }}
</li>
