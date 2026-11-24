/**
 * Searchable select (x-admin.form-select2). The submitted value is the hidden input. Other
 * scripts can replace its options or value with the window events "admin-select2:options"
 * and "admin-select2:value" ({ name, options } / { name, value }); it dispatches
 * "admin-select2:change" ({ name, value, userTriggered }).
 */
export default function adminSelect2({ name, options, selectedValue = '', placeholder, searchPlaceholder, emptyText }) {
    return {
        name,
        options,
        selectedValue,
        placeholder,
        searchPlaceholder,
        emptyText,
        open: false,
        search: '',
        highlighted: -1,
        init() {
            window.addEventListener('admin-select2:options', (event) => {
                if (event.detail?.name === this.name) {
                    this.replaceOptions(event.detail.options ?? []);
                }
            });

            window.addEventListener('admin-select2:value', (event) => {
                if (event.detail?.name === this.name) {
                    this.select(String(event.detail.value ?? ''), false);
                }
            });
        },
        get filteredOptions() {
            const query = this.search.trim().toLowerCase();

            if (!query) {
                return this.options;
            }

            return this.options.filter((option) => option.label.toLowerCase().includes(query));
        },
        /** The "no selection" entry first, then the filtered options. */
        get entries() {
            return [{ value: '', label: this.placeholder }, ...this.filteredOptions];
        },
        get selectedLabel() {
            return this.options.find((option) => option.value === this.selectedValue)?.label ?? '';
        },
        replaceOptions(options) {
            this.options = Array.isArray(options) ? options : [];

            if (this.selectedValue && !this.options.some((option) => option.value === this.selectedValue)) {
                this.select('', false);
            }
        },
        toggle() {
            this.open = !this.open;

            if (this.open) {
                this.highlighted = Math.max(0, this.entries.findIndex((entry) => entry.value === this.selectedValue));
                this.$nextTick(() => this.$refs.searchInput?.focus());
            }
        },
        close() {
            this.open = false;
            this.search = '';
            this.highlighted = -1;
        },
        move(delta) {
            const last = this.entries.length - 1;
            this.highlighted = Math.min(Math.max(this.highlighted + delta, 0), last);
            this.$nextTick(() => document.getElementById(this.$refs.searchInput.getAttribute('aria-activedescendant'))?.scrollIntoView({ block: 'nearest' }));
        },
        chooseHighlighted() {
            const entry = this.entries[this.highlighted];

            if (entry) {
                this.select(entry.value, true);
                this.$refs.trigger?.focus();
            }
        },
        select(value, userTriggered = false) {
            this.selectedValue = value;
            this.$dispatch('admin-select2:change', { name: this.name, value: this.selectedValue, userTriggered });
            this.close();
        },
    };
}
