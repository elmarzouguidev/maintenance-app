<?php

namespace UiKitExport;

/**
 * Every page of the static gallery (ui-kit/static), grouped by section. The exporter renders
 * each page, the index lists them and the gallery bar links them, so nothing is unreachable.
 *
 * kind: gallery (a kit-export view in the gallery layout), tenant / tenant-restricted /
 * tenant-field (the app's tenant layout, as an owner, as a restricted member, as a phone field
 * screen), platform (platform layout), guest (guest layout).
 */
final class Manifest
{
    /**
     * @return list<array{title: string, dir: string, description: string, pages: list<array<string, string>>}>
     */
    public static function sections(): array
    {
        return [
            [
                'title' => 'Foundations',
                'dir' => 'foundations',
                'description' => 'Tokens, icons and display formats every component is built on.',
                'pages' => [
                    self::gallery('tokens', 'Design tokens', 'Colours, type scale, radius, elevation and motion: the only values a view may use.', 'resources/css/admin.css', 'theme/theme.css, brand/brand.css', 'Tokens', 'All colour tokens (brand, surfaces, text, status, chart), type scale, radius, shadow, easing'),
                    self::gallery('icons', 'Icons', 'The 51 inline Heroicons v2 outline icons of x-admin.icon.', 'resources/views/components/admin/icon.blade.php', 'laravel/components/admin/icon.blade.php', 'Icon set', 'Every icon name; decorative and labelled use'),
                    self::gallery('formatting', 'Display formats', 'Amounts, dates, plates and durations: one format everywhere, tabular figures.', 'resources/views/components/admin/{amount,date,plate}.blade.php', 'laravel/components/admin/{amount,date,plate}.blade.php, laravel/support/Format.php', 'Formatting', 'Amount (major, minor, other currency, missing), date (short, with time, long, missing), plate (Latin, Arabic, missing), duration'),
                ],
            ],
            [
                'title' => 'Components',
                'dir' => 'components',
                'description' => 'Every x-admin.* Blade component with its variants and states.',
                'pages' => [
                    self::gallery('buttons', 'Buttons', 'x-admin.button and x-admin.icon-button.', 'resources/views/components/admin/{button,icon-button}.blade.php', 'laravel/components/admin/{button,icon-button}.blade.php', 'Actions', 'Variants primary, secondary, ghost, danger; sizes sm, md, lg; icon, link, loading, disabled; icon buttons md, sm, link, disabled; 44 px field-screen sizes'),
                    self::gallery('alerts', 'Alerts and messages', 'x-admin.alert, flash messages (x-messages) and the validation summary (x-messages.errors).', 'resources/views/components/admin/alert.blade.php, resources/views/components/messages/{index,errors}.blade.php', 'laravel/components/admin/alert.blade.php, laravel/components/messages/{index,errors}.blade.php', 'Feedback', 'Success, error, warning, info; dismissible and not; flash messages from the session; validation summary'),
                    self::gallery('badges', 'Status badges', 'x-admin.status-badge and x-admin.badge: the single status colour mapping.', 'resources/views/components/admin/{status-badge,badge}.blade.php', 'laravel/components/admin/{status-badge,badge}.blade.php, laravel/support/{BadgeVariant,HasStatusBadge}.php', 'Status', 'Neutral, success, warning, danger, info; with an enum, a variant or a label'),
                    self::gallery('stats', 'Stats cards', 'x-admin.stats-card in x-admin.stats-grid: key figures, filters and links.', 'resources/views/components/admin/{stats-card,stats-grid}.blade.php', 'laravel/components/admin/{stats-card,stats-grid}.blade.php', 'Dashboard figures', 'Variants primary, success, warning, danger, info, neutral; link card; selected filter; without icon; counts grid (4) and amounts grid (3)'),
                    self::gallery('page-header', 'Page header', 'x-admin.page-header: the one visible title of a page.', 'resources/views/components/admin/page-header.blade.php', 'laravel/components/admin/page-header.blade.php', 'Page title', 'Title only; eyebrow and description; breadcrumbs; actions'),
                    self::gallery('detail', 'Detail page', 'x-admin.detail-page, x-admin.detail-section, x-admin.detail-item: hairline sections on the white page.', 'resources/views/components/admin/detail-{page,section,item}.blade.php', 'laravel/components/admin/detail-{page,section,item}.blade.php', 'Entity detail ("Encre" structure)', 'Sections with and without actions; label/value grids; aside summary panels'),
                    self::gallery('empty-state', 'Empty states', 'x-admin.empty-state for an empty list, search or panel.', 'resources/views/components/admin/empty-state.blade.php', 'laravel/components/admin/empty-state.blade.php', 'Empty', 'With icon, description and actions; minimal'),
                    self::gallery('table', 'Tables', 'x-admin.table: compact rows, hairlines, horizontal scroll inside the panel.', 'resources/views/components/admin/table.blade.php', 'laravel/components/admin/table.blade.php', 'Lists', 'Headers array; headers in the slot; status, amount, plate and date cells; row actions; empty row'),
                    self::gallery('form-inputs', 'Text fields', 'x-admin.form-input and x-admin.textarea, on the shared field wrapper.', 'resources/views/components/admin/{form-input,textarea}.blade.php, resources/views/components/admin/form/field.blade.php', 'laravel/components/admin/{form-input,textarea}.blade.php, laravel/components/admin/form/field.blade.php', 'Forms', 'Default, required, help, validation error, previous input, read-only, disabled, plate input, number and e-mail input modes'),
                    self::gallery('form-choices', 'Choices', 'x-admin.form-select, x-admin.form-select2, x-admin.segmented-radio, checkbox and switch.', 'resources/views/components/admin/{form-select,form-select2,segmented-radio}.blade.php', 'laravel/components/admin/{form-select,form-select2,segmented-radio}.blade.php', 'Forms', 'Select with placeholder and error; searchable select; segmented radio default and error; checkboxes; switch'),
                    self::gallery('date-picker', 'Date picker', 'x-admin.date-picker: date, date and time, time only, native on phones.', 'resources/views/components/admin/date-picker.blade.php, resources/views/layouts/date_picker_js.blade.php', 'laravel/components/admin/date-picker.blade.php, laravel/layouts/date_picker_js.blade.php', 'Forms', 'Empty, with value, with time, time only, min and max dates, native below md, validation error'),
                    self::gallery('file-upload', 'File upload', 'x-admin.file-upload: drop zone with previews.', 'resources/views/components/admin/file-upload.blade.php', 'laravel/components/admin/file-upload.blade.php', 'Forms', 'Single image, multiple files, current image preview, validation error'),
                    self::gallery('setting-field', 'Settings fields', 'x-admin.setting-field: one settings field of any type.', 'resources/views/components/admin/setting-field.blade.php', 'laravel/components/admin/setting-field.blade.php', 'Settings forms', 'Text, number, select, textarea, file with preview, toggle; validation error'),
                    self::gallery('modal', 'Modal dialogs', 'x-admin.modal: native dialog with focus trap, Escape and focus return.', 'resources/views/components/admin/modal.blade.php', 'laravel/components/admin/modal.blade.php', 'Confirmation', 'Information dialog; destructive confirmation; form in a dialog'),
                    self::gallery('delete-action', 'Delete action', 'x-admin.delete-action: confirmed deletion, or the reason it is refused.', 'resources/views/components/admin/delete-action.blade.php', 'laravel/components/admin/delete-action.blade.php', 'Destructive action', 'Allowed (browser confirmation); refused with its reason (disabled, described)'),
                    self::gallery('navigation', 'Navigation', 'x-admin.nav-link, sidebar groups, section tabs and pagination links.', 'resources/views/components/admin/nav-link.blade.php, resources/views/layouts/partials/admin-nav.blade.php', 'laravel/components/admin/nav-link.blade.php, laravel/layouts/partials/admin-nav.blade.php', 'Navigation', 'Current and other links, with and without icon; a collapsible group; the light platform variant; section tabs'),
                    self::gallery('guest-heading', 'Guest heading', 'x-admin.guest-heading: logo and title of sign-in pages.', 'resources/views/components/admin/guest-heading.blade.php', 'laravel/components/admin/guest-heading.blade.php', 'Guest pages', 'With logo, eyebrow and description; without logo'),
                ],
            ],
            [
                'title' => 'Domain components',
                'dir' => 'domain',
                'description' => 'Optional, vehicle-rental specific. Keep them only in projects that need them.',
                'pages' => [
                    self::gallery('vehicle-condition', 'Vehicle condition map', 'x-admin.vehicle-condition-map and x-admin.vehicle-top-view: damage zones at check-out and check-in. Optional domain component.', 'resources/views/components/admin/{vehicle-condition-map,vehicle-top-view}.blade.php', 'laravel/components/admin/{vehicle-condition-map,vehicle-top-view}.blade.php, theme/images/vehicle-condition-top-view.png', 'Field capture', 'Check-out with zones already marked, check-in empty, plate input'),
                ],
            ],
            [
                'title' => 'Patterns',
                'dir' => 'patterns',
                'description' => 'CSS-only kit classes (no Blade component): surfaces, menus and overlays.',
                'pages' => [
                    self::gallery('surfaces', 'Surfaces', 'admin-card, admin-card-soft, admin-inset, admin-filter-card and admin-popover.', 'resources/css/admin.css', 'theme/theme.css', 'Surfaces', 'Card with header and body; soft card; inset; filter card default and selected; popover'),
                    self::gallery('menus', 'Menus and popovers', 'Dropdown menus: admin-popover with admin-menu-item, Alpine toggles and Escape.', 'resources/views/layouts/admin.blade.php (account menu)', 'laravel/layouts/admin.blade.php', 'Dropdown', 'Closed and open; menu with a danger item; context choices with the current one checked'),
                    self::gallery('overlays', 'Drawer and bottom sheet', 'admin-drawer and admin-sheet: native dialogs for navigation and phone choices.', 'resources/views/layouts/admin.blade.php', 'laravel/layouts/admin.blade.php', 'Overlays', 'Side drawer; phone bottom sheet; reduced-motion fallbacks'),
                ],
            ],
            [
                'title' => 'Layouts',
                'dir' => 'layouts',
                'description' => 'The four shells, with the app layouts and fictional content.',
                'pages' => [
                    self::layout('tenant', 'tenant', 'Tenant shell', 'Dark brand sidebar, topbar with context switcher, search, notifications and account menu, footer, phone bottom bar.', 'resources/views/layouts/admin.blade.php', 'laravel/layouts/admin.blade.php', 'Back-office shell', 'Owner: full navigation, two organisations, unread notifications; phone bottom bar below 768 px; drawer below 1024 px'),
                    self::layout('tenant-restricted', 'tenant-restricted', 'Tenant shell, restricted member', 'The same shell for a member with few permissions: hidden navigation items, one organisation, no notifications.', 'resources/views/layouts/admin.blade.php', 'laravel/layouts/admin.blade.php', 'Permission-hidden navigation', 'Restricted member: permission-hidden items and groups, single organisation, empty notifications, no bottom bar'),
                    self::layout('tenant-field', 'tenant-field', 'Phone field screen', 'A phone-first screen (check-out, check-in): 44 px controls, sticky action bar, no bottom bar.', 'resources/views/admin/reservations/checkout.blade.php (structure)', 'laravel/layouts/admin.blade.php with @section(\'hide-bottom-bar\')', 'Field screen', '44 px controls and targets; sticky action bar above the home indicator; bottom bar hidden'),
                    self::layout('platform', 'platform', 'Platform shell', 'Super-admin shell: context band, light sidebar, "Plateforme" chip.', 'resources/views/layouts/platform.blade.php', 'laravel/layouts/platform.blade.php', 'Platform shell', 'Platform administrator; light sidebar with the current page; account menu'),
                    self::layout('guest', 'guest', 'Guest shell', 'Sign-in and other guest pages: centred card, flash messages top-end, footer.', 'resources/views/layouts/guest.blade.php', 'laravel/layouts/guest.blade.php', 'Guest shell', 'Sign-in form; flash messages (toasts) top-end'),
                ],
            ],
        ];
    }

    /** @return list<array<string, string>> */
    public static function pages(): array
    {
        return array_merge(...array_map(fn (array $section): array => array_map(
            fn (array $page): array => [...$page, 'section' => $section['title'], 'path' => $section['dir'].'/'.$page['key'].'.html', 'view' => 'kit-export::'.$section['dir'].'.'.$page['key']],
            $section['pages'],
        ), self::sections()));
    }

    /** @return array<string, string> */
    private static function gallery(string $key, string $title, string $description, string $source, string $kit, string $pattern, string $states): array
    {
        return ['key' => $key, 'kind' => 'gallery'] + compact('title', 'description', 'source', 'kit', 'pattern', 'states');
    }

    /** @return array<string, string> */
    private static function layout(string $key, string $kind, string $title, string $description, string $source, string $kit, string $pattern, string $states): array
    {
        return ['key' => $key, 'kind' => $kind] + compact('title', 'description', 'source', 'kit', 'pattern', 'states');
    }
}
