# Bootstrap → Tailwind CSS v4 migration audit

Read-only audit of `maintenanceapp` (branch `dev`, commit `05bdbbe4`), 2026-11-23.
Sections 0–8 describe the codebase as it was at that commit.

**Status (2026-11-23):**
- Decisions taken: §9.
- Tailwind setup done in `170322f5`: Tailwind v4 installed next to the Bootstrap bundle (see §10, Phase 1).
- Unused Laravel Mix build deleted in `abd36b8e`.
- The view migration follows the batch plan in **§11**.

**Contents**
0. Summary
1. How Bootstrap is loaded
2. Design tokens to port into `@theme`
3. Bootstrap JS components in use
4. Pagination
5. Third-party packages that depend on Bootstrap
6. Inventory of views
7. Custom CSS that will conflict or need rewriting
8. Risks
9. Decisions you need to make (and decisions taken)
10. Proposed migration plan
11. Batch plan: view migration by module
- Appendix A: per-file inventory of pages
- Appendix B: how the numbers were produced

---

## 0. Summary

- **Bootstrap is not installed as a package.** The UI is the **Skote** admin template (by Themesbrand), copied into the repo as compiled files. It consists of a custom **Bootstrap 5.0.1** CSS build, imported through Vite, and the Bootstrap 5.0.1 JS bundle, loaded with a plain `<script>` in `<head>`. There is no npm `bootstrap`, no Bootstrap CDN, no SCSS source and no `laravel/ui` Bootstrap preset.
- **The real dependency is Skote, not only Bootstrap.** Skote's `app.css` (3,666 lines) runs the app shell: topbar, dark sidebar, collapsed mode and preloader. It also re-skins Select2, the datepicker, Dropzone, SweetAlert2 and TinyMCE. Skote added its own classes inside the Bootstrap build too (`card-title-desc`, `bg-soft`, `badge-soft-*`, `table-nowrap`, `pagination-rounded`, `nav-tabs-custom`, `btn-rounded`).
- **Scope.** 478 Blade files are in scope (layouts, partials, components, Livewire views and pages). **361 of them are reachable** from routes, controllers or other reachable views. Those 361 contain about **7,800 Bootstrap class usages** and **1,130 Skote class usages**. Only **320 distinct Bootstrap classes** and **107 distinct Skote classes** are used, so the vocabulary to port is small even though the volume is large. 84 views extend the single layout `theme.layouts.app`.
- **Bootstrap JS is used only through `data-bs-*` attributes:** dropdowns, modals, tabs, collapse/accordion, dismissible alerts and one carousel. The only JS API calls are in the theme bootstrapper (`resources/js/main.js`) and one `shown.bs.tab` listener. All of it maps onto Alpine.js, which Livewire 4 already ships with its Collapse, Focus and Anchor plugins. No new JS dependency is needed.
- **The hardest part is the jQuery plugins, not the markup.** DataTables 1.10.23 runs through its *Bootstrap 4* integration on 19 views. Select2 (28 views) and bootstrap-datepicker (31 views) are styled by Skote. jQuery has to stay until those plugins are re-skinned or replaced.
- **Tailwind v4 shares many class names with Bootstrap but gives them different values.** Examples are `mb-3`, `mb-4`, `p-4`, `gap-3`, `w-100`, `h-100`, `collapse`, `border` and `col-12`. Spacing alone has **1,056 usages** whose size would change. A find-and-replace by class name would silently break layouts (§7.4).
- **The PDF, e-mail and Excel views do not use Bootstrap.** They are rendered by mPDF, mail clients and Laravel Excel, with inline `<style>` blocks, and must stay out of the migration.
- **There is dead weight worth deleting first.** 117 in-scope views (105 page views, 4 layout partials, 4 components, 4 Livewire views) are only referenced from commented-out code or not at all. Several Skote demo modules (Chat, Email inbox, Calendar, Contacts, Settings/emails) have routes but no link in the menu. A demo "Order Details" modal with fake products is included on every page. Deleting this first would cut the work by roughly a quarter.

---

## 1. How Bootstrap is loaded

### 1.1 Sources

| What | Version | Where it lives | How it reaches the page |
|---|---|---|---|
| Bootstrap CSS (Skote's custom build: Skote colours and extra classes) | 5.0.1 | `resources/css/main/css/bootstrap.min.css` (211 KB, committed) | `@import` in `resources/css/app.css`, then `@vite` |
| Skote theme CSS | not versioned | `resources/css/main/css/app.css` (3,666 lines) | same `@import` chain |
| Icon fonts: Boxicons, Material Design Icons 5.8.55, Font Awesome, Dripicons | — | `resources/css/main/css/icons.min.css` + `resources/css/main/fonts/*` | same `@import` chain |
| Bootstrap JS bundle (includes Popper) | 5.0.1 | `public/assets/libs/bootstrap/bootstrap.bundle.min.js` | `<script>` in `theme/layouts/_parts/vendor-scripts.blade.php`, included in the `<head>` of the main layout and of the 3 auth pages |
| jQuery | 3.5.1 | `public/assets/libs/jquery/jquery.min.js` | same partial |
| SimpleBar (sidebar scrollbar) | 4.2.3 | `public/assets/libs/simplebar/` | same partial |
| metisMenu (sidebar accordion) | 3.0.6 | `resources/js/main/metisMenu.min.js` | `import` in `resources/js/app.js` |
| Skote bootstrapper | — | `resources/js/main.js` (163 lines, jQuery) | `import` in `resources/js/app.js` |

Today `resources/css/app.css` contains only:

```css
@import "./main/css/bootstrap.min.css";
@import "./main/css/icons.min.css";
@import "./main/css/app.css";
```

Build chain: Vite 8.3.8, `laravel-vite-plugin` 3.2.1, PostCSS with `autoprefixer` only (`postcss.config.js`). The Vite config file is `vite.config.mjs`, not `vite.config.js`. Node 24.13.

### 1.2 What is *not* there

- `package.json` has no `bootstrap`, `@popperjs/core`, `sass` or `tailwindcss`. `bootstrap-icons` is a dependency, but no view uses `bi-*` classes, so it is unused.
- There is no `resources/sass` and no `.scss` file anywhere. That includes the git-ignored `admin-panel-theme/` folder, which only holds Skote's compiled HTML demos. The design tokens in §2 were therefore read from the compiled CSS.
- There is no Bootstrap CDN. The only CDNs are `uicdn.toast.com/tui.code-snippet/latest` on the Calendar page and Google Fonts (Poppins), pulled by an `@import url()` inside Skote's `app.css`.
- `laravel/ui` 4.6.3 is installed, but only its **auth backend traits** are used: `AuthenticatesUsers`, `SendsPasswordResetEmails` and `ResetsPasswords` in `app/Http/Controllers/Authentification/*`. Its Bootstrap preset is not used. **Keep the package** unless those controllers are rewritten.

### 1.3 Load order in `<head>` (`theme/layouts/app.blade.php`)

1. `assets/libs/magnific-popup/magnific-popup.css`
2. `@include('theme.layouts._parts.vendor-scripts')`: jQuery, the Bootstrap bundle and SimpleBar. These scripts block rendering.
3. `@yield('css')`: per-page plugin CSS (DataTables bs4, Select2, datepicker and so on)
4. `@vite(['resources/css/app.css', 'resources/js/app.js'])`
5. `@livewireStyles`
6. `public/css/custom.css`

At the end of `<body>`: `@livewireScripts` (which brings Alpine) and `@stack('scripts')`.
The body tag is `<body data-sidebar="dark" data-sidebar-size="small-">`. The dark sidebar comes from Skote attribute selectors such as `body[data-sidebar=dark] .vertical-menu`.

The three auth pages (`theme/Authentification/Login/index`, `Password/forgot`, `Password/reset`) are standalone HTML documents. They repeat this `<head>` but **do not include `@livewireScripts`**, so Alpine is not available on them today.

### 1.4 Committed artefacts that nothing uses

- `public/css/app.css` (622 KB): an old Laravel Mix build of Bootstrap 5.0.1 plus the theme. Also `public/js/app.js` with its `.map` and `.LICENSE.txt`, and `public/css/animations.css`. No view references them.
- `resources/js/main/waves.min.js` is never imported, so the **393** `waves-effect` / `waves-light` classes in views do nothing.
- `public/js/pages/dashboard.init.js`, `sweet-alerts.init.js`, `form-advanced.init.js` and `profile_auth.js` are Skote demo scripts that no view includes. `dashboard.init.js` calls `$("#subscribeModal").modal("show")`.

---

## 2. Design tokens to port into `@theme`

There are no SCSS variables, so every value below was extracted from the compiled `bootstrap.min.css` and from Skote's `app.css`.

### 2.1 Colours

| Role | Value | Notes |
|---|---|---|
| primary | `#556ee6` | Same as `--bs-blue`. The most-used colour in the theme. |
| secondary | `#74788d` | Also the `text-muted` colour. |
| success | `#34c38f` | |
| info | `#50a5f1` | |
| warning | `#f1b44c` | |
| danger | `#f46a6a` | |
| light | `#eff2f7` | Also the table, border and `table-light` colour. |
| dark | `#343a40` | |
| pink / purple / indigo / orange | `#e83e8c` / `#6f42c1` / `#564ab1` / `#f1734f` | Rarely used. |
| teal | `#050505` | Skote sets teal to near-black. This looks like a mistake, so don't port it as-is. |
| body text | `#495057` | |
| page background | `#f8f8fb` | |
| card border | `#f6f6f6` | |
| input border | `#ced4da` | On focus: `#b9bfc4`, with **no** focus ring. |
| topbar | `#ffffff`, item text `#555b6d` | |
| footer | `#f2f2f5`, text `#74788d` | |
| dark sidebar | background `#2a3042`, links `#a6b0cf`, icons `#6a7187`, sub-menu links `#79829c`, hover/active `#ffffff`, hover background when collapsed `#2e3548` | |
| "soft" variants | `bg-soft` = the colour at 25% opacity. `badge-soft-*` = the colour at 18% opacity, with the solid colour as text. | In Tailwind: `bg-primary/25`, and `bg-primary/18 text-primary`. |

### 2.2 Typography

- Font: **Poppins** 300/400/500/600/700, loaded from Google Fonts. Monospace uses Bootstrap's default stack.
- **The base size is 13px**: `body { font-size: .8125rem; line-height: 1.5 }`. Buttons, inputs and dropdowns are 13px too. Tailwind's scale assumes a 16px base (`text-sm` is 14px, `text-xs` is 12px), so this needs a decision (D4).
- Headings: weight 500, line-height 1.2, margin-bottom .5rem. Sizes: h1 2.031rem, h2 1.625rem, h3 1.422rem (these three are fluid below 1200px), h4 1.219rem, h5 1.016rem, h6 .8125rem.
- Skote helpers: `font-size-10` to `font-size-24`, in px and with `!important` (**484 usages**). Also `fw-medium` (500), `card-title` (15px / 600) and the page title (`.page-title-box h4`: 16px / 600 / uppercase).

### 2.3 Spacing, radius, shadows, app shell

- Bootstrap spacer scale: 0, .25rem, .5rem, 1rem, 1.5rem, 3rem. The Tailwind equivalents are steps **0, 1, 2, 4, 6, 12**, so values 3, 4 and 5 do not map one-to-one.
- Gutters are 24px (1.5rem): grid gutter, card `margin-bottom`, and page padding of 12px on each side.
- Radius: .25rem by default, .4rem for modals, 30px for `rounded-pill` / `btn-rounded`, and circles for avatars.
- Shadows: cards, topbar and sidebar use `0 .75rem 1.5rem rgba(18,38,63,.03)`. Dropdowns use `0 1rem 3rem rgba(0,0,0,.175)`.
- App shell: topbar 70px high. Sidebar 250px wide, 70px when collapsed. Footer 60px high with `left: 250px`. Page content padding is `calc(70px + 24px) 12px 60px`.
- Avatars: xs 2rem, sm 3rem, md 4.5rem, lg 6rem, xl 7.5rem.

### 2.4 Breakpoints

These are Bootstrap 5's defaults, unchanged: sm 576, md 768, **lg 992**, xl 1200, xxl 1400. Tailwind v4's defaults are 640, 768, 1024, 1280 and 1536. The sidebar toggle in `resources/js/main.js` hard-codes 992. See D3.

### 2.5 Draft `@theme` (proposal only, not applied)

```css
/* resources/css/app.css: draft */
@import url("https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700&display=swap"); /* or self-host: D14 */
@import "tailwindcss";

@source "../../vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php";
@source "../../public/js/pages"; /* page scripts inject class names */

@theme {
    --font-sans: "Poppins", ui-sans-serif, system-ui, sans-serif;

    /* Bootstrap breakpoints (if D3 = keep) */
    --breakpoint-sm: 36rem;    /* 576px  */
    --breakpoint-md: 48rem;    /* 768px  */
    --breakpoint-lg: 62rem;    /* 992px  */
    --breakpoint-xl: 75rem;    /* 1200px */
    --breakpoint-2xl: 87.5rem; /* 1400px */

    /* Brand / semantic colours → bg-primary, text-danger, bg-primary/25 … */
    --color-primary: #556ee6;
    --color-secondary: #74788d;
    --color-success: #34c38f;
    --color-info: #50a5f1;
    --color-warning: #f1b44c;
    --color-danger: #f46a6a;
    --color-light: #eff2f7;
    --color-dark: #343a40;

    --color-body: #495057;
    --color-muted: #74788d;
    --color-page: #f8f8fb;
    --color-line: #eff2f7;
    --color-input: #ced4da;
    --color-topbar-item: #555b6d;
    --color-footer: #f2f2f5;

    --color-sidebar: #2a3042;
    --color-sidebar-hover: #2e3548;
    --color-sidebar-link: #a6b0cf;
    --color-sidebar-icon: #6a7187;
    --color-sidebar-sublink: #79829c;

    /* 1:1 replacement for Skote's font-size-N helpers → text-13, text-16 … */
    --text-10: 10px; --text-11: 11px; --text-12: 12px; --text-13: 13px;
    --text-14: 14px; --text-15: 15px; --text-16: 16px; --text-17: 17px;
    --text-18: 18px; --text-20: 20px; --text-22: 22px; --text-24: 24px;

    --shadow-card: 0 0.75rem 1.5rem rgb(18 38 63 / 0.03);
    --shadow-dropdown: 0 1rem 3rem rgb(0 0 0 / 0.175);
}

:root {
    --topbar-height: 70px;          /* h-(--topbar-height) */
    --sidebar-width: 250px;
    --sidebar-collapsed-width: 70px;
}

@layer base {
    body { font-size: 0.8125rem; line-height: 1.5; color: var(--color-body); background-color: var(--color-page); }
    *, ::before, ::after, ::backdrop { border-color: var(--color-line); } /* v4 default is currentColor */
    h1, h2, h3, h4, h5, h6 { margin-bottom: 0.5rem; font-weight: 500; line-height: 1.2; }
    h1 { font-size: 2.03125rem; } h2 { font-size: 1.625rem; } h3 { font-size: 1.421875rem; }
    h4 { font-size: 1.21875rem; } h5 { font-size: 1.015625rem; } h6 { font-size: 0.8125rem; }
    [x-cloak] { display: none !important; }
}
```

If D1 picks the `tw:` prefix, the `@theme` block stays the same. Only the import changes, to `@import "tailwindcss" prefix(tw);`.

---

## 3. Bootstrap JS components in use

Counts come from a plain text search, so they include markup inside `{{-- --}}` Blade comments.
`†` marks a file that has no static reference (see §6).

### 3.1 Declarative components (`data-bs-*`)

| Component | Usage | Files | Replacement |
|---|---|---|---|
| **Dropdown** | 46 `data-bs-toggle="dropdown"` in 21 files | **Layout:** `theme/layouts/_parts/__header.blade.php` (4 toggles, 1 of them commented out. The mobile "search" dropdown is empty because its form is commented out.)<br>**Filters (period picker, same markup ×9):** `Ticket/__datatable/__filters`, `Ticket/__pret_livre/__datatable/__filters`, `Commercial/BC/__datatable/__filters`, `Commercial/BL/__datatable/__filters`, `Commercial/Bill/__datatable/__filters`, `Commercial/Invoice/__datatable/__filters`†, `Commercial/Invoice/__datatable/__new_filter`, `Commercial/InvoiceAvoir/__datatable/__filters`, `Commercial/Estimate/__datatable/__filter`<br>**Other:** `Report/__datatable/periode_filter`†, `Home/sections/section_a_period` (2)<br>**Skote demo modules:** `Chat/chat` (9), `Email/emails` (3), `Email/read/content` (3), `Setting/emails` (3), `Setting/read/content`† (3), `Excel/__folders`† (6), `Excel/__content`, `Excel/__sidebar`, `Calendar/sections/section_a_calendar` | Alpine `x-data="{ open:false }"` + `x-anchor` + `@click.outside`, as `<x-ui.dropdown>` |
| **Modal** | 31 files with `.modal` markup, 39 `data-bs-toggle="modal"` triggers in 22 files, 47 `data-bs-dismiss="modal"` | **Layout:** `_parts/_modal` (Skote demo "Order Details" with fake products, **included on every page**), `_parts/_subscribe` (its include is commented out)<br>**Commercial:** `{BC,BL,Estimate,Invoice,InvoiceAvoir}/__edit/__print_document`, `{BC,BL}/__datatable/__send_bc`, `Estimate/__datatable/__send_estimate`, `Invoice/__datatable/__send_invoice`, `InvoiceAvoir/__datatable/__send_invoice_avoir`, `Invoice/__datatable/__add_payment`, `Invoice/__datatable/__payment_detail_modal`†, `InvoiceAvoir/__datatable/__payment_detail_modal`, `Invoice/__datatable/__show_invoice_tickets`†, `Bill/__datatable/__add_bill`, `Provider/__edit/__edit_phones`, `Provider/__edit/__edit_emails`<br>**Client:** `__datatable/__import_clients`, `__edit/__add_phones`, `__edit/__edit_emails`<br>**Ticket:** `__create/__create_client_modal`, `__datatable/__settings_modal`, `__pret_livre/__datatable/confirme`, `__pret_livre/__datatable/confirme_admin`<br>**Warranty:** `__datatable/__add_warranty`<br>**Unreachable / demo:** `TicketRapport/__edit/__add_phones`†, `TicketRapport/__edit/__edit_emails`†, `Email/compose/composeModal`, `Setting/compose/composeModal`<br>**Trigger files:** the modal files above, plus the `__datatable/__with_options` table partials, the `__edit/__*_actions` partials, `Client/__edit/form_2`, `Provider/__edit/form_2`, `Ticket/__normal_table/table`†, `Ticket/__create/form`, `Home/sections/section_c_transactions`†, `Email/emails` | `<x-ui.modal name="…">` built on Alpine + `x-trap.noscroll` (focus trap) + `$dispatch('open-modal', name)`. Targets are **class selectors built from record UUIDs** (`data-bs-target=".printInvoice-{{ $invoice->uuid }}"`), so each modal needs a unique name. |
| **Tabs** | 30 `data-bs-toggle="tab"` in 9 files, + 1 `pill` | `components/diagnostic/admin/tab-nav`, `components/diagnostic/tab-nav`†, `Diagnostic/index` (7), `Diagnostic/__tap_view/tables/__taps`† / `__taps_2`†, `Diagnostic/__admin/__tap_view/tables/__taps`† / `__taps_2`†, `Profile/settings/settings` (2), `Chat/chat` (3). Pill: `Ticket/__single_v2/article_detail` | `<x-ui.tabs>` with Alpine. **It must dispatch an event** that replaces `shown.bs.tab` (see 3.2). |
| **Collapse / accordion** | 5 `data-bs-toggle="collapse"` (+ `.accordion` markup) | `Commercial/BC/__edit/__historique_bc`, `Commercial/BL/__edit/__historique_bc`, `Commercial/Estimate/__edit/__estimate_historique`, `Commercial/Invoice/__edit/__historique_invoice`, `Commercial/InvoiceAvoir/__edit/__historique_avoir`. `.accordion` markup without the toggle: `Excel/__sidebar` | Alpine `x-collapse` |
| **Dismissible alert** | 3 `data-bs-dismiss="alert"` | `Excel/__sidebar`, `PermissionRole/Permission/__permissions`, `Admin/__profile/__checkbox_permissions` | `x-data="{ show:true }" x-show="show"` |
| **Carousel** | 1 (`data-bs-ride`, prev/next) | `Ticket/__diagnostic/detail`. (`Ticket/__historical/style_1`† has owl-carousel classes but no JS.) | Small Alpine carousel |
| **Tooltip / Popover / Offcanvas** | initialised globally in `resources/js/main.js` | **No element uses them**: zero `data-bs-toggle="tooltip"`/`"popover"`, zero `.offcanvas` | Drop |
| **Navbar toggler, toast, scrollspy** | none | The sidebar toggle is custom jQuery (`#vertical-menu-btn` toggles `body.sidebar-enable` / `body.vertical-collpsed`) | Alpine store or `x-data` on the shell |
| **Bootstrap 4 attributes (already inert under BS5)** | `data-toggle="tooltip"` ×2, `data-toggle="modal"` ×1 | `Category/__grid/categories_grid`† (tooltips), `Calendar/sections/section_a_calendar` (**its "new event" button opens nothing today**) | Decide per D11 |

### 3.2 Programmatic Bootstrap JS

| Location | Code | Impact |
|---|---|---|
| `resources/js/main.js:133-142` | `new bootstrap.Tooltip(…)`, `new bootstrap.Popover(…)`, `new bootstrap.Offcanvas(…)` | Once the Bootstrap bundle is removed, this throws `ReferenceError: bootstrap is not defined` inside the IIFE. That also kills everything after it in the file: preloader fade-out, password show/hide, `#checkAll`. **Edit `main.js` in the same step that removes the bundle.** |
| `public/js/pages/datatables.init.2.js:10-14` | `$('a[data-bs-toggle="tab"]').on('shown.bs.tab', …)` and `$('.tab-pane.active')` | Recalculates DataTables column widths when a tab opens (Diagnostic pages). The new tabs component must emit an equivalent event. |
| `public/js/pages/dashboard.init.js:2` | `$("#subscribeModal").modal("show")` | The file is not loaded anywhere. Delete it. |

- No Livewire component opens modals from PHP: there is no `dispatch('open-modal')` and no `dispatchBrowserEvent`.
- There is **no Alpine usage at all** (`x-data`) in views today.
- Inline scripts use `Livewire.dispatch(...)` and `Livewire.on('select2', …)` only to keep Select2 in sync. See `Commercial/{Invoice,Estimate}/__create/index`, `Estimate/__create_from_ticket/index` and `InvoiceAvoir/__create_avoir/index`.

### 3.3 Other jQuery behaviour in `resources/js/main.js` that must be re-homed

- metisMenu init (`#side-menu`), plus the sidebar collapse toggle with a 992px breakpoint check.
- Active-menu highlighting: walks parents adding `mm-active`, `mm-show` and `active` after matching `window.location.href`.
- Fullscreen toggle (`[data-toggle="fullscreen"]`) and the preloader fade-out on `window.load` (`#preloader`, `#status`).
- Password toggle (`#password-addon`) and table "check all" (`#checkAll`, `.table-check .form-check-input`).
- **Dead code**: the light/dark/RTL switcher points to `assets/css/bootstrap-dark.min.css`, `app-rtl.min.css` and similar files that don't exist. The language switcher and the right-bar toggle are also dead (the right-bar include is commented out).

---

## 4. Pagination

- `app/Providers/AppServiceProvider.php:38-40` has both `Paginator::defaultView('vendor.pagination.bootstrap')` and `Paginator::useBootstrap()` **commented out**. Laravel's default therefore applies: the **Tailwind** views.
- No `pagination_theme` is set in `config/livewire.php`, and no Livewire component uses `WithPagination`.
- 8 pagination views are published to `resources/views/vendor/pagination/`: Laravel's older set plus a custom `bootstrap.blade.php`. 4 Livewire pagination views are published to `resources/views/vendor/livewire/`.
  - `vendor/pagination/bootstrap.blade.php` is a Livewire 2 era view (`$this->numberOfPaginatorsRendered`). Nothing uses it.
  - `bootstrap-4.blade.php` has been customised (it differs from the framework copy).
  - Laravel 13 renamed the framework's `default` view to `bootstrap-3`, so the published `default.blade.php` / `simple-default.blade.php` are stale.

Where pagination links are rendered:

| View | Call | What happens today |
|---|---|---|
| `theme/pages/PermissionRole/Permission/_items.blade.php:56` | `$permissions->links('vendor.pagination.bootstrap-4')` | Bootstrap-styled |
| `theme/pages/PermissionRole/Role/_items.blade.php:56` | `$roles->links('vendor.pagination.bootstrap-4')` | Bootstrap-styled |
| `theme/pages/Ticket/__normal_table/table.blade.php:47` | `$tickets->links('vendor.pagination.bootstrap-4')` | **Not rendered.** Its include in `Ticket/index` is commented out, and so is the `paginate(20)` in `TicketController::old()`. |
| `theme/pages/Commercial/Invoice/__datatable/__with_options.blade.php:169` | `$invoices->onEachSide(5)->links()` on a `simplePaginate(30)` | Renders the published **`simple-tailwind`** view, but no Tailwind CSS is compiled, so **the invoice pager is probably unstyled today**. It will start looking right after the migration. |

**Migration impact:**
- Restyle the published `tailwind.blade.php` / `simple-tailwind.blade.php` with the theme tokens.
- Point the 2 live `bootstrap-4` calls at them (or drop the argument).
- Add the framework pagination views to `@source`.
- The published Tailwind views use v2/v3-era classes: `focus:ring` (1px in v4 instead of 3px) and `focus:outline-none` (behaviour changed in v4). Republish them from Laravel 13 or fix them by hand.

Observed, outside the CSS scope (please verify): `BCommandController`, `BLController` and `EstimateController` call `paginate(100)` / `paginate(100)` / `paginate(600)`, but their views never render `links()`, so rows beyond the first page cannot be reached.

---

## 5. Third-party packages that depend on Bootstrap

Every front-end library is **vendored** in `public/assets/libs/` (497 committed files) and loaded with `asset()`. None of them is managed by npm.

| Library | Version | Used in | Bootstrap coupling | Suggestion |
|---|---|---|---|---|
| **Bootstrap bundle** (incl. Popper) | 5.0.1 | every page + auth pages | — | Remove at the end (Phase 6) |
| **jQuery** | 3.5.1 | every page | Needed by every plugin below and by `main.js` | Keep until the plugins are replaced |
| **DataTables** core + **`datatables.net-bs4`**, **`buttons-bs4`**, **`responsive-bs4`** (+ Buttons, JSZip, pdfmake) | 1.10.23 (EOL) | **19 views** (all `__datatable/index` pages, `components/diagnostic/assets`), via `js/pages/datatables.init*.js` | **Hard.** The *bs4* integration generates Bootstrap 4 markup (`row`, `col-md-6`, `pagination`/`page-item`/`page-link`, `btn btn-secondary`, `form-control-sm`, `custom-select`), and its CSS assumes Bootstrap is present. The init scripts add `btn-primary` and `form-select form-select-sm` from JS. | Decision D7 |
| **Select2** | 4.0.13 | **28 views** (filters, create/edit forms) | Medium. Select2's own CSS is standalone, but **43 Skote rules** re-skin it to look like `.form-control`. There is no Bootstrap theme option (`theme:` is not set). One page overrides `.select2-selection__choice` inline (`Invoice/__edit/index`). | Re-skin in `@layer components` (or D8) |
| **bootstrap-datepicker** | 1.9.0 | **31 views**, incl. `.input-daterange` + `.input-group` in all filter partials | Medium. Its default CSS mimics a BS dropdown, Skote adds 21 rules, and the markup relies on `.input-group`. | D8: native `<input type="date">`, flatpickr, or restyle |
| **SweetAlert2** | 10.10.4 | 10 views | Low. Standalone CSS, and colours are passed as hex (`confirmButtonColor: "#34c38f"`). Skote adds 12 rules. No Bootstrap theme package. | Keep |
| **TinyMCE** | 5.3.2 (EOL) | 8 views (`js/pages/ticket-create.init.js`) | None (iframe + own skin). Skote adds 14 `tox` rules. | Keep |
| **jquery.repeater** | 1.2.1 | 15 views | None (markup-agnostic) | Keep |
| **ApexCharts** | 3.22.2 | Dashboard (`theme/layouts/_helpers/apexchart/*`, `Home`) | None. `custom.css` tweaks the axis labels. | Keep |
| **Magnific Popup** | 1.1.0 | CSS on every page, JS in 2 views | None | Keep |
| **Dropzone** | 5.7.2 | 2 views | Low. Skote has a "Dropzone" section. | Re-skin |
| **TOAST UI Calendar** (+ tui-date/time-picker, `tui-code-snippet@latest` from CDN), **FullCalendar** (`public/js/fullcalendar.js`), moment, chance | 1.12.13 | `Calendar` only (routed, not in the menu) | Low | Depends on D11 |
| **SimpleBar** | 4.2.3 | sidebar (`data-simplebar`) | Skote has 29 rules | Replace with `overflow-y-auto` (D10) |
| **metisMenu** | 3.0.6 | sidebar | `mm-*` classes, CSS in Skote | Replace with Alpine (D10) |
| **Waves** | — | not loaded | 393 dead classes | Delete the classes |
| `bootstrap-icons` (npm) | ^1.5 | unused | — | Remove from `package.json` |
| `laravel/ui` (composer) | 4.6.3 | auth backend traits only | none for the UI | Keep |
| **Livewire** | 4.4.9 | 13 components (`app/Http/Livewire`) | Bundles **Alpine + every official plugin except `@alpinejs/ui`** (Collapse, Focus, Anchor, Intersect, Persist, Mask, Sort…) | Use Alpine for all Bootstrap JS replacements |

There is no Select2 Bootstrap theme, no SweetAlert2 Bootstrap theme and no toastr. The only Bootstrap-integration package is the DataTables bs4 trio.

---

## 6. Inventory of views

542 Blade files in total: 507 own views and 35 published vendor views.

- "BS classes" = class tokens defined by the compiled Skote/Bootstrap build. That includes Skote's in-build additions such as `card-title-desc` and `bg-soft`.
- "Skote classes" = tokens defined only by Skote's `app.css`. That includes the dead `waves-*` classes and the `font-size-*` helpers.
- "BS4 leftovers" = Bootstrap 4 names that do nothing under BS5 (`text-right`, `ml-3`, `badge-pill`, `btn-block`…).
- "Reachable": ✓ = statically referenced from a route, controller, Livewire class, mailable or another reachable view (following `@include`, `@extends`, `<x-…>`, `view()` and the custom `@includePart()` directive). References inside `{{-- --}}` Blade comments and PHP comments are ignored. "—" = no live static reference found. **Verify before deleting**, because a dynamically built view name would be missed.

### 6.1 Totals by group

| Group | Files | Unreachable | BS classes | Skote classes | In migration scope |
|---|---:|---:|---:|---:|---|
| Layouts (root documents) | 5 | 0 | 152 | 23 | Yes |
| Layout partials | 14 | 4 | 179 | 72 | Yes |
| Blade components | 23 | 4 | 121 | 30 | Yes |
| Livewire views | 17 | 4 | 412 | 22 | Yes |
| Pages + page partials | 419 | 105 | 9908 | 1373 | Yes |
| PDF / e-mail / Excel views | 18 | 6 | 0 | 0 | **No** (mPDF / mail clients / Excel) |
| Error pages + welcome | 11 | 1 | 43 | 7 | Optional |
| Published vendor views | 35 | 0 | 264 | 6 | Pagination only |

The heaviest groups (Commercial documents and Ticket) repeat the same building blocks: page title, filter card, DataTable card with an actions column, create/edit form with validation, and actions dropdown/modals. Once those blocks exist as components, most page migrations become mechanical.

### 6.2 Layouts (root documents)

| File | Lines | BS classes | Skote classes | BS4 leftovers | JS components / plugins | Reachable |
|---|---:|---:|---:|---:|---|:---:|
| `theme/Authentification/Login/index.blade.php` | 143 | 61 | 11 |  |  | ✓ |
| `theme/Authentification/Password/forgot.blade.php` | 94 | 40 | 5 |  |  | ✓ |
| `theme/Authentification/Password/reset.blade.php` | 110 | 47 | 5 |  |  | ✓ |
| `theme/Authentification/auth_footer.blade.php` | 16 | 4 | 0 |  |  | ✓ |
| `theme/layouts/app.blade.php` | 87 | 0 | 2 |  |  | ✓ |

### 6.3 Layout partials (`theme/layouts/_parts`, `theme/layouts/_helpers`)

| File | Lines | BS classes | Skote classes | BS4 leftovers | JS components / plugins | Reachable |
|---|---:|---:|---:|---:|---|:---:|
| `theme/layouts/_helpers/apexchart/LineChart.blade.php` | 107 | 0 | 0 |  | ApexCharts | ✓ |
| `theme/layouts/_helpers/apexchart/__apex_chart.blade.php` | 8 | 0 | 0 |  |  | ✓ |
| `theme/layouts/_parts/__header.blade.php` | 193 | 59 | 31 |  | dropdown | ✓ |
| `theme/layouts/_parts/__loader.blade.php` | 13 | 0 | 7 |  |  | ✓ |
| `theme/layouts/_parts/__messages.blade.php` | 16 | 6 | 0 |  |  | ✓ |
| `theme/layouts/_parts/_footer.blade.php` | 24 | 7 | 1 |  |  | ✓ |
| `theme/layouts/_parts/_leftSidebar.blade.php` | 16 | 0 | 0 |  |  | ✓ |
| `theme/layouts/_parts/_leftSidebar_commercial.blade.php` | 149 | 6 | 23 |  | simplebar, metisMenu | — |
| `theme/layouts/_parts/_modal.blade.php` | 83 | 29 | 4 | 3 | modal | ✓ |
| `theme/layouts/_parts/_navbar.blade.php` | 1 | 0 | 0 |  |  | — |
| `theme/layouts/_parts/_overly.blade.php` | 1 | 0 | 1 |  |  | ✓ |
| `theme/layouts/_parts/_rightSidebar.blade.php` | 56 | 40 | 3 |  | simplebar | — |
| `theme/layouts/_parts/_subscribe.blade.php` | 35 | 32 | 2 |  | modal | — |
| `theme/layouts/_parts/vendor-scripts.blade.php` | 4 | 0 | 0 |  |  | ✓ |

### 6.4 Blade components (`resources/views/components`)

The 10 `components/sidebar/*` files are the sidebar. They behave like layout partials and are migrated with the layout (Phase 2). `components/forms/input.blade.php` is an **empty stub** (`App\View\Components\Forms\Input` renders a placeholder comment), which makes it a natural home for the new form field component.

| File | Lines | BS classes | Skote classes | BS4 leftovers | JS components / plugins | Reachable |
|---|---:|---:|---:|---:|---|:---:|
| `components/diagnostic/admin/diagnostic-layout.blade.php` | 22 | 6 | 0 |  |  | ✓ |
| `components/diagnostic/admin/filters.blade.php` | 90 | 32 | 0 |  | Select2, datepicker | ✓ |
| `components/diagnostic/admin/js-filters.blade.php` | 72 | 0 | 0 |  | Select2 | ✓ |
| `components/diagnostic/admin/tab-content.blade.php` | 46 | 4 | 0 |  |  | ✓ |
| `components/diagnostic/admin/tab-nav.blade.php` | 48 | 9 | 0 |  | tabs | ✓ |
| `components/diagnostic/admin/ticket-table.blade.php` | 70 | 12 | 2 |  |  | ✓ |
| `components/diagnostic/assets.blade.php` | 27 | 0 | 0 |  | DataTables | ✓ |
| `components/diagnostic/diagnostic-layout.blade.php` | 18 | 5 | 0 |  |  | — |
| `components/diagnostic/tab-content.blade.php` | 86 | 4 | 0 |  |  | — |
| `components/diagnostic/tab-nav.blade.php` | 76 | 9 | 0 |  | tabs | — |
| `components/diagnostic/ticket-table.blade.php` | 78 | 14 | 2 |  |  | — |
| `components/forms/input.blade.php` | 3 | 0 | 0 |  |  | ✓ |
| `components/forms/loading.blade.php` | 10 | 0 | 0 |  |  | ✓ |
| `components/sidebar/admin-menu.blade.php` | 14 | 4 | 1 |  |  | ✓ |
| `components/sidebar/commercial-menu.blade.php` | 93 | 0 | 4 |  |  | ✓ |
| `components/sidebar/dashboard-item.blade.php` | 10 | 0 | 1 |  |  | ✓ |
| `components/sidebar/developer-menu.blade.php` | 21 | 0 | 2 |  |  | ✓ |
| `components/sidebar/main-sidebar.blade.php` | 60 | 2 | 3 |  | simplebar, metisMenu | ✓ |
| `components/sidebar/reception-menu.blade.php` | 20 | 4 | 1 |  |  | ✓ |
| `components/sidebar/reports-menu.blade.php` | 25 | 0 | 3 |  |  | ✓ |
| `components/sidebar/settings-menu.blade.php` | 18 | 0 | 3 |  |  | ✓ |
| `components/sidebar/technician-menu.blade.php` | 22 | 4 | 3 |  |  | ✓ |
| `components/sidebar/ticket-menu.blade.php` | 34 | 12 | 5 |  |  | ✓ |

### 6.5 Livewire views (`theme/livewire`)

| File | Lines | BS classes | Skote classes | BS4 leftovers | JS components / plugins | Reachable |
|---|---:|---:|---:|---:|---|:---:|
| `theme/livewire/application/importer/c-s-v-file-importer.blade.php` | 43 | 12 | 0 | 1 |  | ✓ |
| `theme/livewire/commercial/avoir/create/info.blade.php` | 77 | 24 | 0 |  |  | ✓ |
| `theme/livewire/commercial/b-l/b-l-info.blade.php` | 65 | 20 | 0 |  |  | ✓ |
| `theme/livewire/commercial/bon-command/info.blade.php` | 66 | 20 | 0 |  |  | ✓ |
| `theme/livewire/commercial/estimate/create/from-ticket.blade.php` | 76 | 26 | 0 |  | Select2 | ✓ |
| `theme/livewire/commercial/estimate/create/info.blade.php` | 89 | 20 | 0 |  | Select2 | — |
| `theme/livewire/commercial/estimate/create/new_info.blade.php` | 87 | 20 | 0 |  | Select2 | ✓ |
| `theme/livewire/commercial/estimate/create/tickets.blade.php` | 24 | 6 | 0 |  | Select2 | ✓ |
| `theme/livewire/commercial/estimate/edit/edit-article.blade.php` | 79 | 36 | 6 |  |  | ✓ |
| `theme/livewire/commercial/invoice-avoir/edit/edit-article.blade.php` | 71 | 31 | 6 |  |  | ✓ |
| `theme/livewire/commercial/invoice/avoir/info.blade.php` | 78 | 26 | 0 |  |  | — |
| `theme/livewire/commercial/invoice/create/articles.blade.php` | 87 | 44 | 0 |  |  | — |
| `theme/livewire/commercial/invoice/create/info.blade.php` | 100 | 26 | 0 |  | Select2 | — |
| `theme/livewire/commercial/invoice/create/info2.blade.php` | 64 | 20 | 0 |  |  | ✓ |
| `theme/livewire/commercial/invoice/create/tickets.blade.php` | 20 | 6 | 0 |  | Select2 | ✓ |
| `theme/livewire/commercial/invoice/edit/edit-article.blade.php` | 78 | 36 | 6 |  |  | ✓ |
| `theme/livewire/dasboard/dashboard.blade.php` | 76 | 39 | 4 |  | datepicker | ✓ |

### 6.6 Pages by module (`theme/pages`)

| Module | Files | Unreachable | Lines | BS classes | Skote classes | BS4 leftovers |
|---|---:|---:|---:|---:|---:|---:|
| Admin | 14 | 4 | 715 | 317 | 38 | 0 |
| Backup | 3 | 3 | 154 | 53 | 2 | 3 |
| Calendar | 5 | 2 | 168 | 66 | 2 | 0 |
| Category | 6 | 2 | 200 | 109 | 11 | 0 |
| Chat | 3 | 0 | 638 | 403 | 109 | 1 |
| Client | 16 | 3 | 1187 | 364 | 29 | 0 |
| Commercial/BC | 24 | 1 | 1598 | 468 | 56 | 0 |
| Commercial/BL | 24 | 3 | 1610 | 473 | 56 | 0 |
| Commercial/Bill | 24 | 5 | 1626 | 459 | 29 | 0 |
| Commercial/Company | 9 | 3 | 879 | 295 | 9 | 0 |
| Commercial/Estimate | 32 | 5 | 2323 | 668 | 66 | 0 |
| Commercial/Invoice | 44 | 8 | 2944 | 913 | 66 | 0 |
| Commercial/InvoiceAvoir | 27 | 4 | 1871 | 518 | 59 | 0 |
| Commercial/Provider | 11 | 1 | 709 | 216 | 17 | 0 |
| Contact | 7 | 3 | 472 | 313 | 85 | 0 |
| Diagnostic | 29 | 18 | 1614 | 532 | 60 | 5 |
| Email | 6 | 0 | 581 | 238 | 220 | 1 |
| Excel | 6 | 1 | 646 | 478 | 58 | 2 |
| FileManager | 1 | 1 | 1 | 0 | 0 | 0 |
| Home | 14 | 7 | 933 | 428 | 94 | 0 |
| Importer | 3 | 1 | 88 | 33 | 2 | 0 |
| PermissionRole | 6 | 0 | 327 | 140 | 12 | 0 |
| Profile | 7 | 0 | 517 | 248 | 37 | 0 |
| Reparation | 9 | 4 | 360 | 174 | 16 | 5 |
| Report | 5 | 1 | 227 | 78 | 7 | 0 |
| Setting | 7 | 1 | 367 | 240 | 70 | 3 |
| Ticket | 54 | 14 | 4312 | 1230 | 114 | 10 |
| TicketRapport | 18 | 9 | 1231 | 377 | 42 | 0 |
| Warranty | 5 | 1 | 287 | 77 | 7 | 0 |
| **Total** | **419** | **105** | **28585** | **9908** | **1373** | **30** |

The full per-file list is in **Appendix A**.

### 6.7 The 25 heaviest reachable files

| File | BS | Skote | Lines |
|---|---:|---:|---:|
| `theme/pages/Chat/chat.blade.php` | 391 | 107 | 608 |
| `theme/pages/Email/emails.blade.php` | 141 | 199 | 413 |
| `theme/pages/Contact/__list/section_a_contacts.blade.php` | 189 | 54 | 311 |
| `theme/pages/Profile/profile.blade.php` | 133 | 33 | 285 |
| `theme/pages/Excel/__rightbar.blade.php` | 117 | 18 | 127 |
| `theme/pages/Commercial/Company/__edit/form_2.blade.php` | 130 | 2 | 333 |
| `theme/pages/Client/__profile/profile.blade.php` | 83 | 15 | 245 |
| `theme/pages/Commercial/Estimate/__edit/__form_edit.blade.php` | 96 | 2 | 227 |
| `theme/pages/Commercial/Invoice/__edit/__form_edit.blade.php` | 93 | 4 | 245 |
| `theme/pages/Setting/emails.blade.php` | 65 | 27 | 96 |
| `theme/layouts/_parts/__header.blade.php` | 59 | 31 | 193 |
| `theme/pages/Commercial/InvoiceAvoir/__edit/__form_edit.blade.php` | 81 | 4 | 189 |
| `theme/pages/Commercial/Invoice/__create_from_estimate/__form_create.blade.php` | 80 | 4 | 175 |
| `theme/pages/Commercial/Estimate/__create_from_ticket/__form_create.blade.php` | 79 | 4 | 161 |
| `theme/pages/Email/read/content.blade.php` | 64 | 19 | 87 |
| `theme/pages/TicketRapport/Edition/Edit/__form_edit.blade.php` | 78 | 4 | 152 |
| `theme/pages/Commercial/Estimate/__create/__form_create.blade.php` | 77 | 4 | 172 |
| `theme/pages/Ticket/__diagnostic/detail.blade.php` | 76 | 3 | 242 |
| `theme/pages/Diagnostic/index.blade.php` | 76 | 0 | 157 |
| `theme/Authentification/Login/index.blade.php` | 61 | 11 | 143 |
| `theme/pages/Commercial/Invoice/__create/__form_create.blade.php` | 69 | 2 | 167 |
| `theme/pages/Commercial/InvoiceAvoir/__create_avoir/__form_create.blade.php` | 65 | 4 | 161 |
| `theme/pages/Commercial/BL/__create/__form_create.blade.php` | 63 | 4 | 135 |
| `theme/pages/Commercial/BL/__edit/__form_edit.blade.php` | 63 | 4 | 146 |
| `theme/pages/Commercial/BC/__edit/__form_edit.blade.php` | 60 | 4 | 134 |

### 6.8 Out of scope: do not migrate

These views have no Bootstrap dependency. They are rendered by **mPDF** (`app/Support/PdfGenerator.php`), mail clients or Laravel Excel, and carry their own inline `<style>`. mPDF cannot use Tailwind v4 output (cascade layers, CSS variables, `oklch()`, `color-mix()`), so **keep them as they are**. They can optionally be excluded from Tailwind scanning with `@source not`.

- `__pdf_templates/a.blade.php`
- `__pdf_templates/b.blade.php`
- `__pdf_templates/c.blade.php`
- `exports/clients.blade.php`
- `theme/Emails/Commercial/BC/SendBCMail.blade.php`
- `theme/Emails/Commercial/Estimate/DeletedEstimateMail.blade.php`
- `theme/Emails/Commercial/Estimate/SendEstimateMail.blade.php`
- `theme/Emails/Commercial/Invoice/SendInvoiceMail.blade.php`
- `theme/Emails/Commercial/InvoiceAvoir/SendInvoiceAvoirMail.blade.php`
- `theme/Emails/mail_template.blade.php`
- `theme/bons_template/template1/bl.blade.php`
- `theme/bons_template/template1/index.blade.php`
- `theme/estimates_template/template1/index.blade.php`
- `theme/estimates_template/template2/index.blade.php`
- `theme/invoices_template/avoirs/index.blade.php`
- `theme/invoices_template/template1/index.blade.php`
- `theme/pages/Ticket/__pdf/Report/index.blade.php`
- `theme/pdf/partials/footer.blade.php`

Also out of scope:
- `resources/views/vendor/*` other than pagination (`mail`, `notifications`, `honeypot`, `media-library`, `invoices`).
- `errors/*`: Laravel's own error layouts, which already use inline utility CSS.
- `welcome.blade.php` (unreachable).

---

## 7. Custom CSS that will conflict or need rewriting

### 7.1 Skote `resources/css/main/css/app.css` (3,666 lines)

This is a compiled theme, not authored code. Only **107 of its 446 classes** are used in views. What it contains:

| Area | What to do |
|---|---|
| App shell: `#page-topbar`, `.navbar-header`, `.navbar-brand-box`, `.vertical-menu`, `#sidebar-menu`, `.main-content`, `.page-content`, `.footer`, `.vertical-collpsed` (52 rules), `body[data-sidebar=dark]` (63), `body[data-sidebar-size]` (15) | **Rewrite** as the new layout (Phase 2). Values are in §2.3. |
| Horizontal, boxed, scrollable and colored-sidebar layouts (`[data-layout]` 38 rules, `[data-topbar]` 33), RTL support, right-bar, megamenu, app-search | **Drop.** Unused. |
| Helpers: `font-size-*`, `avatar-*`, `avatar-title`, `fw-medium`, `w-sm`/`w-md`, `mini-stats-wid`, `mini-stat-icon`, `page-title-box`, `button-items`, `spinner-chase` (preloader), `verti-timeline`, `event-list`, `contact-links` | **Port** as tokens or utilities (`text-16`, `size-8`, `@utility`) or fold into components |
| Plugin skins: Select2 (43 rules), bootstrap-datepicker (21), SimpleBar (29), SweetAlert2 (12), TinyMCE `tox` (14), tui (12), ApexCharts (9), metisMenu, Dropzone, ion-rangeslider, flot, timepicker, "CSS switch" | **Port only what's used** (Select2, datepicker, SweetAlert2, Dropzone, ApexCharts) into `@layer components`. Drop the rest. |
| Pages: chat, email, file manager, kanban, timeline, auth (`account-pages`, `auth-logo`, `profile-user-wid`) | Port only for the modules you keep (D11) |
| `@import url(Google Fonts Poppins)` | Move to the top of the new `app.css`, or self-host (D14) |

### 7.2 Skote additions inside `bootstrap.min.css`

`.card` (margin-bottom 24px + card shadow), `.card-title` (15px/600), `.card-title-desc`, `.bg-soft` (+ `.bg-*.bg-soft`), `.badge-soft-*`, `.table-nowrap`, `.pagination-rounded`, `.nav-tabs-custom`, `.btn-rounded`, `.dropdown-menu` animation (`DropDownSlide`) and shadow.
These disappear with `bootstrap.min.css`, so they have to be recreated in the component kit.

### 7.3 Other custom CSS

| Where | Content | Action |
|---|---|---|
| `public/css/custom.css` (20 lines, loaded last in `<head>`) | `input[type=search]` 19px font + blue solid border; `.dataTables_filter label` 20px; ApexCharts axis label fill | Fold into the DataTables / ApexCharts skins |
| **9 filter partials** (listed in §3.1; 8 reachable), each with an identical `<style>` block | `.dropdown-item.active { background:#556ee6 !important; color:white !important }` and `.dropdown-item:hover` | Replace with one shared filter component (Phase 4) |
| `Commercial/{BC,BL,Bill,Estimate,Invoice,InvoiceAvoir}/__detail/index` | `<style media="print">` with `@page` margins and `body { padding-top: 720px }` | Keep: these are **browser print** rules for document previews. Re-express with `print:` utilities or keep them as a `<style>` block. Test print preview. |
| `Commercial/Invoice/__edit/index`, `Commercial/Estimate/__edit/index` | `.select2-selection__choice` override (red background, blue text, 16px) | Move to the Select2 skin, or remove if accidental |
| `Ticket/__edit/__reassignment` | `#reassignment… { display:block !important; visibility:visible !important }` (a workaround against something hiding the form) | Find the root cause. It may be Bootstrap `.collapse`/`.fade` or TinyMCE. |
| 169 inline `style="…"` attributes in browser views | Mostly `min-width` on filter columns, badge font sizes, blue footer links | Convert to utilities case by case |

### 7.4 Class-name collisions: same name, different meaning in Tailwind v4

Tailwind v4 generates any utility it finds in the scanned files, including Bootstrap class names that happen to be valid Tailwind utilities. The danger is twofold:
- It makes a blind find-and-replace wrong.
- While both frameworks are loaded, these classes produce CSS from both sides.

| Class (usages) | Bootstrap 5 | Tailwind v4 | Consequence |
|---|---|---|---|
| `m*-3`, `p*-3`, `gap-3` and the 4 / 5 variants, incl. responsive (**1,056**, e.g. `mb-4` ×393, `mb-3` ×329, `me-3` ×95) | 1rem / 1.5rem / 3rem | 0.75rem / 1rem / 1.25rem | Everything gets tighter. Convert 3→4, 4→6, 5→12. Steps 0/1/2 (845 usages) are identical. |
| `w-100` (46), `h-100` (4), `w-25/50/75` | 100% / 25% … | 25rem / 6.25rem … (v4 spacing is dynamic) | Elements shrink or overflow. Use `w-full`, `h-full`, `w-1/4` … |
| `collapse` (5) | `display:none` unless `.show` | `visibility: collapse` | **The accordion body stays invisible even when opened** as soon as Tailwind loads, because both rules apply. |
| `border` (59), `border-0`, `border-danger` … | 1px solid `#eff2f7` | 1px solid **currentColor** | Borders turn dark unless the base layer sets `border-color` (§2.5) |
| `col-12` (87), `col-6` (12), `col-7`/`col-5` (8 each) … | flex column width | `grid-column: N` | Harmless in a flex row, but misleading. Convert to `col-span-*`. |
| `container` (4, auth pages) | centred, padded, fixed max-widths | `width:100%` + max-widths, **not centred, no padding** | Add `mx-auto px-3` |
| `table` (71) | styled table | `display: table` | Harmless, but the table styling has to come from components |
| `shadow`, `shadow-sm`, `rounded` | Skote values | Tailwind values (and v4 renamed `shadow`→`shadow-sm`, `rounded`→`rounded-sm`) | Visual drift |
| `text-primary`, `bg-danger` … | Skote colours | Skote colours once `--color-*` is in `@theme` | Same meaning. Safe to keep these names. |
| `ms-*`, `me-*`, `ps-*`, `pe-*`, `float-end`, `text-end`, `align-middle`, `overflow-hidden`, `flex-wrap`, `order-*`, `opacity-*` | logical/simple | same property in v4 | Same meaning, but spacing values 3/4/5 still differ (row 1) |

Dormant classes that **will suddenly start applying** once Tailwind loads. These are Bootstrap 4 names that do nothing today but are valid Tailwind utilities:

- `mr-auto` ×10: `Reparation/__list/reparation`†, `Reparation/__single/section_b`, `Ticket/__diagnostic/detail`
- `text-right` ×4: `_parts/_modal`, `Backup/section_a_backups`†
- `mr-2` ×2, `mr-4`, `pr-3`, `text-left`: `Backup/section_a_backups`†, `Excel/__content`, `livewire/application/importer/c-s-v-file-importer`, `Ticket/__single_v2/article_detail`

The other BS4 leftovers (`badge-pill`, `btn-block`, `form-group`, `custom-accordion`, `sr-only` …) have no or the same meaning in Tailwind.

### 7.5 Dynamic class names (Tailwind cannot see them)

- `text-{{ $color }}` with `$color` in `danger|success|warning|info`: `Ticket/__datatable/__with_options`, `Ticket/__normal_table/table`†, `Ticket/__pret_livre/__datatable/__with_options`, `Ticket/__invoiceable/__datatable/__with_options`†, `Ticket/__invoiceable/__datatable/__with_options2`. Map them to full class names (`'danger' => 'text-danger'`). Alternatively add `@source inline("text-{danger,success,warning,info}")`.
- PHP arrays with full names (`'badge_class' => 'bg-info'`) in `components/diagnostic/*/tab-nav` are fine. Tailwind scans the file as text.
- No PHP class (controller, model, enum, helper) builds CSS class names.

---

## 8. Risks

| # | Risk | Severity | Mitigation |
|---|---|---|---|
| R1 | **Volume.** 361 reachable files, ~7,800 Bootstrap + ~1,130 Skote class usages, 1,486 grid classes, 482 `form-control`, 428 `is-invalid`. | High | Build a component kit first (Phase 3–4), then migrate module by module |
| R2 | **The theme and Bootstrap are fused.** Removing `bootstrap.min.css` also removes Skote's in-build additions (§7.2). Removing Skote's `app.css` removes the shell and every plugin skin. | High | Port the tokens (§2) and the plugin skins before deleting anything |
| R3 | **jQuery plugins render Bootstrap markup.** DataTables bs4 on 19 views, Select2 on 28, datepicker on 31. DataTables 1.10 and TinyMCE 5 are end-of-life. | High | D7, D8. Skin these plugins before migrating the pages that use them. |
| R4 | **Silent collisions** (§7.4): spacing 3/4/5, `w-100`, `collapse`, border colour, dormant BS4 classes | High | Mapping table (§10.2), no blind search/replace, coexistence strategy D1 |
| R5 | **Cascade layers.** v4 puts utilities in `@layer utilities`, so **any unlayered CSS beats them**, whatever the specificity or order. That covers Bootstrap, Skote, plugin CSS, `custom.css` and inline `<style>`. Bootstrap utilities also use `!important`, which beats Tailwind even from a lower layer. | High | Migrate an element completely (never mix BS and TW classes on one element). Keep the legacy CSS off migrated pages (D1 a) or import it into a named lower layer. |
| R6 | **Preflight side effects** if Preflight is loaded next to Bootstrap: `img { display:block }`, unstyled lists and headings, `button { cursor: default }` in v4 | Medium | Don't load Preflight on pages that still load Bootstrap (D1) |
| R7 | **Browser support.** Tailwind v4 needs Safari 16.4+, Chrome 111+ and Firefox 128+. Reception and workshop PCs may be older. | Medium–High | Confirm the browser baseline before starting (D12) |
| R8 | **`main.js` depends on the global `bootstrap`** (§3.2): removing the bundle first breaks the preloader, password toggle and check-all | Medium | Change `main.js` in the same step |
| R9 | **Livewire DOM morphing vs Alpine/jQuery state.** Select2 already needs `wire:ignore` + `Livewire.on('select2')` re-init. New Alpine dropdowns/modals inside Livewire views must survive re-renders (`wire:ignore.self`, `x-data` on stable roots). | Medium | Test every Livewire screen (13 components) after migrating it |
| R10 | **No UI test safety net.** `tests/Feature` holds only `ExampleTest.php`. | Medium | Add route smoke tests and baseline screenshots before starting (Phase 0) |
| R11 | **The 13px base** vs Tailwind's 16px-based scale, and different breakpoints (992 vs 1024) | Medium | D3, D4. Lock the tokens before any page work. |
| R12 | **Modal triggers that point nowhere.** For example, the invoice list's PDF icon targets `.printInvoice-{uuid}`, but that modal is only rendered on the edit page. | Low | Inventory each trigger when converting to named Alpine modals |
| R13 | **Print layouts** (`__detail` pages with `@media print`) | Low | Check print preview per document type |
| R14 | **Dead code inflates the effort**: 117 unreachable in-scope views, Skote demo modules, a demo modal on every page | Low (cost) | D11. Delete first. |
| R15 | **Long-lived branch** while `dev` keeps moving: recent commits touch views (`reduce repeated invoice listing queries`) | Medium | Migrate module by module, merging often |

---

## 9. Decisions you need to make

### Decisions taken (2026-11-23)

| # | Decision |
|---|---|
| D1 | **a) Side by side.** A Tailwind-only layout sits next to the Bootstrap one, and modules switch one at a time (§11). |
| D3 | Bootstrap breakpoints kept (576/768/992/1200/1400) in `@theme`. |
| D4 | 13px base kept. |
| D5 | Blade components with **flat names**: `<x-button>`, `<x-card>`, `<x-input>`, `<x-alert>`, `<x-modal>` … in `resources/views/components/`, not `<x-ui.*>`. |
| D13 | Icon fonts kept. `icons.min.css` is imported into the new bundle in the `components` layer, so utilities can override it. |
| D14 | Poppins stays on Google Fonts for now. Self-hosting may come later. |
| Packages | `tailwindcss` and `@tailwindcss/vite` 4.3.5 added. `bootstrap-icons`, `autoprefixer` and `postcss` removed. |
| Mix build | `public/css/app.css`, `public/js/app.js` (+ map and licence file) and `public/css/animations.css` deleted (`abd36b8e`). |

Still open, and the recommendations below stand until decided: D2, D6–D12, D15, D16.

Each item below gives a recommendation. Items marked **(dependency)** add a package, and the project rules say that needs your approval.

| # | Question | Options | Recommendation |
|---|---|---|---|
| **D1** | How do Bootstrap and Tailwind coexist during the migration? | **a)** Two layouts: a new Tailwind-only layout with its own CSS bundle. Pages switch `@extends` module by module, so a page is never styled by both frameworks.<br>**b)** One layout with both CSS bundles. Tailwind is imported with `prefix(tw)` (`tw:flex`), with Preflight off, and the prefix is stripped at the end.<br>**c)** Big bang on a branch. | **a.** It avoids every collision in §7.4 and R5/R6, keeps `dev` shippable, and matches the module-by-module plan. The cost is two shells (header/sidebar) living side by side for a while. |
| **D2** | Visual target | Faithful port of the Skote look, or a redesign | **Faithful port first** (tokens from §2). Redesign later, when a single `@theme` change can do it. |
| **D3** | Breakpoints | Keep Bootstrap's 576/768/992/1200/1400, or adopt Tailwind's | **Keep Bootstrap's** in `@theme`. `col-lg-*` then maps to `lg:` with no behaviour change. |
| **D4** | Base font size | Keep 13px (body + `--text-*` helpers) or move to 14px | **Keep 13px** for parity. Changing it is a design decision (D2). |
| **D5** | How to express repeated UI (buttons, cards, inputs, badges, modals) | Blade anonymous components (`<x-ui.button>`), CSS classes via `@utility` / `@layer components` (`.btn`), or raw utilities inline | **Blade components** for structural pieces (form field with `@error`, card, modal, dropdown, tabs, page title). A few `@utility` classes (`btn`, `badge`) where JS plugins inject class names. Proposed folder: `resources/views/components/ui/` (a sub-folder of the existing components dir). |
| **D6** | JS for dropdown/modal/tabs/collapse | Alpine (already shipped by Livewire), keep Bootstrap JS, or a Tailwind UI kit (Preline, Flowbite) **(dependency)** | **Alpine**, with no new dependency |
| **D7** | DataTables (19 views) | **a)** Keep 1.10 and write a Tailwind skin for its default (non-bs4) markup<br>**b)** Upgrade to DataTables 2.x and use its Tailwind styling integration **(dependency upgrade)**<br>**c)** Replace with server-side tables (Laravel pagination, or Livewire) | **a** to unblock the migration, then **c** module by module. The tables already filter through query strings (`appFilter[...]`) and some are already server-paginated. |
| **D8** | Select2 and the datepicker | Re-skin both, or replace the datepicker with native `<input type="date">` / flatpickr **(dependency)**, or replace Select2 with Tom Select **(dependency)** | Re-skin Select2. Native `<input type="date">` for the date-range filters (they already use `yyyy-mm-dd`). |
| **D9** | Form base styles | `@tailwindcss/forms` **(dependency)**, or hand-written styles inside the form components | Hand-written inside `<x-ui.input>` and friends. Fewer surprises with Select2 and the datepicker. |
| **D10** | Sidebar | Keep metisMenu + SimpleBar + jQuery, or rebuild with Alpine (`x-collapse`, `$persist` for the collapsed state) and server-side active state (`request()->routeIs()`) | **Rebuild with Alpine.** It removes the URL-matching jQuery code too. |
| **D11** | Dead and demo code | Delete or migrate: Chat, Email inbox/compose, Calendar, Contacts, Setting/emails, FileManager, the `_modal` / `_subscribe` / `_rightSidebar` / `_navbar` / `_leftSidebar_commercial` partials, ~105 unreachable page views, the 4 unreachable Livewire views, legacy `public/css/app.css` and `public/js/app.js` | **Delete what is confirmed unused** before starting. Backup uses the `Excel` views, so keep and migrate those. |
| **D12** | Browser baseline | Confirm that users' browsers meet v4's requirements | Check the reception/workshop PCs. If some can't be upgraded, Tailwind v3.4 is the fallback (but you asked for v4). |
| **D13** | Icons | Keep the Boxicons / MDI / Font Awesome fonts (`icons.min.css`, 385 KB, ~690 usages), or move to SVG (Blade Icons) **(dependency)** | **Keep the fonts** for now. Import `icons.min.css` into the new bundle, in the `components` layer so utilities can override it. |
| **D14** | Poppins | Google Fonts `@import`, or self-host the font files | Self-host. Faster, works offline, no third-party request. |
| **D15** | Visual regression testing | Pest 4 browser plugin + Playwright, with `assertScreenshotMatches()` **(dev dependency)**, or manual screenshots | The Pest browser plugin if you accept the dev dependency. It also enables `assertNoJavascriptErrors()`, which catches R8-type breakage. |
| **D16** | Dark sidebar | Keep it (`data-sidebar="dark"`) or switch to a light sidebar | **Keep it.** The tokens are in §2.1. |

---

## 10. Proposed migration plan

The order follows your request: **layout → partials → components → pages**. Each phase ends with a commit, and `dev` stays shippable throughout (assuming D1 = a).

> **Status:**
> - Phase 1 is done (`170322f5`).
> - The legacy-artefact part of Phase 0 is done (`abd36b8e`).
> - Phases 2–6 are now carried out as the batches in **§11**, which replace the ordering and the wave table below. The checklists and the cheat sheet in this section still apply.

### Phase 0: preparation (no visual change)

1. Answer D1–D16.
2. Safety net:
   - Add a feature test that logs in as each role and hits every GET route under `admin:` / `commercial:`, asserting 200 (Pest, factories).
   - Take baseline screenshots of the 25 heaviest screens (§6.7) and one screen per module, at 375, 992 and 1440px.
3. Delete what D11 confirms as dead, plus the legacy artefacts (§1.4) and the demo `@include('theme.layouts._parts._modal')`.
4. Make dynamic classes static: the 5 `text-{{ $color }}` sites (§7.5).
5. Remove or fix the dormant BS4 classes (§7.4), so the migration doesn't change them silently.

### Phase 1: tooling (done in `170322f5`)

What was applied:
1. Installed `tailwindcss` and `@tailwindcss/vite` 4.3.5. Added `tailwindcss()` to `vite.config.mjs`, and Vite now watches `resources/views` so Tailwind sees Blade changes in `npm run dev`.
2. Entry points:
   - `resources/css/legacy.css` and `resources/js/legacy.js` hold the unchanged Bootstrap + Skote stack.
   - `resources/css/app.css` is the Tailwind entry: `source(none)` plus explicit `@source` paths, with PDF/e-mail/export views excluded, the `@theme` from §2.5, the base layer, and `icons.min.css` in the `components` layer.
   - `resources/js/app.js` holds only the axios setup.
3. Pointed the `@vite()` call at `legacy.css` / `legacy.js` in five files: `theme/layouts/app`, the 3 auth pages and `errors/503`. The 503 page was missed in the audit.
4. Removed `postcss.config.js`, `autoprefixer`, `postcss` and `bootstrap-icons`, and the commented-out `Paginator` lines.
5. Checks:
   - `npm run build` passes with no warnings.
   - The legacy CSS matches the previous build apart from vendor-prefix and minifier rewrites.
   - The theme tokens compile (`bg-primary/25`, `text-16`, `h-topbar`, `lg:` = 62rem …).
   - The test suite passes.

### Phase 2: layout

Build the new Tailwind shell next to the old one:

1. `resources/views/theme/layouts/app.blade.php` gets a Tailwind twin (for example `theme/layouts/tailwind.blade.php`, final name to be agreed). It must:
   - load only the Tailwind bundle, jQuery (still needed by plugins) and `@livewireScripts`;
   - drop the Bootstrap bundle, SimpleBar, metisMenu and `main.js`;
   - keep `@yield('css')`, `@yield('content')` and `@stack('scripts')`, so page views move over by changing one `@extends` line.
2. Shell structure with Tailwind + Alpine:
   - fixed topbar (`h-(--topbar-height)`);
   - fixed sidebar 250px / 70px collapsed, dark (`bg-sidebar`);
   - content offset and footer;
   - collapsed state in `Alpine.store` / `$persist`, toggled at `lg` (992px);
   - mobile off-canvas below `lg`;
   - preloader re-implemented without jQuery (or dropped).
3. Auth: extract one auth layout from the 3 standalone pages (`theme/Authentification/Login/index`, `Password/forgot`, `Password/reset`). It needs Alpine (add `@livewireScripts`) for the password show/hide toggle.

### Phase 3: layout partials

Migrate in this order. Each one is used on every page, so check it on several screens.

1. `theme/layouts/_parts/__header.blade.php`: logo, sidebar toggle, company name, fullscreen button, user dropdown (Alpine). Drop the empty search dropdown and the commented-out notification block.
2. `components/sidebar/main-sidebar` + `dashboard-item`, `reception-menu`, `commercial-menu`, `ticket-menu`, `technician-menu`, `reports-menu`, `admin-menu`, `settings-menu`, `developer-menu`:
   - metisMenu becomes Alpine `x-collapse`;
   - the active state is computed in Blade with `request()->routeIs()`, replacing the URL matching in `main.js`;
   - badges use `<x-ui.badge>`.
3. `theme/layouts/_parts/_leftSidebar.blade.php` (wrapper). `_leftSidebar_commercial.blade.php` is unreachable: its only reference is a commented-out view composer.
4. `_parts/_footer.blade.php`: replace `document.write(new Date().getFullYear())` with `{{ now()->year }}`. Also `theme/Authentification/auth_footer.blade.php`.
5. `_parts/__messages.blade.php`: becomes `<x-ui.alert>`.
6. `_parts/__loader.blade.php`, `_parts/_overly.blade.php`, `_parts/vendor-scripts.blade.php` (Tailwind layout version without Bootstrap).
7. `_helpers/apexchart/__apex_chart.blade.php`, `_helpers/apexchart/LineChart.blade.php`.
8. Delete (per D11): `_modal`, `_subscribe`, `_rightSidebar`, `_navbar` (empty file), `_leftSidebar_commercial`.

### Phase 4: components

1. **UI kit** (anonymous components, D5), each with a short preview on a hidden dev route or a Pest test that renders it:
   - `button` (variants primary/secondary/success/danger/warning/info/light/outline-*, sizes sm/md);
   - `card` (+ title, description: `card-title`, `card-title-desc`);
   - `page-title` (+ breadcrumb; replaces 36× `page-title-box`);
   - `badge` (solid + soft);
   - `alert` (dismissible);
   - form field components `label`, `input`, `select`, `textarea`, `checkbox`, `radio`, `input-group`. They take `name` and render `@error($name)` with the invalid state, replacing ~430 `is-invalid` / `invalid-feedback` pairs.
   - `table` (responsive wrapper, `nowrap`, light header);
   - `modal` (Alpine + `x-trap.noscroll`, opened by name through `$dispatch('open-modal', '…')`, closed with Esc or the backdrop);
   - `dropdown` (`x-anchor`);
   - `tabs`: emits `tab-shown`, which `datatables.init.2.js` uses instead of `shown.bs.tab`;
   - `accordion` (`x-collapse`);
   - `carousel`;
   - `avatar`.
2. **Pagination:** restyle `resources/views/vendor/pagination/tailwind.blade.php` and `simple-tailwind.blade.php`, then switch the 2 live `links('vendor.pagination.bootstrap-4')` calls (§4).
3. **Existing components:**
   - `components/forms/input` (empty stub) becomes the real form field, or is deleted in favour of `ui/*`. Also `components/forms/loading`.
   - `components/diagnostic/admin/*`: `diagnostic-layout`, `tab-nav`, `tab-content`, `ticket-table`, `filters`, `js-filters`.
   - `components/diagnostic/assets`.
   - The non-admin `components/diagnostic/*` set is unreachable (D11).
4. **Shared filter component:** merge the 9 near-identical filter partials (client select, status checkboxes, company radios, period dropdown, date range) into one component. This deletes the 9 duplicated `<style>` blocks.
5. **Plugin skins** in `@layer components`, ported from the relevant Skote sections: Select2, the datepicker (or its replacement, D8), the DataTables wrapper / pagination / buttons / search / length (D7), Dropzone and SweetAlert2. Then add the `custom.css` rules and drop that file.

### Phase 5: pages (module by module; Livewire views move with their host page)

For each module:
- switch its entry views' `@extends` to the new layout;
- rewrite every partial in the module with the kit and the mapping table (§10.2);
- run its smoke tests and compare with the baseline screenshots;
- commit.

The wave order that used to be here is replaced by the batches in §11.

Definition of done for each page (§11.4 is the full per-batch checklist):
- no `data-bs-*`;
- no class from the Bootstrap/Skote list (§6) left;
- forms show validation errors;
- modals and dropdowns work with the keyboard;
- Select2 and DataTables are styled;
- Livewire interactions still work after a re-render;
- correct at 375, 992 and 1440px;
- print preview checked where `@media print` exists.

### Phase 6: removal

Do this once no view extends the old layout:

1. Delete:
   - the old layout and `legacy.css`;
   - `resources/css/main/css/bootstrap.min.css` and Skote's `app.css` (keep `icons.min.css` + `fonts/` if D13 = keep);
   - `resources/js/main.js`, `resources/js/main/metisMenu.min.js`, `resources/js/main/waves.min.js`;
   - `public/assets/libs/bootstrap`, `simplebar`, `datatables.net-*-bs4` (if D7 a/b) and any other libs no longer referenced;
   - the published `bootstrap*`, `default`, `simple-default`, `semantic-ui` pagination views and `vendor/livewire/bootstrap*`.
2. Already done in `170322f5`: the commented `Paginator` lines, `bootstrap-icons`, `autoprefixer`, `postcss` and `postcss.config.js`.
3. Gates (all must return nothing):
   ```bash
   grep -rn 'data-bs-' resources/views
   grep -rnE 'class="[^"]*\b(btn|card|card-body|form-control|form-select|row|col-(sm|md|lg|xl)-[0-9]+|d-flex|mb-[345]|me-[345])\b' resources/views/theme resources/views/components
   grep -rn 'bootstrap\.' resources/js public/js/pages
   ```
4. If D1 = b: strip the `tw:` prefix with one mechanical rename and remove `prefix(tw)`.

### Phase 7: final QA

- Re-run the smoke tests and the screenshot comparison.
- Do a manual pass of the critical workflows: ticket intake, diagnostic, estimate, invoice, payment, credit note, delivery and print/PDF.
- Test on the oldest browser in use (D12).
- Check Lighthouse / bundle size. Today about 700 KB of CSS ships on every page.

### 10.2 Conversion cheat sheet (Bootstrap 5 / Skote → Tailwind v4 with the §2.5 theme)

| Bootstrap / Skote | Tailwind |
|---|---|
| `row` + `col-12 col-lg-6` | `grid grid-cols-12 gap-6` + `col-span-12 lg:col-span-6` (a Bootstrap column without a base size is full width on mobile) |
| `row` + `col` / `col-auto` | `flex flex-wrap gap-6` + `flex-1` / `flex-none` |
| `g-0`, `g-2`, `g-3` | `gap-0`, `gap-2`, `gap-4` |
| `container-fluid` | `w-full px-3` |
| `m*-1`, `m*-2`, `m*-3`, `m*-4`, `m*-5` (same for `p*-`, `gap-`) | `*-1`, `*-2`, **`*-4`, `*-6`, `*-12`** |
| `ms-*` / `me-*` / `ps-*` / `pe-*` | same prefixes, values per the row above |
| `d-flex`, `d-none`, `d-block`, `d-sm-flex`, `d-none d-xl-inline-block` | `flex`, `hidden`, `block`, `sm:flex`, `hidden xl:inline-block` |
| `justify-content-between`, `align-items-center`, `align-self-end` | `justify-between`, `items-center`, `self-end` |
| `flex-fill`, `flex-grow-1`, `flex-shrink-0` | `flex-auto`, `grow`, `shrink-0` |
| `w-100`, `h-100`, `w-50` | `w-full`, `h-full`, `w-1/2` |
| `fw-bold`, `fw-medium`, `fw-semibold`, `text-uppercase` | `font-bold`, `font-medium`, `font-semibold`, `uppercase` |
| `font-size-16` | `text-16` (theme token) |
| `text-muted`, `text-body`, `text-dark` | `text-muted`, `text-body`, `text-dark` (theme colours) |
| `text-truncate`, `visually-hidden` / `sr-only` | `truncate`, `sr-only` |
| `rounded-circle`, `rounded-pill` | `rounded-full` |
| `position-relative`, `position-absolute`, `top-0 end-0` | `relative`, `absolute`, `top-0 end-0` |
| `bg-primary bg-soft`, `badge-soft-primary` | `bg-primary/25`, `bg-primary/18 text-primary` |
| `float-end`, `text-end`, `text-center` | `float-end`, `text-end`, `text-center` |
| `avatar-xs` / `-sm` / `-md` | `size-8` / `size-12` / `size-18` |
| `waves-effect`, `waves-light` | delete |
| `btn btn-primary btn-sm`, `card`/`card-body`, `form-control` + `is-invalid` + `invalid-feedback`, `modal …`, `dropdown …`, `nav nav-tabs …`, `badge bg-*`, `alert alert-*`, `table table-*`, `breadcrumb` | `<x-button>`, `<x-card>`, `<x-input>`, `<x-modal>`, `<x-dropdown>`, `<x-tabs>`, `<x-badge>`, `<x-alert>`, `<x-table>`, `<x-page-title>` … (§11, batch B1) |

---

## 11. Batch plan: view migration by module

Order: **shell (layout, sidebar, topbar) first, then Auth, then one business module at a time**, each with its own modals, partials, Livewire views and JS. Each batch ends with a build, the test suite and a commit.

### 11.1 Rules that hold after every batch

1. **A page is either fully Tailwind or fully Bootstrap.**
   - A migrated entry view extends the new layout `theme.layouts.tailwind`, and everything it includes is Tailwind.
   - A page that is not migrated yet extends `theme.layouts.app`, and everything it includes is Bootstrap.
   - So **no Tailwind page ever includes a Bootstrap partial**, and no Bootstrap page includes a Tailwind one (Bootstrap pages don't load the Tailwind CSS).
2. **Shared Bootstrap pieces are never converted in place** while a Bootstrap page still uses them. The batch uses the Tailwind counterpart from §11.2, and the legacy piece is deleted in the batch that migrates its last user.
3. **Coupled modules move together:**
   - `Commercial/BL` includes `Commercial/BC/__detail/BC` and `BC_detail_top`, so BC and BL form one batch.
   - `Email` and `Setting` include each other, so they form one batch.
4. **Views follow their folder, not their controller.** These views are rendered by another module's controller and still move with their own folder:

   | View | Rendered by | Moves with |
   |---|---|---|
   | `Commercial/Invoice/__create_from_estimate` | `EstimateController` | Invoice |
   | `Ticket/__diagnostic` | `DiagnostiqueController` | Ticket |
   | `Ticket/__invoiceable`, `Ticket/__pret_livre` | `DashboardController` | Ticket |
   | `Excel` | `BackupController` and `ExcelController` | Excel |
5. **The sidebar exists twice during the migration:** the legacy `components/sidebar/*` and the Tailwind shell copy. Any menu or permission change before the final batch must be made in **both** copies.
6. **The two shells must look the same.** Users move between migrated and non-migrated modules all the time, so the Tailwind shell must reproduce the legacy one exactly (D2: faithful port).

### 11.2 Shared pieces and their Tailwind counterparts

| Legacy piece (used by) | Tailwind counterpart | Created in | Legacy deleted in |
|---|---|---|---|
| `theme/layouts/app.blade.php` (84 entry views) | `theme/layouts/tailwind.blade.php`. Loads `@vite(['resources/css/app.css', 'resources/js/app.js'])`, jQuery (the plugins still need it) and `@livewireScripts`, with `x-data` on `<body>`. No Bootstrap bundle, SimpleBar, metisMenu or `main.js`. | B1 | B24 |
| `View::composer('theme.layouts.app', TicketComposer::class)` (sidebar badge counts) | Add `theme.layouts.tailwind` to the same composer list | B1 | B24 |
| `_parts/__header`, `_parts/_footer`, `_parts/__loader`, `_parts/_overly`, `_parts/_leftSidebar`, `components/sidebar/*` | Tailwind shell components: topbar, sidebar (Alpine store with `$persist` for the collapsed state at `lg`, `x-collapse` sub-menus, active state from `request()->routeIs()`), footer, loader | B1 | B24 |
| `_parts/__messages` (included by Client, BC, BL, Bill, Estimate, Invoice, InvoiceAvoir, Report, Ticket, TicketRapport) | `<x-flash-messages />`. Each module batch swaps its `@include`. | B1 | B24 |
| `_parts/vendor-scripts` (jQuery + Bootstrap bundle + SimpleBar; used by the layout, the auth pages and 503) | The new layout loads jQuery only; the auth layout loads nothing | B1 / B2 | B24 |
| `vendor/pagination/bootstrap-4` (PermissionRole) | Restyled `vendor/pagination/tailwind` and `simple-tailwind` | B1 | B24 |
| DataTables Bootstrap 4 integration (`datatables.net-*-bs4` CSS/JS) | DataTables core without bs4 + a Tailwind skin in `app.css` `@layer components` (D7 a) | first DataTables batch (B5) | B24 |
| `public/js/pages/datatables.init.js` (Client, Company, Provider, Report, Warranty, Ticket, TicketRapport, BC, BL, Bill, Estimate, InvoiceAvoir), `datatables.init.document.js` (Invoice), `datatables.init.2.js` (Diagnostic) | Tailwind copies next to the originals, without `className: 'btn-primary'` / `addClass('form-select …')`. The Diagnostic copy listens to `tab-shown` instead of `shown.bs.tab`. | first module needing each one | the batch that migrates its last user |
| Select2 Skote skin | Select2 skin in `app.css` | first Select2 batch (B9) | B24 |
| bootstrap-datepicker | Per D8: native `<input type="date">` or a restyle | first datepicker batch (B13) | last datepicker user |
| SweetAlert2, Dropzone, TinyMCE, Magnific Popup, ApexCharts Skote rules | Small skins in `app.css` as needed | first batch using each | B24 |

These page scripts are **framework-neutral** and can be shared as they are: `add_invoice.js`, `form-repeater.int.js`, `select_2_init.js`, `ticket-create.init.js` (TinyMCE) and `lightbox.init.js`.

### 11.3 Batches

"Views" = reachable views moved by the batch. "BS" = Bootstrap class usages in them (§6).

| # | Batch | Views | BS | Contents and notes |
|---|---|---:|---:|---|
| **B1** | **Shell** | 19 | 127 | The new layout and shell components (§11.2). The component kit: `<x-button>`, `<x-card>`, `<x-page-title>`, `<x-badge>`, `<x-alert>`, `<x-flash-messages>`, `<x-dropdown>` (+ item, divider), `<x-modal>`, `<x-tabs>` (+ tab, panel; emits `tab-shown`), `<x-accordion-item>`, `<x-table>`, `<x-avatar>`, and the form fields `<x-field>`, `<x-input>`, `<x-select>`, `<x-textarea>`, `<x-checkbox>`, `<x-radio>`, `<x-input-error>`. Restyled Tailwind pagination views. Pest render tests for the components and the `<x-button>` type guard (§11.4). No business page moves yet; the shell gets its first visual check in B3. |
| **B2** | **Auth** | 5 | 173 | `Authentification/Login`, `Password/forgot`, `Password/reset`, `auth_footer`, plus `errors/503`. New `theme/layouts/auth.blade.php` without jQuery or Bootstrap. `@livewireScripts` for the Alpine password toggle. |
| B3 | PermissionRole | 6 | 140 | Pilot for the shell. Pagination moves from `bootstrap-4` to `tailwind`; dismissible alert. |
| B4 | Category | 4 | 70 | |
| B5 | Warranty | 4 | 77 | First DataTables module: DataTables Tailwind skin and the Tailwind copy of `datatables.init.js`. Add-warranty modal. |
| B6 | Report | 4 | 54 | DataTables |
| B7 | Importer | 4 | 23 | Livewire `application/importer` and `components/forms/loading` |
| B8 | Profile | 7 | 248 | Tabs (settings) |
| B9 | Admin | 10 | 190 | First Select2 module: Select2 skin. Permission checkboxes, dismissible alert. |
| B10 | Client | 13 | 318 | DataTables, repeater, import / phones / emails modals |
| B11 | Commercial/Provider | 10 | 215 | Phones / emails modals |
| B12 | Commercial/Company | 6 | 173 | Long forms |
| B13 | Commercial/Invoice | 39 | 802 | The reference commercial module. 3 Livewire views. `__create_from_estimate`. First datepicker (D8) and SweetAlert2 module. Send / print / payment modals, history accordion, invoice pager (`simple-tailwind`). Builds the shared filter component that later commercial batches reuse. |
| B14 | Commercial/Estimate | 31 | 697 | 4 Livewire views |
| B15 | Commercial/InvoiceAvoir | 25 | 519 | 2 Livewire views |
| B16 | Commercial/BC + Commercial/BL | 46 | 888 | Coupled (rule 3). 1 Livewire view each. |
| B17 | Commercial/Bill | 19 | 403 | |
| B18 | Ticket | 41 | 802 | Includes `__diagnostic`, `__invoiceable` and `__pret_livre`. TinyMCE, lightbox, the carousel, the pill tab, and the dynamic `text-{{ $color }}` classes (§7.5). |
| B19 | Diagnostic | 18 | 247 | `components/diagnostic/admin/*`. Tailwind copy of `datatables.init.2.js` using `tab-shown`. |
| B20 | Reparation | 5 | 44 | |
| B21 | TicketRapport | 9 | 116 | Last user of the legacy `datatables.init.js`, which gets deleted here (unless a B23 module still uses it). |
| B22 | Home (dashboard) | 10 | 233 | Livewire dashboard, `_helpers/apexchart`, period filter |
| B23 | Kept demo modules (D11) | — | — | Only what D11 keeps: Excel (used by Backup), Email + Setting (coupled), Chat, Calendar, Contact. Everything else is deleted instead. |
| **B24** | **Removal** | — | — | §10 Phase 6: old layout, legacy shell partials, `components/sidebar/*`, `__messages`, legacy page scripts, bs4 libraries, `legacy.css` / `legacy.js`, Bootstrap bundle, Skote CSS. Then the grep gates. |

The order can change within B3–B22, except that B16 must stay a single batch, and each plugin skin is created by whichever batch first needs it.

### 11.4 Checklist for every batch

1. **Scope.**
   - Every view in the module is converted: entry views, partials, modals, Livewire views and module components.
   - Every entry view `@extends('theme.layouts.tailwind')`.
   - Every `@include` / `<x-…>` / `<livewire:…>` in the batch points to a Tailwind view.
   - These must return nothing for the batch's folders:
     ```bash
     grep -rn "data-bs-" <batch folders>
     grep -rnE "theme\.layouts\.app'|_parts\.__messages|vendor\.pagination\.bootstrap" <batch folders>
     grep -rnE 'class="[^"]*\b(btn|card|card-body|form-control|form-select|row|col-(sm|md|lg|xl)-[0-9]+|d-flex|mb-[345]|me-[345]|waves-effect)\b' <batch folders>
     ```
2. **`<x-button>` type.** `<x-button>` defaults to `type="submit"`, like a native button. So every converted `<x-button>` that does **not** submit a form needs **`type="button"`**:
   - modal, dropdown, tab and accordion triggers;
   - buttons with `x-on:click`, `@click`, `$dispatch(...)`, `wire:click` or `onclick`;
   - close / cancel buttons;
   - any button inside a `<form>` that isn't that form's submit.

   How it's checked:
   - **Automated:** a Pest test added in B1 scans `resources/views` and fails on any `<x-button` that has `x-on:`, `@click`, `$dispatch`, `wire:click` or `onclick` but no `type=`.
   - **Manual:** every remaining `<x-button>` without `type` in the batch is reviewed, and must be a real submit button of its form. Links use `href` and render `<a>`.
3. **JS.**
   - Page scripts are framework-neutral or Tailwind copies (§11.2).
   - No `bootstrap.*` calls and no `*.bs.*` events.
   - DataTables loads without the bs4 integration.
   - Select2 inside Livewire keeps `wire:ignore`.
   - Each modal `name` is unique per record (UUID-based, as today).
4. **Behaviour.**
   - Validation errors show on every form, and modals reopen after an error when needed (`:show="$errors->any()"`).
   - Modals close with Esc and the backdrop, and keep focus inside.
   - Dropdowns and tabs work with the keyboard.
   - Livewire components keep their Alpine state after a re-render.
5. **Visual.**
   - Compared with the legacy page at 375, 992 and 1440px.
   - Print preview checked where the page has `@media print` (the `__detail` pages).
6. **Build and tests.**
   - `npm run build` passes with no errors or warnings.
   - `php artisan test --compact` is green.
   - `vendor/bin/pint --dirty --format agent` has been run on any PHP change.
7. **Cleanup.** Any legacy shared piece whose last user just migrated is deleted in the same batch.
8. **Commit.** One commit per batch (or per sub-step for B1, B13 and B18), for example `feat(ui): migrate Warranty to Tailwind`.

---

## Appendix A: per-file inventory of pages (`resources/views/theme/pages`)

#### Admin

| File | Lines | BS classes | Skote classes | BS4 leftovers | JS components / plugins | Reachable |
|---|---:|---:|---:|---:|---|:---:|
| `theme/pages/Admin/__create/form.blade.php` | 101 | 57 | 1 |  |  | ✓ |
| `theme/pages/Admin/__create/index.blade.php` | 13 | 1 | 0 |  |  | ✓ |
| `theme/pages/Admin/__grid/index.blade.php` | 3 | 0 | 0 |  |  | — |
| `theme/pages/Admin/__grid/section_a_contacts.blade.php` | 125 | 112 | 29 |  |  | — |
| `theme/pages/Admin/__list/index.blade.php` | 1 | 0 | 0 |  |  | ✓ |
| `theme/pages/Admin/__list/section_a_contacts.blade.php` | 113 | 21 | 3 |  |  | ✓ |
| `theme/pages/Admin/__profile/__checkbox_permissions.blade.php` | 63 | 31 | 2 |  | alert-dismiss | ✓ |
| `theme/pages/Admin/__profile/__select_multi_roles.blade.php` | 37 | 8 | 0 |  | Select2 | ✓ |
| `theme/pages/Admin/__profile/__select_one_role.blade.php` | 20 | 8 | 0 |  |  | — |
| `theme/pages/Admin/__profile/__select_permissions.blade.php` | 70 | 7 | 0 |  | Select2 | — |
| `theme/pages/Admin/__profile/form.blade.php` | 107 | 59 | 1 |  |  | ✓ |
| `theme/pages/Admin/__profile/index.blade.php` | 29 | 1 | 0 |  | Select2 | ✓ |
| `theme/pages/Admin/index.blade.php` | 13 | 1 | 0 |  |  | ✓ |
| `theme/pages/Admin/section_0_page_title.blade.php` | 20 | 11 | 2 |  |  | ✓ |

#### Backup

| File | Lines | BS classes | Skote classes | BS4 leftovers | JS components / plugins | Reachable |
|---|---:|---:|---:|---:|---|:---:|
| `theme/pages/Backup/__title.blade.php` | 17 | 10 | 2 |  |  | — |
| `theme/pages/Backup/index.blade.php` | 13 | 1 | 0 |  |  | — |
| `theme/pages/Backup/section_a_backups.blade.php` | 124 | 42 | 0 | 3 |  | — |

#### Calendar

| File | Lines | BS classes | Skote classes | BS4 leftovers | JS components / plugins | Reachable |
|---|---:|---:|---:|---:|---|:---:|
| `theme/pages/Calendar/index.blade.php` | 35 | 1 | 0 |  |  | ✓ |
| `theme/pages/Calendar/scripts.blade.php` | 13 | 0 | 0 |  |  | — |
| `theme/pages/Calendar/sections/section_0_page_title.blade.php` | 17 | 11 | 2 |  |  | ✓ |
| `theme/pages/Calendar/sections/section_a_calendar.blade.php` | 100 | 54 | 0 |  | dropdown, modal(BS4 attr, inert) | ✓ |
| `theme/pages/Calendar/styles.blade.php` | 3 | 0 | 0 |  |  | — |

#### Category

| File | Lines | BS classes | Skote classes | BS4 leftovers | JS components / plugins | Reachable |
|---|---:|---:|---:|---:|---|:---:|
| `theme/pages/Category/__grid/categories_grid.blade.php` | 42 | 39 | 5 |  | tooltip(BS4 attr, inert) | — |
| `theme/pages/Category/__grid/index.blade.php` | 1 | 0 | 0 |  |  | — |
| `theme/pages/Category/__list/index.blade.php` | 2 | 0 | 0 |  |  | ✓ |
| `theme/pages/Category/__list/section_a_categories.blade.php` | 125 | 58 | 4 |  |  | ✓ |
| `theme/pages/Category/index.blade.php` | 13 | 1 | 0 |  |  | ✓ |
| `theme/pages/Category/section_0_page_title.blade.php` | 17 | 11 | 2 |  |  | ✓ |

#### Chat

| File | Lines | BS classes | Skote classes | BS4 leftovers | JS components / plugins | Reachable |
|---|---:|---:|---:|---:|---|:---:|
| `theme/pages/Chat/chat.blade.php` | 608 | 391 | 107 | 1 | dropdown, tabs, simplebar | ✓ |
| `theme/pages/Chat/index.blade.php` | 13 | 1 | 0 |  |  | ✓ |
| `theme/pages/Chat/section_0_page_title.blade.php` | 17 | 11 | 2 |  |  | ✓ |

#### Client

| File | Lines | BS classes | Skote classes | BS4 leftovers | JS components / plugins | Reachable |
|---|---:|---:|---:|---:|---|:---:|
| `theme/pages/Client/__create/__add_phones.blade.php` | 27 | 16 | 0 |  |  | — |
| `theme/pages/Client/__create/form_2.blade.php` | 172 | 55 | 2 |  |  | ✓ |
| `theme/pages/Client/__create/index.blade.php` | 26 | 1 | 0 |  |  | ✓ |
| `theme/pages/Client/__datatable/__clients_table.blade.php` | 2 | 0 | 0 |  |  | ✓ |
| `theme/pages/Client/__datatable/__import_clients.blade.php` | 40 | 25 | 0 |  | modal | ✓ |
| `theme/pages/Client/__datatable/__with_options.blade.php` | 109 | 21 | 2 |  | modal-trigger | ✓ |
| `theme/pages/Client/__datatable/index.blade.php` | 51 | 1 | 0 |  | DataTables | ✓ |
| `theme/pages/Client/__edit/__add_phones.blade.php` | 69 | 35 | 2 |  | modal | ✓ |
| `theme/pages/Client/__edit/__edit_emails.blade.php` | 62 | 30 | 4 |  | modal | ✓ |
| `theme/pages/Client/__edit/__edit_phones.blade.php` | 58 | 29 | 0 |  |  | — |
| `theme/pages/Client/__edit/form_2.blade.php` | 185 | 55 | 2 |  | modal-trigger | ✓ |
| `theme/pages/Client/__edit/index.blade.php` | 90 | 1 | 0 |  |  | ✓ |
| `theme/pages/Client/__profile/index.blade.php` | 18 | 1 | 0 |  |  | ✓ |
| `theme/pages/Client/__profile/profile.blade.php` | 245 | 83 | 15 |  |  | ✓ |
| `theme/pages/Client/index.blade.php` | 15 | 1 | 0 |  |  | — |
| `theme/pages/Client/section_0_page_title.blade.php` | 18 | 10 | 2 |  |  | ✓ |

#### Commercial/BC

| File | Lines | BS classes | Skote classes | BS4 leftovers | JS components / plugins | Reachable |
|---|---:|---:|---:|---:|---|:---:|
| `theme/pages/Commercial/BC/__create/__add_articles.blade.php` | 73 | 34 | 6 |  |  | ✓ |
| `theme/pages/Commercial/BC/__create/__form_create.blade.php` | 119 | 57 | 4 |  | datepicker | ✓ |
| `theme/pages/Commercial/BC/__create/__info.blade.php` | 63 | 20 | 0 |  | Select2 | — |
| `theme/pages/Commercial/BC/__create/index.blade.php` | 41 | 1 | 0 |  | Select2, datepicker | ✓ |
| `theme/pages/Commercial/BC/__datatable/__documents_table.blade.php` | 6 | 0 | 0 |  |  | ✓ |
| `theme/pages/Commercial/BC/__datatable/__filters.blade.php` | 158 | 55 | 0 |  | dropdown, Select2, datepicker | ✓ |
| `theme/pages/Commercial/BC/__datatable/__send_bc.blade.php` | 52 | 27 | 0 |  | modal | ✓ |
| `theme/pages/Commercial/BC/__datatable/__with_options.blade.php` | 128 | 23 | 2 |  | modal-trigger | ✓ |
| `theme/pages/Commercial/BC/__datatable/index.blade.php` | 70 | 1 | 0 |  | Select2, datepicker, DataTables | ✓ |
| `theme/pages/Commercial/BC/__detail/BC.blade.php` | 105 | 42 | 7 |  |  | ✓ |
| `theme/pages/Commercial/BC/__detail/BC_detail_top.blade.php` | 18 | 11 | 2 |  |  | ✓ |
| `theme/pages/Commercial/BC/__detail/index.blade.php` | 50 | 1 | 0 |  |  | ✓ |
| `theme/pages/Commercial/BC/__edit/__add_articles.blade.php` | 71 | 34 | 6 |  |  | ✓ |
| `theme/pages/Commercial/BC/__edit/__bc_actions.blade.php` | 51 | 18 | 17 |  | modal-trigger | ✓ |
| `theme/pages/Commercial/BC/__edit/__delete_articles_bc_ajax.blade.php` | 33 | 0 | 0 |  |  | ✓ |
| `theme/pages/Commercial/BC/__edit/__edit_articles.blade.php` | 80 | 34 | 6 |  |  | ✓ |
| `theme/pages/Commercial/BC/__edit/__form_edit.blade.php` | 134 | 60 | 4 |  | datepicker | ✓ |
| `theme/pages/Commercial/BC/__edit/__historique_bc.blade.php` | 28 | 9 | 0 |  | collapse/accordion | ✓ |
| `theme/pages/Commercial/BC/__edit/__info.blade.php` | 34 | 13 | 0 |  |  | ✓ |
| `theme/pages/Commercial/BC/__edit/__print_document.blade.php` | 31 | 16 | 0 |  | modal | ✓ |
| `theme/pages/Commercial/BC/__edit/index.blade.php` | 67 | 1 | 0 |  | Select2, datepicker, SweetAlert2 | ✓ |
| `theme/pages/Commercial/BC/__js.blade.php` | 165 | 0 | 0 |  | Select2 | ✓ |
| `theme/pages/Commercial/BC/index.blade.php` | 1 | 0 | 0 |  |  | ✓ |
| `theme/pages/Commercial/BC/section_0_page_title.blade.php` | 20 | 11 | 2 |  |  | ✓ |

#### Commercial/BL

| File | Lines | BS classes | Skote classes | BS4 leftovers | JS components / plugins | Reachable |
|---|---:|---:|---:|---:|---|:---:|
| `theme/pages/Commercial/BL/__create/__add_articles.blade.php` | 73 | 34 | 6 |  |  | ✓ |
| `theme/pages/Commercial/BL/__create/__form_create.blade.php` | 135 | 63 | 4 |  | datepicker | ✓ |
| `theme/pages/Commercial/BL/__create/__info.blade.php` | 63 | 20 | 0 |  | Select2 | — |
| `theme/pages/Commercial/BL/__create/index.blade.php` | 40 | 1 | 0 |  | Select2, datepicker | ✓ |
| `theme/pages/Commercial/BL/__datatable/__documents_table.blade.php` | 6 | 0 | 0 |  |  | ✓ |
| `theme/pages/Commercial/BL/__datatable/__filters.blade.php` | 158 | 55 | 0 |  | dropdown, Select2, datepicker | ✓ |
| `theme/pages/Commercial/BL/__datatable/__send_bc.blade.php` | 52 | 27 | 0 |  | modal | ✓ |
| `theme/pages/Commercial/BL/__datatable/__with_options.blade.php` | 113 | 19 | 2 |  | modal-trigger | ✓ |
| `theme/pages/Commercial/BL/__datatable/index.blade.php` | 70 | 1 | 0 |  | Select2, datepicker, DataTables | ✓ |
| `theme/pages/Commercial/BL/__detail/BC.blade.php` | 105 | 42 | 7 |  |  | — |
| `theme/pages/Commercial/BL/__detail/BC_detail_top.blade.php` | 18 | 11 | 2 |  |  | — |
| `theme/pages/Commercial/BL/__detail/index.blade.php` | 50 | 1 | 0 |  |  | ✓ |
| `theme/pages/Commercial/BL/__edit/__add_articles.blade.php` | 71 | 34 | 6 |  |  | ✓ |
| `theme/pages/Commercial/BL/__edit/__bc_actions.blade.php` | 51 | 18 | 17 |  | modal-trigger | ✓ |
| `theme/pages/Commercial/BL/__edit/__delete_articles_bc_ajax.blade.php` | 33 | 0 | 0 |  |  | ✓ |
| `theme/pages/Commercial/BL/__edit/__edit_articles.blade.php` | 80 | 34 | 6 |  |  | ✓ |
| `theme/pages/Commercial/BL/__edit/__form_edit.blade.php` | 146 | 63 | 4 |  | datepicker | ✓ |
| `theme/pages/Commercial/BL/__edit/__historique_bc.blade.php` | 28 | 9 | 0 |  | collapse/accordion | ✓ |
| `theme/pages/Commercial/BL/__edit/__info.blade.php` | 34 | 13 | 0 |  |  | ✓ |
| `theme/pages/Commercial/BL/__edit/__print_document.blade.php` | 31 | 16 | 0 |  | modal | ✓ |
| `theme/pages/Commercial/BL/__edit/index.blade.php` | 67 | 1 | 0 |  | Select2, datepicker, SweetAlert2 | ✓ |
| `theme/pages/Commercial/BL/__js.blade.php` | 165 | 0 | 0 |  | Select2 | ✓ |
| `theme/pages/Commercial/BL/index.blade.php` | 1 | 0 | 0 |  |  | ✓ |
| `theme/pages/Commercial/BL/section_0_page_title.blade.php` | 20 | 11 | 2 |  |  | ✓ |

#### Commercial/Bill

| File | Lines | BS classes | Skote classes | BS4 leftovers | JS components / plugins | Reachable |
|---|---:|---:|---:|---:|---|:---:|
| `theme/pages/Commercial/Bill/__create/__form_create.blade.php` | 88 | 35 | 4 |  |  | ✓ |
| `theme/pages/Commercial/Bill/__create/__info.blade.php` | 59 | 26 | 0 |  |  | ✓ |
| `theme/pages/Commercial/Bill/__create/__info_bill.blade.php` | 70 | 27 | 0 |  | Select2, datepicker | ✓ |
| `theme/pages/Commercial/Bill/__create/index.blade.php` | 35 | 1 | 0 |  | datepicker | ✓ |
| `theme/pages/Commercial/Bill/__create_normal/__form_create.blade.php` | 88 | 35 | 4 |  |  | ✓ |
| `theme/pages/Commercial/Bill/__create_normal/__info.blade.php` | 59 | 26 | 0 |  |  | ✓ |
| `theme/pages/Commercial/Bill/__create_normal/__info_bill.blade.php` | 66 | 27 | 0 |  | Select2, datepicker | ✓ |
| `theme/pages/Commercial/Bill/__create_normal/index.blade.php` | 35 | 1 | 0 |  | datepicker | ✓ |
| `theme/pages/Commercial/Bill/__datatable/__add_bill.blade.php` | 143 | 49 | 2 |  | modal | ✓ |
| `theme/pages/Commercial/Bill/__datatable/__bills_table.blade.php` | 2 | 0 | 0 |  |  | ✓ |
| `theme/pages/Commercial/Bill/__datatable/__filters.blade.php` | 174 | 55 | 0 |  | dropdown, Select2, datepicker | ✓ |
| `theme/pages/Commercial/Bill/__datatable/__js_filters.blade.php` | 165 | 0 | 0 |  | Select2 | ✓ |
| `theme/pages/Commercial/Bill/__datatable/__with_options.blade.php` | 124 | 28 | 5 |  | modal-trigger | ✓ |
| `theme/pages/Commercial/Bill/__datatable/index.blade.php` | 73 | 1 | 0 |  | Select2, datepicker, DataTables | ✓ |
| `theme/pages/Commercial/Bill/__detail/Bill.blade.php` | 109 | 42 | 7 |  |  | — |
| `theme/pages/Commercial/Bill/__detail/Bill_detail_top.blade.php` | 19 | 11 | 2 |  |  | — |
| `theme/pages/Commercial/Bill/__detail/index.blade.php` | 54 | 1 | 0 |  |  | — |
| `theme/pages/Commercial/Bill/__edit/__form_edit.blade.php` | 86 | 33 | 2 |  |  | ✓ |
| `theme/pages/Commercial/Bill/__edit/__info.blade.php` | 46 | 20 | 0 |  |  | ✓ |
| `theme/pages/Commercial/Bill/__edit/__info_bill.blade.php` | 65 | 27 | 0 |  | Select2, datepicker | ✓ |
| `theme/pages/Commercial/Bill/__edit/__send_invoice_section.blade.php` | 10 | 2 | 1 |  |  | — |
| `theme/pages/Commercial/Bill/__edit/index.blade.php` | 35 | 1 | 0 |  | datepicker | ✓ |
| `theme/pages/Commercial/Bill/index.blade.php` | 1 | 0 | 0 |  |  | — |
| `theme/pages/Commercial/Bill/section_0_page_title.blade.php` | 20 | 11 | 2 |  |  | ✓ |

#### Commercial/Company

| File | Lines | BS classes | Skote classes | BS4 leftovers | JS components / plugins | Reachable |
|---|---:|---:|---:|---:|---|:---:|
| `theme/pages/Commercial/Company/__create/form_2.blade.php` | 302 | 120 | 2 |  |  | — |
| `theme/pages/Commercial/Company/__create/index.blade.php` | 24 | 1 | 0 |  |  | — |
| `theme/pages/Commercial/Company/__datatable/__companies_table.blade.php` | 2 | 0 | 0 |  |  | ✓ |
| `theme/pages/Commercial/Company/__datatable/__with_options.blade.php` | 109 | 31 | 3 |  |  | ✓ |
| `theme/pages/Commercial/Company/__datatable/index.blade.php` | 49 | 1 | 0 |  | DataTables | ✓ |
| `theme/pages/Commercial/Company/__edit/form_2.blade.php` | 333 | 130 | 2 |  |  | ✓ |
| `theme/pages/Commercial/Company/__edit/index.blade.php` | 24 | 1 | 0 |  |  | ✓ |
| `theme/pages/Commercial/Company/index.blade.php` | 16 | 1 | 0 |  |  | — |
| `theme/pages/Commercial/Company/section_0_page_title.blade.php` | 20 | 10 | 2 |  |  | ✓ |

#### Commercial/Estimate

| File | Lines | BS classes | Skote classes | BS4 leftovers | JS components / plugins | Reachable |
|---|---:|---:|---:|---:|---|:---:|
| `theme/pages/Commercial/Estimate/__create/__add_articles.blade.php` | 70 | 34 | 6 |  |  | ✓ |
| `theme/pages/Commercial/Estimate/__create/__form_create.blade.php` | 172 | 77 | 4 |  | Select2, datepicker | ✓ |
| `theme/pages/Commercial/Estimate/__create/__info.blade.php` | 65 | 19 | 0 |  | Select2 | — |
| `theme/pages/Commercial/Estimate/__create/__mutli_tickets.blade.php` | 38 | 8 | 0 |  | Select2 | — |
| `theme/pages/Commercial/Estimate/__create/__tickets.blade.php` | 24 | 6 | 0 |  | Select2 | — |
| `theme/pages/Commercial/Estimate/__create/index.blade.php` | 80 | 1 | 0 |  | Select2, datepicker | ✓ |
| `theme/pages/Commercial/Estimate/__create_from_ticket/__add_articles.blade.php` | 70 | 34 | 6 |  |  | ✓ |
| `theme/pages/Commercial/Estimate/__create_from_ticket/__form_create.blade.php` | 161 | 79 | 4 |  | Select2, datepicker | ✓ |
| `theme/pages/Commercial/Estimate/__create_from_ticket/__info.blade.php` | 72 | 26 | 0 |  | Select2 | — |
| `theme/pages/Commercial/Estimate/__create_from_ticket/index.blade.php` | 64 | 1 | 0 |  | Select2, datepicker | ✓ |
| `theme/pages/Commercial/Estimate/__datatable/__estimates_table.blade.php` | 7 | 0 | 0 |  |  | ✓ |
| `theme/pages/Commercial/Estimate/__datatable/__filter.blade.php` | 154 | 55 | 0 |  | dropdown, Select2, datepicker | ✓ |
| `theme/pages/Commercial/Estimate/__datatable/__js_filter.blade.php` | 187 | 0 | 0 |  | Select2 | ✓ |
| `theme/pages/Commercial/Estimate/__datatable/__send_estimate.blade.php` | 52 | 27 | 0 |  | modal | ✓ |
| `theme/pages/Commercial/Estimate/__datatable/__with_options.blade.php` | 160 | 33 | 2 |  | modal-trigger | ✓ |
| `theme/pages/Commercial/Estimate/__datatable/index.blade.php` | 68 | 1 | 0 |  | Select2, datepicker, DataTables | ✓ |
| `theme/pages/Commercial/Estimate/__detail/estimate.blade.php` | 116 | 45 | 7 |  |  | ✓ |
| `theme/pages/Commercial/Estimate/__detail/estimate_detail_top.blade.php` | 17 | 11 | 2 |  |  | ✓ |
| `theme/pages/Commercial/Estimate/__detail/index.blade.php` | 57 | 1 | 0 |  |  | ✓ |
| `theme/pages/Commercial/Estimate/__edit/__add_article.blade.php` | 80 | 34 | 6 |  |  | ✓ |
| `theme/pages/Commercial/Estimate/__edit/__delete_article_ajax.blade.php` | 29 | 0 | 0 |  |  | ✓ |
| `theme/pages/Commercial/Estimate/__edit/__edit_articles.blade.php` | 8 | 0 | 0 |  |  | ✓ |
| `theme/pages/Commercial/Estimate/__edit/__edit_tickets.blade.php` | 29 | 6 | 0 |  | Select2 | ✓ |
| `theme/pages/Commercial/Estimate/__edit/__estimate_actions.blade.php` | 68 | 24 | 25 |  | modal-trigger | ✓ |
| `theme/pages/Commercial/Estimate/__edit/__estimate_historique.blade.php` | 28 | 9 | 0 |  | collapse/accordion | ✓ |
| `theme/pages/Commercial/Estimate/__edit/__form_edit.blade.php` | 227 | 96 | 2 |  | Select2, datepicker | ✓ |
| `theme/pages/Commercial/Estimate/__edit/__info.blade.php` | 50 | 13 | 0 |  |  | ✓ |
| `theme/pages/Commercial/Estimate/__edit/__print_document.blade.php` | 31 | 16 | 0 |  | modal | ✓ |
| `theme/pages/Commercial/Estimate/__edit/__update_article_ajax.blade.php` | 45 | 0 | 0 |  |  | — |
| `theme/pages/Commercial/Estimate/__edit/index.blade.php` | 73 | 1 | 0 |  | Select2, datepicker, SweetAlert2 | ✓ |
| `theme/pages/Commercial/Estimate/index.blade.php` | 1 | 0 | 0 |  |  | ✓ |
| `theme/pages/Commercial/Estimate/section_0_title.blade.php` | 20 | 11 | 2 |  |  | ✓ |

#### Commercial/Invoice

| File | Lines | BS classes | Skote classes | BS4 leftovers | JS components / plugins | Reachable |
|---|---:|---:|---:|---:|---|:---:|
| `theme/pages/Commercial/Invoice/__create/__add_articles.blade.php` | 82 | 34 | 6 |  |  | ✓ |
| `theme/pages/Commercial/Invoice/__create/__form_create.blade.php` | 167 | 69 | 2 |  | Select2, datepicker | ✓ |
| `theme/pages/Commercial/Invoice/__create/__info.blade.php` | 74 | 26 | 0 |  | Select2 | — |
| `theme/pages/Commercial/Invoice/__create/__send_invoice_section.blade.php` | 10 | 2 | 1 |  |  | — |
| `theme/pages/Commercial/Invoice/__create/b_info.blade.php` | 30 | 14 | 0 |  |  | ✓ |
| `theme/pages/Commercial/Invoice/__create/index.blade.php` | 81 | 1 | 0 |  | Select2, datepicker | ✓ |
| `theme/pages/Commercial/Invoice/__create_from_estimate/__add_articles.blade.php` | 87 | 31 | 3 |  |  | ✓ |
| `theme/pages/Commercial/Invoice/__create_from_estimate/__form_create.blade.php` | 175 | 80 | 4 |  | Select2, datepicker | ✓ |
| `theme/pages/Commercial/Invoice/__create_from_estimate/__info.blade.php` | 81 | 20 | 0 |  |  | ✓ |
| `theme/pages/Commercial/Invoice/__create_from_estimate/__ticket.blade.php` | 12 | 6 | 0 |  | Select2 | ✓ |
| `theme/pages/Commercial/Invoice/__create_from_estimate/__tickets.blade.php` | 30 | 6 | 0 |  | Select2 | ✓ |
| `theme/pages/Commercial/Invoice/__create_from_estimate/index.blade.php` | 41 | 1 | 0 |  | Select2, datepicker | ✓ |
| `theme/pages/Commercial/Invoice/__datatable/__add_payment.blade.php` | 18 | 9 | 0 |  | modal | ✓ |
| `theme/pages/Commercial/Invoice/__datatable/__add_payment_form.blade.php` | 34 | 15 | 2 |  |  | ✓ |
| `theme/pages/Commercial/Invoice/__datatable/__add_payment_info.blade.php` | 64 | 25 | 0 |  |  | ✓ |
| `theme/pages/Commercial/Invoice/__datatable/__default.blade.php` | 113 | 28 | 5 |  |  | — |
| `theme/pages/Commercial/Invoice/__datatable/__filters.blade.php` | 163 | 62 | 0 |  | dropdown, Select2, datepicker | — |
| `theme/pages/Commercial/Invoice/__datatable/__invoices_table.blade.php` | 17 | 0 | 0 |  |  | ✓ |
| `theme/pages/Commercial/Invoice/__datatable/__new_filter.blade.php` | 161 | 62 | 0 |  | dropdown, Select2, datepicker | ✓ |
| `theme/pages/Commercial/Invoice/__datatable/__payment_detail_modal.blade.php` | 40 | 24 | 0 |  | modal | — |
| `theme/pages/Commercial/Invoice/__datatable/__send_invoice.blade.php` | 52 | 27 | 0 |  | modal | ✓ |
| `theme/pages/Commercial/Invoice/__datatable/__show_invoice_tickets.blade.php` | 18 | 9 | 0 |  | modal | — |
| `theme/pages/Commercial/Invoice/__datatable/__show_invoice_tickets_table.blade.php` | 52 | 10 | 0 |  |  | — |
| `theme/pages/Commercial/Invoice/__datatable/__top.blade.php` | 21 | 12 | 1 |  |  | — |
| `theme/pages/Commercial/Invoice/__datatable/__with_options.blade.php` | 176 | 48 | 4 |  | modal-trigger | ✓ |
| `theme/pages/Commercial/Invoice/__datatable/index.blade.php` | 70 | 1 | 0 |  | Select2, datepicker, DataTables | ✓ |
| `theme/pages/Commercial/Invoice/__detail/index.blade.php` | 57 | 1 | 0 |  |  | ✓ |
| `theme/pages/Commercial/Invoice/__detail/invoice.blade.php` | 116 | 45 | 7 |  |  | ✓ |
| `theme/pages/Commercial/Invoice/__detail/invoice_detail_top.blade.php` | 17 | 11 | 2 |  |  | ✓ |
| `theme/pages/Commercial/Invoice/__edit/__add_articles.blade.php` | 80 | 34 | 6 |  |  | ✓ |
| `theme/pages/Commercial/Invoice/__edit/__delete_article_invoice_ajax.blade.php` | 31 | 0 | 0 |  |  | ✓ |
| `theme/pages/Commercial/Invoice/__edit/__edit_articles.blade.php` | 8 | 0 | 0 |  |  | ✓ |
| `theme/pages/Commercial/Invoice/__edit/__form_edit.blade.php` | 245 | 93 | 4 |  | Select2, datepicker | ✓ |
| `theme/pages/Commercial/Invoice/__edit/__historique_invoice.blade.php` | 28 | 9 | 0 |  | collapse/accordion | ✓ |
| `theme/pages/Commercial/Invoice/__edit/__info.blade.php` | 60 | 19 | 0 |  |  | ✓ |
| `theme/pages/Commercial/Invoice/__edit/__info_with_tickets.blade.php` | 34 | 13 | 0 |  |  | ✓ |
| `theme/pages/Commercial/Invoice/__edit/__invoice_actions.blade.php` | 51 | 18 | 17 |  | modal-trigger | ✓ |
| `theme/pages/Commercial/Invoice/__edit/__print_document.blade.php` | 31 | 16 | 0 |  | modal | ✓ |
| `theme/pages/Commercial/Invoice/__edit/__tickets.blade.php` | 29 | 6 | 0 |  | Select2 | ✓ |
| `theme/pages/Commercial/Invoice/__edit/b_info.blade.php` | 30 | 14 | 0 |  |  | ✓ |
| `theme/pages/Commercial/Invoice/__edit/index.blade.php` | 70 | 1 | 0 |  | Select2, datepicker, SweetAlert2 | ✓ |
| `theme/pages/Commercial/Invoice/__js.blade.php` | 167 | 0 | 0 |  | Select2 | ✓ |
| `theme/pages/Commercial/Invoice/index.blade.php` | 1 | 0 | 0 |  |  | ✓ |
| `theme/pages/Commercial/Invoice/section_0_title.blade.php` | 20 | 11 | 2 |  |  | ✓ |

#### Commercial/InvoiceAvoir

| File | Lines | BS classes | Skote classes | BS4 leftovers | JS components / plugins | Reachable |
|---|---:|---:|---:|---:|---|:---:|
| `theme/pages/Commercial/InvoiceAvoir/__create_avoir/__add_articles.blade.php` | 71 | 29 | 6 |  |  | ✓ |
| `theme/pages/Commercial/InvoiceAvoir/__create_avoir/__form_create.blade.php` | 161 | 65 | 4 |  | Select2, datepicker | ✓ |
| `theme/pages/Commercial/InvoiceAvoir/__create_avoir/__info.blade.php` | 79 | 24 | 0 |  | Select2 | — |
| `theme/pages/Commercial/InvoiceAvoir/__create_avoir/__send_invoice_section.blade.php` | 10 | 2 | 1 |  |  | — |
| `theme/pages/Commercial/InvoiceAvoir/__create_avoir/index.blade.php` | 63 | 1 | 0 |  | Select2, datepicker | ✓ |
| `theme/pages/Commercial/InvoiceAvoir/__datatable/__default.blade.php` | 113 | 28 | 5 |  |  | — |
| `theme/pages/Commercial/InvoiceAvoir/__datatable/__filters.blade.php` | 154 | 55 | 0 |  | dropdown, Select2, datepicker | ✓ |
| `theme/pages/Commercial/InvoiceAvoir/__datatable/__invoices_table.blade.php` | 12 | 0 | 0 |  |  | ✓ |
| `theme/pages/Commercial/InvoiceAvoir/__datatable/__payment_detail_modal.blade.php` | 28 | 20 | 0 |  | modal | ✓ |
| `theme/pages/Commercial/InvoiceAvoir/__datatable/__send_invoice_avoir.blade.php` | 52 | 27 | 0 |  | modal | ✓ |
| `theme/pages/Commercial/InvoiceAvoir/__datatable/__with_options.blade.php` | 150 | 18 | 4 |  | modal-trigger | ✓ |
| `theme/pages/Commercial/InvoiceAvoir/__datatable/index.blade.php` | 71 | 1 | 0 |  | Select2, datepicker, DataTables | ✓ |
| `theme/pages/Commercial/InvoiceAvoir/__detail/index.blade.php` | 57 | 1 | 0 |  |  | ✓ |
| `theme/pages/Commercial/InvoiceAvoir/__detail/invoice.blade.php` | 116 | 46 | 8 |  |  | ✓ |
| `theme/pages/Commercial/InvoiceAvoir/__detail/invoice_detail_top.blade.php` | 18 | 11 | 2 |  |  | ✓ |
| `theme/pages/Commercial/InvoiceAvoir/__edit/__add_articles.blade.php` | 71 | 29 | 6 |  |  | ✓ |
| `theme/pages/Commercial/InvoiceAvoir/__edit/__delete_article_invoice_avoir_ajax.blade.php` | 31 | 0 | 0 |  |  | ✓ |
| `theme/pages/Commercial/InvoiceAvoir/__edit/__edit_articles.blade.php` | 8 | 0 | 0 |  |  | ✓ |
| `theme/pages/Commercial/InvoiceAvoir/__edit/__form_edit.blade.php` | 189 | 81 | 4 |  | Select2, datepicker | ✓ |
| `theme/pages/Commercial/InvoiceAvoir/__edit/__historique_avoir.blade.php` | 28 | 9 | 0 |  | collapse/accordion | ✓ |
| `theme/pages/Commercial/InvoiceAvoir/__edit/__info.blade.php` | 61 | 25 | 0 |  |  | ✓ |
| `theme/pages/Commercial/InvoiceAvoir/__edit/__invoice_avoir_actions.blade.php` | 49 | 18 | 17 |  | modal-trigger | ✓ |
| `theme/pages/Commercial/InvoiceAvoir/__edit/__print_document.blade.php` | 31 | 16 | 0 |  | modal | ✓ |
| `theme/pages/Commercial/InvoiceAvoir/__edit/index.blade.php` | 61 | 1 | 0 |  | Select2, datepicker, SweetAlert2 | ✓ |
| `theme/pages/Commercial/InvoiceAvoir/__js.blade.php` | 166 | 0 | 0 |  | Select2 | ✓ |
| `theme/pages/Commercial/InvoiceAvoir/index.blade.php` | 1 | 0 | 0 |  |  | — |
| `theme/pages/Commercial/InvoiceAvoir/section_0_title.blade.php` | 20 | 11 | 2 |  |  | ✓ |

#### Commercial/Provider

| File | Lines | BS classes | Skote classes | BS4 leftovers | JS components / plugins | Reachable |
|---|---:|---:|---:|---:|---|:---:|
| `theme/pages/Commercial/Provider/__create/form_2.blade.php` | 143 | 56 | 2 |  |  | ✓ |
| `theme/pages/Commercial/Provider/__create/index.blade.php` | 24 | 1 | 0 |  |  | ✓ |
| `theme/pages/Commercial/Provider/__datatable/__providers_table.blade.php` | 2 | 0 | 0 |  |  | ✓ |
| `theme/pages/Commercial/Provider/__datatable/__with_options.blade.php` | 88 | 25 | 3 |  |  | ✓ |
| `theme/pages/Commercial/Provider/__datatable/index.blade.php` | 49 | 1 | 0 |  | DataTables | ✓ |
| `theme/pages/Commercial/Provider/__edit/__edit_emails.blade.php` | 62 | 30 | 4 |  | modal | ✓ |
| `theme/pages/Commercial/Provider/__edit/__edit_phones.blade.php` | 62 | 30 | 4 |  | modal | ✓ |
| `theme/pages/Commercial/Provider/__edit/form_2.blade.php` | 156 | 60 | 2 |  | modal-trigger | ✓ |
| `theme/pages/Commercial/Provider/__edit/index.blade.php` | 87 | 1 | 0 |  |  | ✓ |
| `theme/pages/Commercial/Provider/index.blade.php` | 16 | 1 | 0 |  |  | — |
| `theme/pages/Commercial/Provider/section_0_page_title.blade.php` | 20 | 11 | 2 |  |  | ✓ |

#### Contact

| File | Lines | BS classes | Skote classes | BS4 leftovers | JS components / plugins | Reachable |
|---|---:|---:|---:|---:|---|:---:|
| `theme/pages/Contact/__grid/index.blade.php` | 3 | 0 | 0 |  |  | — |
| `theme/pages/Contact/__grid/section_a_contacts.blade.php` | 125 | 112 | 29 |  |  | — |
| `theme/pages/Contact/__list/index.blade.php` | 2 | 0 | 0 |  |  | ✓ |
| `theme/pages/Contact/__list/section_a_contacts.blade.php` | 311 | 189 | 54 |  |  | ✓ |
| `theme/pages/Contact/__profile/index.blade.php` | 1 | 0 | 0 |  |  | — |
| `theme/pages/Contact/index.blade.php` | 13 | 1 | 0 |  |  | ✓ |
| `theme/pages/Contact/section_0_page_title.blade.php` | 17 | 11 | 2 |  |  | ✓ |

#### Diagnostic

| File | Lines | BS classes | Skote classes | BS4 leftovers | JS components / plugins | Reachable |
|---|---:|---:|---:|---:|---|:---:|
| `theme/pages/Diagnostic/__admin/__datatable/__default.blade.php` | 123 | 41 | 7 | 2 |  | — |
| `theme/pages/Diagnostic/__admin/__datatable/__tickets_table.blade.php` | 4 | 0 | 0 |  |  | — |
| `theme/pages/Diagnostic/__admin/__datatable/__with_options.blade.php` | 119 | 24 | 5 | 1 |  | — |
| `theme/pages/Diagnostic/__admin/__datatable/index.blade.php` | 52 | 1 | 0 |  | DataTables | — |
| `theme/pages/Diagnostic/__admin/__tap_view/__tickets.blade.php` | 2 | 0 | 0 |  |  | — |
| `theme/pages/Diagnostic/__admin/__tap_view/index.blade.php` | 4 | 0 | 0 |  |  | — |
| `theme/pages/Diagnostic/__admin/__tap_view/tables/__taps.blade.php` | 41 | 27 | 0 |  | tabs | — |
| `theme/pages/Diagnostic/__admin/__tap_view/tables/__taps_2.blade.php` | 25 | 22 | 6 | 1 | tabs | — |
| `theme/pages/Diagnostic/__admin/__tap_view/tables/diagnistique-attend-bc.blade.php` | 69 | 11 | 2 |  |  | — |
| `theme/pages/Diagnostic/__admin/__tap_view/tables/diagnistique-attend.blade.php` | 66 | 13 | 2 |  |  | — |
| `theme/pages/Diagnostic/__admin/__tap_view/tables/diagnistique-non.blade.php` | 62 | 8 | 2 |  |  | — |
| `theme/pages/Diagnostic/__admin/index.blade.php` | 33 | 1 | 0 |  | Select2, datepicker | ✓ |
| `theme/pages/Diagnostic/__admin/title.blade.php` | 17 | 11 | 2 |  |  | ✓ |
| `theme/pages/Diagnostic/__list/__pagination.blade.php` | 28 | 23 | 0 |  |  | — |
| `theme/pages/Diagnostic/__list/diagnostic.blade.php` | 136 | 97 | 12 |  |  | — |
| `theme/pages/Diagnostic/__list/index.blade.php` | 3 | 0 | 0 |  |  | — |
| `theme/pages/Diagnostic/__tap_view/__tickets.blade.php` | 2 | 0 | 0 |  |  | — |
| `theme/pages/Diagnostic/__tap_view/index.blade.php` | 4 | 0 | 0 |  |  | — |
| `theme/pages/Diagnostic/__tap_view/tables/__taps.blade.php` | 93 | 59 | 0 |  | tabs | — |
| `theme/pages/Diagnostic/__tap_view/tables/__taps_2.blade.php` | 25 | 22 | 6 | 1 | tabs | — |
| `theme/pages/Diagnostic/__tap_view/tables/diagnistique-a-reparer.blade.php` | 75 | 12 | 2 |  |  | ✓ |
| `theme/pages/Diagnostic/__tap_view/tables/diagnistique-attend-bc.blade.php` | 79 | 11 | 2 |  |  | ✓ |
| `theme/pages/Diagnostic/__tap_view/tables/diagnistique-cancled.blade.php` | 73 | 12 | 2 |  |  | ✓ |
| `theme/pages/Diagnostic/__tap_view/tables/diagnistique-encours-de-reparation.blade.php` | 74 | 12 | 2 |  |  | ✓ |
| `theme/pages/Diagnostic/__tap_view/tables/diagnistique-open.blade.php` | 78 | 13 | 2 |  |  | ✓ |
| `theme/pages/Diagnostic/__tap_view/tables/diagnistique-pret-a-livre.blade.php` | 76 | 12 | 2 |  |  | ✓ |
| `theme/pages/Diagnostic/__tap_view/tables/diagnistique-wait.blade.php` | 77 | 13 | 2 |  |  | ✓ |
| `theme/pages/Diagnostic/index.blade.php` | 157 | 76 | 0 |  | tabs, DataTables | ✓ |
| `theme/pages/Diagnostic/section_0_page_title.blade.php` | 17 | 11 | 2 |  |  | ✓ |

#### Email

| File | Lines | BS classes | Skote classes | BS4 leftovers | JS components / plugins | Reachable |
|---|---:|---:|---:|---:|---|:---:|
| `theme/pages/Email/compose/composeModal.blade.php` | 33 | 20 | 0 |  | modal | ✓ |
| `theme/pages/Email/emails.blade.php` | 413 | 141 | 199 | 1 | dropdown, modal-trigger | ✓ |
| `theme/pages/Email/index.blade.php` | 15 | 1 | 0 |  |  | ✓ |
| `theme/pages/Email/read/content.blade.php` | 87 | 64 | 19 |  | dropdown | ✓ |
| `theme/pages/Email/read/index.blade.php` | 16 | 1 | 0 |  |  | ✓ |
| `theme/pages/Email/section_0_page_title.blade.php` | 17 | 11 | 2 |  |  | ✓ |

#### Excel

| File | Lines | BS classes | Skote classes | BS4 leftovers | JS components / plugins | Reachable |
|---|---:|---:|---:|---:|---|:---:|
| `theme/pages/Excel/__content.blade.php` | 140 | 57 | 7 | 1 | dropdown | ✓ |
| `theme/pages/Excel/__folders.blade.php` | 263 | 235 | 24 |  | dropdown | — |
| `theme/pages/Excel/__rightbar.blade.php` | 127 | 117 | 18 |  |  | ✓ |
| `theme/pages/Excel/__section_title.blade.php` | 15 | 11 | 2 |  |  | ✓ |
| `theme/pages/Excel/__sidebar.blade.php` | 85 | 57 | 7 | 1 | dropdown, alert-dismiss | ✓ |
| `theme/pages/Excel/index.blade.php` | 16 | 1 | 0 |  |  | ✓ |

#### FileManager

| File | Lines | BS classes | Skote classes | BS4 leftovers | JS components / plugins | Reachable |
|---|---:|---:|---:|---:|---|:---:|
| `theme/pages/FileManager/index.blade.php` | 1 | 0 | 0 |  |  | — |

#### Home

| File | Lines | BS classes | Skote classes | BS4 leftovers | JS components / plugins | Reachable |
|---|---:|---:|---:|---:|---|:---:|
| `theme/pages/Home/filter_js.blade.php` | 46 | 0 | 0 |  |  | — |
| `theme/pages/Home/index.blade.php` | 136 | 6 | 0 |  | datepicker | ✓ |
| `theme/pages/Home/sections/section_0_page_title.blade.php` | 17 | 11 | 2 |  |  | ✓ |
| `theme/pages/Home/sections/section_a_a.blade.php` | 89 | 35 | 17 |  |  | — |
| `theme/pages/Home/sections/section_a_chart.blade.php` | 18 | 9 | 2 |  |  | ✓ |
| `theme/pages/Home/sections/section_a_orders.blade.php` | 72 | 42 | 15 |  |  | ✓ |
| `theme/pages/Home/sections/section_a_period.blade.php` | 99 | 44 | 1 |  | dropdown, datepicker | ✓ |
| `theme/pages/Home/sections/section_b_activity.blade.php` | 70 | 41 | 23 |  |  | — |
| `theme/pages/Home/sections/section_b_b.blade.php` | 70 | 42 | 15 |  |  | ✓ |
| `theme/pages/Home/sections/section_b_social_source.blade.php` | 54 | 54 | 11 |  |  | — |
| `theme/pages/Home/sections/section_b_top.blade.php` | 60 | 38 | 0 |  |  | — |
| `theme/pages/Home/sections/section_c_c.blade.php` | 72 | 40 | 0 |  |  | ✓ |
| `theme/pages/Home/sections/section_c_transactions.blade.php` | 56 | 29 | 4 |  | modal-trigger | — |
| `theme/pages/Home/sections/section_dd.blade.php` | 74 | 37 | 4 |  | datepicker | — |

#### Importer

| File | Lines | BS classes | Skote classes | BS4 leftovers | JS components / plugins | Reachable |
|---|---:|---:|---:|---:|---|:---:|
| `theme/pages/Importer/__form.blade.php` | 39 | 22 | 0 |  |  | — |
| `theme/pages/Importer/index.blade.php` | 29 | 1 | 0 |  |  | ✓ |
| `theme/pages/Importer/section_0_page_title.blade.php` | 20 | 10 | 2 |  |  | ✓ |

#### PermissionRole

| File | Lines | BS classes | Skote classes | BS4 leftovers | JS components / plugins | Reachable |
|---|---:|---:|---:|---:|---|:---:|
| `theme/pages/PermissionRole/Permission/__permissions.blade.php` | 63 | 31 | 2 |  | alert-dismiss | ✓ |
| `theme/pages/PermissionRole/Permission/_items.blade.php` | 110 | 48 | 4 |  |  | ✓ |
| `theme/pages/PermissionRole/Permission/index.blade.php` | 16 | 1 | 0 |  |  | ✓ |
| `theme/pages/PermissionRole/Role/_items.blade.php` | 107 | 48 | 4 |  |  | ✓ |
| `theme/pages/PermissionRole/Role/index.blade.php` | 15 | 1 | 0 |  |  | ✓ |
| `theme/pages/PermissionRole/section_0_page_title.blade.php` | 16 | 11 | 2 |  |  | ✓ |

#### Profile

| File | Lines | BS classes | Skote classes | BS4 leftovers | JS components / plugins | Reachable |
|---|---:|---:|---:|---:|---|:---:|
| `theme/pages/Profile/index.blade.php` | 18 | 1 | 0 |  |  | ✓ |
| `theme/pages/Profile/profile.blade.php` | 285 | 133 | 33 |  |  | ✓ |
| `theme/pages/Profile/section_0_page_title.blade.php` | 18 | 11 | 2 |  |  | ✓ |
| `theme/pages/Profile/settings/_company_form.blade.php` | 32 | 15 | 1 |  |  | ✓ |
| `theme/pages/Profile/settings/_user_info_form.blade.php` | 107 | 62 | 1 |  |  | ✓ |
| `theme/pages/Profile/settings/index.blade.php` | 18 | 1 | 0 |  |  | ✓ |
| `theme/pages/Profile/settings/settings.blade.php` | 39 | 25 | 0 |  | tabs | ✓ |

#### Reparation

| File | Lines | BS classes | Skote classes | BS4 leftovers | JS components / plugins | Reachable |
|---|---:|---:|---:|---:|---|:---:|
| `theme/pages/Reparation/__list/__pagination.blade.php` | 28 | 23 | 0 |  |  | — |
| `theme/pages/Reparation/__list/index.blade.php` | 3 | 0 | 0 |  |  | — |
| `theme/pages/Reparation/__list/reparation.blade.php` | 140 | 106 | 12 | 3 |  | — |
| `theme/pages/Reparation/__single/detail.blade.php` | 2 | 0 | 0 |  |  | ✓ |
| `theme/pages/Reparation/__single/index.blade.php` | 56 | 1 | 0 |  | SweetAlert2, TinyMCE | ✓ |
| `theme/pages/Reparation/__single/section_a.blade.php` | 65 | 26 | 4 |  |  | ✓ |
| `theme/pages/Reparation/__single/section_b.blade.php` | 52 | 17 | 0 | 2 |  | ✓ |
| `theme/pages/Reparation/index.blade.php` | 13 | 1 | 0 |  |  | — |
| `theme/pages/Reparation/section_0_page_title.blade.php` | 1 | 0 | 0 |  |  | ✓ |

#### Report

| File | Lines | BS classes | Skote classes | BS4 leftovers | JS components / plugins | Reachable |
|---|---:|---:|---:|---:|---|:---:|
| `theme/pages/Report/__datatable/__with_options.blade.php` | 62 | 28 | 4 |  |  | ✓ |
| `theme/pages/Report/__datatable/graphs.blade.php` | 33 | 16 | 0 |  |  | ✓ |
| `theme/pages/Report/__datatable/index.blade.php` | 61 | 1 | 0 |  | DataTables | ✓ |
| `theme/pages/Report/__datatable/periode_filter.blade.php` | 52 | 24 | 1 |  | dropdown | — |
| `theme/pages/Report/section_0_page_title.blade.php` | 19 | 9 | 2 |  |  | ✓ |

#### Setting

| File | Lines | BS classes | Skote classes | BS4 leftovers | JS components / plugins | Reachable |
|---|---:|---:|---:|---:|---|:---:|
| `theme/pages/Setting/__menu.blade.php` | 10 | 6 | 5 | 2 |  | ✓ |
| `theme/pages/Setting/compose/composeModal.blade.php` | 33 | 20 | 0 |  | modal | ✓ |
| `theme/pages/Setting/emails.blade.php` | 96 | 65 | 27 |  | dropdown | ✓ |
| `theme/pages/Setting/index.blade.php` | 16 | 1 | 0 |  |  | ✓ |
| `theme/pages/Setting/read/content.blade.php` | 168 | 136 | 36 | 1 | dropdown, modal-trigger | — |
| `theme/pages/Setting/read/index.blade.php` | 26 | 1 | 0 |  | Select2, TinyMCE | ✓ |
| `theme/pages/Setting/section_0_page_title.blade.php` | 18 | 11 | 2 |  |  | ✓ |

#### Ticket

| File | Lines | BS classes | Skote classes | BS4 leftovers | JS components / plugins | Reachable |
|---|---:|---:|---:|---:|---|:---:|
| `theme/pages/Ticket/__create/__create_client_modal.blade.php` | 19 | 12 | 0 |  | modal | ✓ |
| `theme/pages/Ticket/__create/__create_client_modal_form.blade.php` | 94 | 36 | 2 |  |  | ✓ |
| `theme/pages/Ticket/__create/form.blade.php` | 134 | 63 | 0 |  | modal-trigger, Select2 | ✓ |
| `theme/pages/Ticket/__create/index.blade.php` | 83 | 1 | 0 |  | Select2, TinyMCE | ✓ |
| `theme/pages/Ticket/__datatable/__default.blade.php` | 123 | 41 | 7 | 2 |  | — |
| `theme/pages/Ticket/__datatable/__filters.blade.php` | 209 | 61 | 0 |  | dropdown, Select2, datepicker | ✓ |
| `theme/pages/Ticket/__datatable/__js_filters.blade.php` | 202 | 0 | 0 |  | Select2 | ✓ |
| `theme/pages/Ticket/__datatable/__settings_modal.blade.php` | 54 | 27 | 2 |  | modal | ✓ |
| `theme/pages/Ticket/__datatable/__tickets_table.blade.php` | 7 | 0 | 0 |  |  | ✓ |
| `theme/pages/Ticket/__datatable/__with_options.blade.php` | 168 | 40 | 6 |  |  | ✓ |
| `theme/pages/Ticket/__datatable/index.blade.php` | 66 | 1 | 0 |  | Select2, datepicker, DataTables | ✓ |
| `theme/pages/Ticket/__diagnostic/detail.blade.php` | 242 | 76 | 3 | 7 | carousel | ✓ |
| `theme/pages/Ticket/__diagnostic/index.blade.php` | 76 | 13 | 1 |  | SweetAlert2, TinyMCE | ✓ |
| `theme/pages/Ticket/__edit/__form_edit.blade.php` | 122 | 56 | 4 |  | datepicker | ✓ |
| `theme/pages/Ticket/__edit/__info.blade.php` | 41 | 17 | 0 |  | Select2 | ✓ |
| `theme/pages/Ticket/__edit/__reassignment.blade.php` | 131 | 34 | 2 |  | TinyMCE | ✓ |
| `theme/pages/Ticket/__edit/__ticket_actions.blade.php` | 76 | 22 | 13 |  |  | ✓ |
| `theme/pages/Ticket/__edit/index.blade.php` | 57 | 1 | 0 |  | Select2, datepicker, SweetAlert2, TinyMCE | ✓ |
| `theme/pages/Ticket/__edit_old/form.blade.php` | 56 | 30 | 0 |  |  | — |
| `theme/pages/Ticket/__edit_old/index.blade.php` | 25 | 1 | 0 |  | TinyMCE | — |
| `theme/pages/Ticket/__historical/index.blade.php` | 17 | 1 | 0 |  |  | ✓ |
| `theme/pages/Ticket/__historical/style_1.blade.php` | 110 | 55 | 22 |  |  | — |
| `theme/pages/Ticket/__historical/style_2.blade.php` | 57 | 16 | 3 |  |  | ✓ |
| `theme/pages/Ticket/__invoiceable/__datatable/__tickets_table.blade.php` | 5 | 0 | 0 |  |  | ✓ |
| `theme/pages/Ticket/__invoiceable/__datatable/__with_options.blade.php` | 106 | 22 | 1 |  |  | — |
| `theme/pages/Ticket/__invoiceable/__datatable/__with_options2.blade.php` | 96 | 21 | 1 |  |  | ✓ |
| `theme/pages/Ticket/__invoiceable/__datatable/index.blade.php` | 52 | 1 | 0 |  | DataTables | ✓ |
| `theme/pages/Ticket/__media/__files.blade.php` | 147 | 53 | 5 |  |  | ✓ |
| `theme/pages/Ticket/__media/index.blade.php` | 16 | 1 | 0 |  |  | ✓ |
| `theme/pages/Ticket/__new_create/__form_new_create.blade.php` | 134 | 62 | 4 |  | datepicker | — |
| `theme/pages/Ticket/__new_create/__info.blade.php` | 18 | 7 | 0 |  |  | — |
| `theme/pages/Ticket/__new_create/__ticket_actions.blade.php` | 51 | 20 | 13 |  |  | — |
| `theme/pages/Ticket/__new_create/index.blade.php` | 38 | 1 | 0 |  | Select2, datepicker, TinyMCE | — |
| `theme/pages/Ticket/__normal_table/__filters.blade.php` | 166 | 38 | 1 |  | Select2, datepicker | — |
| `theme/pages/Ticket/__normal_table/index.blade.php` | 43 | 1 | 0 |  | Select2, datepicker, DataTables | — |
| `theme/pages/Ticket/__normal_table/table.blade.php` | 154 | 55 | 3 |  |  | — |
| `theme/pages/Ticket/__pret_livre/__datatable/__filters.blade.php` | 143 | 47 | 0 |  | dropdown, Select2, datepicker | ✓ |
| `theme/pages/Ticket/__pret_livre/__datatable/__js_filters.blade.php` | 146 | 0 | 0 |  | Select2 | ✓ |
| `theme/pages/Ticket/__pret_livre/__datatable/__tickets_table.blade.php` | 5 | 0 | 0 |  |  | ✓ |
| `theme/pages/Ticket/__pret_livre/__datatable/__with_options.blade.php` | 123 | 22 | 1 |  | modal-trigger | ✓ |
| `theme/pages/Ticket/__pret_livre/__datatable/confirm_form.blade.php` | 34 | 15 | 2 |  |  | ✓ |
| `theme/pages/Ticket/__pret_livre/__datatable/confirm_form_info.blade.php` | 52 | 21 | 0 |  | datepicker | ✓ |
| `theme/pages/Ticket/__pret_livre/__datatable/confirme.blade.php` | 17 | 9 | 0 |  | modal | ✓ |
| `theme/pages/Ticket/__pret_livre/__datatable/confirme_admin.blade.php` | 27 | 16 | 2 |  | modal | ✓ |
| `theme/pages/Ticket/__pret_livre/__datatable/index.blade.php` | 74 | 1 | 0 |  | Select2, datepicker, DataTables | ✓ |
| `theme/pages/Ticket/__single_v2/article_detail.blade.php` | 134 | 51 | 2 | 1 | pills | ✓ |
| `theme/pages/Ticket/__single_v2/index.blade.php` | 23 | 1 | 0 |  |  | ✓ |
| `theme/pages/Ticket/__single_v2/section_a_title.blade.php` | 16 | 11 | 2 |  |  | ✓ |
| `theme/pages/Ticket/__single_v2/section_attached_files.blade.php` | 96 | 38 | 9 |  |  | ✓ |
| `theme/pages/Ticket/__single_v2/section_bas.blade.php` | 91 | 83 | 0 |  |  | — |
| `theme/pages/Ticket/__single_v2/section_logs.blade.php` | 34 | 12 | 1 |  |  | — |
| `theme/pages/Ticket/__single_v2/section_ticket_info.blade.php` | 60 | 5 | 0 |  |  | ✓ |
| `theme/pages/Ticket/index.blade.php` | 19 | 1 | 0 |  |  | ✓ |
| `theme/pages/Ticket/section_0_page_title.blade.php` | 23 | 11 | 2 |  |  | ✓ |

#### TicketRapport

| File | Lines | BS classes | Skote classes | BS4 leftovers | JS components / plugins | Reachable |
|---|---:|---:|---:|---:|---|:---:|
| `theme/pages/TicketRapport/Edition/Edit/__form_edit.blade.php` | 152 | 78 | 4 |  | datepicker | ✓ |
| `theme/pages/TicketRapport/Edition/Edit/__info.blade.php` | 18 | 7 | 0 |  |  | — |
| `theme/pages/TicketRapport/Edition/Edit/__ticket_actions.blade.php` | 51 | 20 | 13 |  |  | — |
| `theme/pages/TicketRapport/Edition/Edit/index.blade.php` | 37 | 1 | 0 |  | Select2, datepicker, TinyMCE | ✓ |
| `theme/pages/TicketRapport/Edition/__datatable/__clients_table.blade.php` | 1 | 0 | 0 |  |  | ✓ |
| `theme/pages/TicketRapport/Edition/__datatable/__with_options.blade.php` | 71 | 14 | 0 |  |  | ✓ |
| `theme/pages/TicketRapport/Edition/__datatable/index.blade.php` | 48 | 1 | 0 |  | DataTables | ✓ |
| `theme/pages/TicketRapport/__datatable/__clients_table.blade.php` | 2 | 0 | 0 |  |  | ✓ |
| `theme/pages/TicketRapport/__datatable/__with_options.blade.php` | 57 | 12 | 0 |  |  | ✓ |
| `theme/pages/TicketRapport/__datatable/index.blade.php` | 48 | 1 | 0 |  | DataTables | ✓ |
| `theme/pages/TicketRapport/__edit/__add_phones.blade.php` | 69 | 35 | 2 |  | modal | — |
| `theme/pages/TicketRapport/__edit/__edit_emails.blade.php` | 62 | 30 | 4 |  | modal | — |
| `theme/pages/TicketRapport/__edit/__edit_phones.blade.php` | 58 | 29 | 0 |  |  | — |
| `theme/pages/TicketRapport/__edit/form_2.blade.php` | 185 | 55 | 2 |  | modal-trigger | — |
| `theme/pages/TicketRapport/__edit/index.blade.php` | 90 | 1 | 0 |  |  | — |
| `theme/pages/TicketRapport/__profile/index.blade.php` | 18 | 1 | 0 |  |  | — |
| `theme/pages/TicketRapport/__profile/profile.blade.php` | 245 | 83 | 15 |  |  | — |
| `theme/pages/TicketRapport/section_0_page_title.blade.php` | 19 | 9 | 2 |  |  | ✓ |

#### Warranty

| File | Lines | BS classes | Skote classes | BS4 leftovers | JS components / plugins | Reachable |
|---|---:|---:|---:|---:|---|:---:|
| `theme/pages/Warranty/__datatable/__add_warranty.blade.php` | 111 | 47 | 2 |  | modal, datepicker | ✓ |
| `theme/pages/Warranty/__datatable/__with_options.blade.php` | 106 | 19 | 3 |  |  | ✓ |
| `theme/pages/Warranty/__datatable/index.blade.php` | 51 | 1 | 0 |  | DataTables | ✓ |
| `theme/pages/Warranty/index.blade.php` | 1 | 0 | 0 |  |  | — |
| `theme/pages/Warranty/section_0_page_title.blade.php` | 18 | 10 | 2 |  |  | ✓ |

---

## Appendix B: how the numbers were produced

- **Bootstrap class set:** every class selector in `resources/css/main/css/bootstrap.min.css` (1,983 names; this is Skote's build, so it includes Skote's additions). **Skote class set:** class selectors in `resources/css/main/css/app.css` not already in the first set (446 names). **Icon set:** `icons.min.css`.
- **Per-file counts:** every token inside `class="…"` / `class='…'` attributes of each Blade file, after removing `{{-- --}}` comments and Blade echo or directive fragments inside the attribute. Each token is counted once per occurrence. Tokens built at runtime (`text-{{ $color }}`) are not counted (§7.5). Classes added from JavaScript (DataTables `className`, `addClass(...)`) are not counted.
- **`data-bs-*` counts** (§3) are a plain text search and include commented-out markup.
- **Reachability:** starts from view names found in `app/`, `routes/`, `config/`, `packages/` and `helpers/` (`view()`, `->view()`, `loadView()`, Livewire `render()`). It then follows `@include*`, `@extends`, `@includePart()` (the custom directive in `app/Providers/BladeServiceProvider.php`, which prefixes `theme/pages/`) and `<x-…>` tags. References inside `{{-- --}}` Blade comments and PHP comments are ignored. Error and vendor views count as reachable. A view reached only through a dynamically built name would be reported as unreachable. Re-check every "—" before deleting.
- Versions were taken from file headers in `public/assets/libs/*`, from `composer show` and from `node_modules/*/package.json`.
