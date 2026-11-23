// Laravel's axios setup (resources/js/bootstrap.js), not the Bootstrap CSS framework.
// Alpine.js is provided by Livewire through @livewireScripts; do not install it separately.
import './bootstrap';

// Alpine starts on DOMContentLoaded, after this module has run, so stores can be registered here.
document.addEventListener('alpine:init', () => {
    const Alpine = window.Alpine;

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
});
