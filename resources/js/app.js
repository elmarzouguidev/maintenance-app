// Laravel's axios setup (resources/js/bootstrap.js), not the Bootstrap CSS framework.
import './bootstrap';

// Alpine.js for the UI kit layouts (resources/views/layouts), as ui-kit/laravel/INSTALL.md §3.
// Livewire is being removed from the app (MIGRATION_AUDIT.md §9): pages on the Bootstrap layout
// still get Alpine from @livewireScripts until then, and they never load this file.
import Alpine from 'alpinejs';
import { registerUiKit } from '../../ui-kit/laravel/js/index.js';

// UI kit Alpine components (adminNavGroups, adminSelect2), used by the kit layouts and x-admin.* views.
registerUiKit(Alpine);

// This module is deferred: it runs once the document is parsed, so the inline scripts that
// define Alpine data (such as layouts/date_picker_js) are already there when Alpine starts.
window.Alpine = Alpine;
Alpine.start();
