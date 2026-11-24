/**
 * UI kit Alpine components. In a Vite project (resources/js/app.js):
 *
 *     import Alpine from 'alpinejs';
 *     import { registerUiKit } from '../../ui-kit/laravel/js/index.js';
 *
 *     registerUiKit(Alpine);
 *     window.Alpine = Alpine;
 *     Alpine.start();
 *
 * The date picker, the file upload and the vehicle condition map ship their scripts with
 * their Blade views (layouts/date_picker_js.blade.php and the components themselves).
 */
import adminNavGroups from './nav-groups.js';
import adminSelect2 from './select2.js';

export function registerUiKit(Alpine) {
    Alpine.data('adminNavGroups', adminNavGroups);
    Alpine.data('adminSelect2', adminSelect2);
}
