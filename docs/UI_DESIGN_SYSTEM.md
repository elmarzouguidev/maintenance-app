# MaintenanceApp UI Design System

This is a short map of the UI patterns currently present in MaintenanceApp and the Skote theme reference. It is not a copy of the ThemeForest documentation. The source scan was performed on 2026-10-02; treat details as a snapshot and verify the implementation before relying on them.

## Theme and Bootstrap

- Theme identified from the original stylesheet header: **Skote Admin & Dashboard Template 4.3.0**, by Themesbrand.
- Original theme reference: `admin-panel-theme/` (read-only source library; do not link runtime assets to demo files).
- The theme's Bootstrap file, `admin-panel-theme/assets/css/bootstrap.min.css`, declares **5.3.3**.
- The app's Vite stylesheet entry is `resources/css/app.css`, which imports `resources/css/main/css/bootstrap.min.css`; that file declares **5.0.1**. The project intends to use Bootstrap 5.3, but the active imported CSS is older. Check the actual bundled version before using Bootstrap 5.3-only APIs. This version drift was documented, not fixed in this audit.

## How the UI is assembled

- Vite inputs: `resources/css/app.css` and `resources/js/app.js`, configured in `vite.config.mjs`.
- CSS import order: Bootstrap, theme icons, then the app's Skote stylesheet (`resources/css/app.css`).
- Shared application shell: `resources/views/theme/layouts/app.blade.php`.
- Shell structure: theme header, app sidebar, page content, shared modal/footer, Livewire styles/scripts, and `@stack('scripts')`.
- Shared vendor scripts are loaded from `public/assets/libs/` in `resources/views/theme/layouts/_parts/vendor-scripts.blade.php` (jQuery, Bootstrap bundle, and Simplebar). Page-specific CSS and JavaScript are also included by individual views.
- App-specific CSS overrides are at `public/css/custom.css`.
- The application adapts Skote's vertical dark sidebar layout. The header and sidebar are app-specific Blade components/partials, not a direct copy of the demo content.

## Current visual conventions

| Area | MaintenanceApp pattern | Theme reference |
| --- | --- | --- |
| Page shell | `theme.layouts.app`; app header and sidebar | `layouts-colored-sidebar.html`, `layouts-light-sidebar.html`, and `layouts-compact-sidebar.html` |
| Page heading and breadcrumbs | `resources/views/theme/pages/Ticket/section_0_page_title.blade.php` | `pages-starter.html` |
| Forms and validation | Bootstrap grid, `.form-control`, `.is-invalid`, `.invalid-feedback`; ticket forms use Select2 and page scripts | `form-elements.html`, `form-layouts.html`, `form-validation.html` |
| Buttons and actions | Bootstrap `.btn-*` variants with theme effects; existing action meanings are feature-specific | `ui-buttons.html` |
| Cards and content grouping | Bootstrap `.card` / `.card-body` with Skote card typography/shadows | `ui-cards.html` |
| Tables and listings | Bootstrap table classes; DataTables and responsive extensions are loaded on listing pages | `tables-basic.html`, `tables-responsive.html`, `tables-datatable.html` |
| Badges and statuses | Bootstrap contextual badges are present; business status mappings must be taken from existing feature views | `ui-general.html`, `ui-colors.html` |
| Alerts and modals | Bootstrap alert patterns exist in the theme; ticket creation includes a Bootstrap modal | `ui-alerts.html`, `ui-modals.html`; app modal: `resources/views/theme/pages/Ticket/__create/__create_client_modal.blade.php` |
| Icons | Installed theme fonts include Material Design Icons, Boxicons, Font Awesome 5, and Dripicons | `icons-materialdesign.html`, `icons-boxicons.html`, `icons-fontawesome.html`, `icons-dripicons.html` |
| Typography and colors | Poppins is imported by the Skote stylesheet. Skote accent colors in the app stylesheet include primary `#556ee6`, secondary `#74788d`, success `#34c38f`, info `#50a5f1`, warning `#f1b44c`, and danger `#f46a6a`. | `ui-typography.html`, `ui-colors.html` |
| Tabs and accordions | Bootstrap tab patterns are available; feature usage varies | `ui-tabs-accordions.html` |
| Invoices | Invoice list and detail layout examples are available | `invoices-list.html`, `invoices-detail.html` |

### Existing app examples

- Shared layout: `resources/views/theme/layouts/app.blade.php`
- Header: `resources/views/theme/layouts/_parts/__header.blade.php`
- Sidebar entry point: `resources/views/components/sidebar/main-sidebar.blade.php`
- Ticket form: `resources/views/theme/pages/Ticket/__create/form.blade.php`
- Invoice list and table: `resources/views/theme/pages/Commercial/Invoice/index.blade.php`, `resources/views/theme/pages/Commercial/Invoice/__datatable/__invoices_table.blade.php`
- Livewire invoice form fields: `resources/views/theme/livewire/commercial/invoice/create/articles.blade.php`
- Ticket modal: `resources/views/theme/pages/Ticket/__create/__create_client_modal.blade.php`
- App-owned override stylesheet: `public/css/custom.css`

## Integration assessment

- **Integrated:** the main application shell, dark vertical sidebar, header, Bootstrap grid/cards/forms, and many Skote CSS classes are adapted into Blade views.
- **Partial:** the original theme's Bootstrap version and the app's Bootstrap version differ. Theme assets are split between Vite-managed `resources/` files and runtime assets under `public/assets/`; page views also load plugin assets individually.
- **Custom:** navigation content, business forms/tables, ticket and repair flows, Livewire screens, and workflow-specific UI are application-owned adaptations.
- **Not established as app conventions:** Skote demo-only dashboards and unrelated domains (crypto, jobs, ecommerce, projects) are available as references but do not define MaintenanceApp's business UI.

## Inconsistencies to keep visible

These are audit findings only; none were changed here.

1. **Bootstrap version drift:** app CSS imports Bootstrap 5.0.1, while the original Skote CSS is Bootstrap 5.3.3 and the project intends Bootstrap 5.3. A version-sensitive change can therefore behave differently from the theme demo.
2. **Bootstrap 4 pagination view:** permission and role lists render pagination with `vendor.pagination.bootstrap-4` (`resources/views/theme/pages/PermissionRole/Role/_items.blade.php` and `resources/views/theme/pages/PermissionRole/Permission/_items.blade.php`) inside a Bootstrap 5 UI.
3. **Multiple icon families:** Material Design Icons, Boxicons, Font Awesome 5, and Dripicons are all present. New UI should reuse an icon family already used by the closest app feature; avoid adding a fifth library.
4. **Split asset ownership:** the active UI is composed from Vite imports under `resources/`, shared third-party scripts under `public/assets/libs/`, page-level asset declarations, and `public/css/custom.css`. Confirm which layer owns a style or plugin before changing it.

## Reusable theme components

Skote includes reference examples for buttons, form controls/layouts/validation, cards, alerts, modals, basic and responsive tables, DataTables, tabs/accordions, typography, colors, icons, and invoice list/detail pages. See the file map above. Adapt their Bootstrap markup and visual treatment into the established Blade/Livewire structure; do not copy static demo data or scripts without checking app behavior.

## Missing or app-specific patterns

No missing general-purpose UI primitive was confirmed by this source scan. Maintenance-specific states and workflows—such as ticket intake, diagnosis, repair, warranty, estimate/invoice transitions, and technician assignments—are business patterns that need app-specific views; Skote provides generic building blocks, not those workflows. Continue to inspect the existing implementation before creating another variant.
