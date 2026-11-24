// Laravel's axios setup (resources/js/bootstrap.js), not the Bootstrap CSS framework.
import './bootstrap';

// Alpine.js for the Tailwind, auth and UI kit layouts, with the plugins their views use:
// x-anchor (dropdown), x-collapse (sidebar, accordion), x-trap (modal), $persist (sidebar store).
// Livewire is being removed from the app (MIGRATION_AUDIT.md §9): pages on the Bootstrap layout
// still get Alpine from @livewireScripts until then, and they never load this file.
import Alpine from 'alpinejs';
import anchor from '@alpinejs/anchor';
import collapse from '@alpinejs/collapse';
import focus from '@alpinejs/focus';
import persist from '@alpinejs/persist';
import { registerUiKit } from '../../ui-kit/laravel/js/index.js';

Alpine.plugin(anchor);
Alpine.plugin(collapse);
Alpine.plugin(focus);
Alpine.plugin(persist);

// UI kit Alpine components (adminNavGroups, adminSelect2), used by the kit layouts and x-admin.* views.
registerUiKit(Alpine);

// Sidebar of the Tailwind layout: collapsed on desktop (remembered), off-canvas on mobile.
Alpine.store('sidebar', {
    collapsed: Alpine.$persist(false).as('sidebar-collapsed'),
    open: false,

    toggle() {
        if (window.matchMedia('(min-width: 62rem)').matches) {
            this.collapsed = !this.collapsed;
        } else {
            this.open = !this.open;
        }
    },
});

// This module is deferred: it runs once the document is parsed, so the inline scripts that
// define Alpine data (such as layouts/date_picker_js) are already there when Alpine starts.
window.Alpine = Alpine;
Alpine.start();
