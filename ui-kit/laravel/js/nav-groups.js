/**
 * Sidebar groups (x-data="adminNavGroups"): collapsed state remembered per browser; the group
 * holding the current page is always open. localStorage may be unavailable (private mode):
 * fall back to open.
 */
export default function adminNavGroups() {
    return {
        closed: {},
        init() {
            try {
                this.closed = JSON.parse(window.localStorage.getItem('ui-kit.nav.closed') || '{}') || {};
            } catch (error) {
                this.closed = {};
            }
        },
        isOpen(key, hasCurrent) {
            return hasCurrent || this.closed[key] !== true;
        },
        toggle(key) {
            this.closed[key] = this.closed[key] !== true;

            try {
                window.localStorage.setItem('ui-kit.nav.closed', JSON.stringify(this.closed));
            } catch (error) {
                // Not persisted; the toggle still works for this page.
            }
        },
    };
}
