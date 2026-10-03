# Bootstrap 5.3.3 Migration Report

## Scope

The target is a single Bootstrap 5.3.3 runtime for active MaintenanceApp web views, styled with the existing Skote theme. This report covers the asset-chain audit and remaining migration work. PDF and email outputs remain separate contexts. No routes, controllers, business rules, database schema, or production records were changed for this frontend work.

## Previous and current integration

| Area | Previous | Current |
| --- | --- | --- |
| CSS | `resources/css/app.css` imported the app-owned `resources/css/main/css/bootstrap.min.css`, which declared 5.0.1 | The same app-owned path now contains Bootstrap 5.3.3 copied from the included Skote Bootstrap source |
| JavaScript | The main theme layout loaded `public/assets/libs/bootstrap/bootstrap.bundle.min.js`, which declared 5.0.1 | The same single shared script path now contains Bootstrap 5.3.3 copied from the included Skote Bootstrap source |
| Build | Vite inputs were the existing `resources/css/app.css` and `resources/js/app.js` | Inputs unchanged; `npm run build` passed after the asset update |
| Dependencies | Bootstrap is not a direct dependency in `package.json` or `package-lock.json` | No npm or Composer dependency was added or changed |
| Theme source | `admin-panel-theme/` contains Bootstrap 5.3.3 | Theme source remains read-only; application runtime points to app-owned copies |

## Runtime audit

- The active application shell loads the Bootstrap CSS through Vite and the Bootstrap JavaScript bundle through `theme/layouts/_parts/vendor-scripts.blade.php`.
- The shell, login, password recovery, and maintenance 503 views use the shared vendor script partial and therefore resolve the same application-owned bundle.
- No Bootstrap CDN reference or second Bootstrap JavaScript script reference was found in application Blade views.
- `public/vendor/laravel_backup_panel/bootstrap.css` declares Bootstrap 4.5.0. No app, route, config, or Blade reference to this published asset was found in the audit. It is a **LEGACY/UNREFERENCED candidate**, retained pending verification of package-provided routes or external consumers; it is not currently included in the MaintenanceApp shell.
- Theme demo assets under `admin-panel-theme/` are not used as runtime URLs.
- Bootstrap headers were checked directly in both active app assets; each reports **5.3.3**. `npm run build` completed successfully.

## Legacy markup and adapters found

The repository-wide Blade scan still has migration work. These occurrences are being tracked rather than bulk-replaced:

| Finding | Scope | Plan |
| --- | --- | --- |
| DataTables Bootstrap 4 adapters and assets (`datatables.net-bs4`, `datatables.net-buttons-bs4`, `datatables.net-responsive-bs4`) | Referenced by 18 Blade views/components. Theme source only provides Bootstrap 4 adapters; public app assets mirror those integrations. | Preserve table behavior while evaluating a compatible DataTables Bootstrap 5 adapter or carefully adapted output. Do not remove the existing adapter before replacement is verified. |
| Bootstrap 4 pagination view calls | Active Role and Permission lists; the old ticket-table fragment is unreferenced and retained. | Updated all three calls to Laravel's Bootstrap 5 pagination view; page links and query retention still need browser QA. |
| `data-toggle="tooltip"` and `data-placement` | Removed from the Category grid fragment when its static demo values were replaced with real category content. | No remaining active-view occurrence in the current scan. |
| `data-toggle="modal"` | Removed from the TUI Calendar new-schedule control after inspecting `public/js/pages/calendar.init.js`; its click handler calls the TUI `openCreationPopup`, and does not depend on Bootstrap's modal data API. | TUI event creation handler is preserved. |
| `data-toggle="fullscreen"` | Renamed in the shared header to the application-specific `data-app-fullscreen`; `resources/js/main.js` listens to that attribute. | Custom fullscreen behavior is preserved and no longer resembles a Bootstrap data API. |
| Bootstrap 4 utility/accessibility classes | Migrated reviewed occurrences of `text-right`, `text-left`, `sr-only`, `badge-pill`, and legacy spacing utilities in the affected active views to Bootstrap 5 equivalents. | Continue scanning and migrating per view batch; do not claim the whole active-view sweep is complete. |
| Bootstrap 4 `form-group` pattern | Replaced in the reviewed Chat search form with Bootstrap 5 spacing utilities. | Continue scanning nested partials and active modules. |

The initial scan also found a separately published Bootstrap 4.5 CSS file under `public/vendor/laravel_backup_panel/`. It is not counted as an active runtime until a reference is found.

## Plugin and JavaScript inventory

| Plugin/runtime | Observed use | Status |
| --- | --- | --- |
| jQuery | Loaded before theme and page scripts; used by the Skote main script and jQuery-based plugins and inline view code | Required; do not remove globally |
| MetisMenu, Waves, SimpleBar | Shared Skote navigation/effects/scroll behavior | Required by current shell; recheck lifecycle during shell modernization |
| DataTables and Buttons/Responsive | Used by client, tickets, commercial documents, diagnosis, reporting, and warranty lists | Required; current Bootstrap integration is the legacy Bootstrap 4 adapter |
| Select2, bootstrap-datepicker, jQuery Repeater | Used by operational and commercial forms/filters | Referenced and required on their owning pages; test after each markup migration |
| SweetAlert2, TinyMCE, Dropzone, Magnific Popup | Used by confirmation, rich-text, upload, and media experiences | Referenced; retain until active usage is mapped and replacements are verified |
| TUI Calendar and ApexCharts | Calendar module and dashboard charts | Referenced; preserve plugin-specific behavior and check Bootstrap/Livewire compatibility |
| Moment, JSZip, PDFMake | Dates and DataTables export actions | Referenced by page features; not Bootstrap runtimes |
| Theme demo-only plugins | Located under `admin-panel-theme/` | Reference only unless separately copied into an app-owned path and justified |

No plugin was removed or replaced during the Bootstrap asset alignment.

## Current Blade migration status

The exhaustive file-level inventory is maintained in `docs/UI_REDESIGN_PLAN.md` and currently covers all **543** files under `resources/views/`. The dashboard, client list/create/edit, ticket workflow and discovered module headers use the shared `x-app.page-header` component. The global shell, category screen, ticket list/detail/create/edit/history/media, ticket diagnosis, repair queue/report, ready-for-delivery flow, warranty list, authentication, and active error screens have received focused presentation passes. Other files remain pending recursive activity and Bootstrap markup review. PDFs and email views are explicitly excluded.

## Verification performed

- `npm run build` — passed after updating the Bootstrap assets.
- `php artisan view:cache --no-interaction` — passed after this shell/category and utility migration batch.
- `npm run build` — passed after this shell/category and utility migration batch.
- `php artisan route:list --name=categories --no-ansi` — existing category index/store/delete routes are registered; route definitions were not changed.
- `git diff --check` — passed after this shell/category and utility migration batch.
- `php artisan route:list --name=clients --no-ansi` — existing client routes remain registered; no route definitions were changed.
- `php artisan view:cache --no-interaction` — passed after the ticket diagnosis and repair-screen changes.
- `php artisan view:cache --no-interaction` — passed again after the authentication and error-page changes.
- `env APP_ENV=testing DB_CONNECTION=sqlite DB_DATABASE=:memory: php artisan test --compact` — one baseline test passed; `Tests\Feature\ExampleTest::example` failed because `/` returned 302 while the example expects 200. The run used in-memory SQLite and did not connect to production.
- `npm run build` — passed after the ticket diagnosis and repair-screen changes.
- `php artisan route:list --name=reparations --no-ansi` — the existing repair index/detail/store/completion routes are registered; route definitions were not changed.
- Delivery and warranty routes/views were traced; delivery forms still submit the existing field names and routes. Warranty `create()` and `store()` controller methods are empty, so no new trigger or workflow was enabled.
- Laravel's installed pagination views were inspected; ticket, role, and permission `links()` calls now use the Bootstrap 5 view.
- `git diff --check` — passed after this batch.
- Browser visual and interactive verification — pending; no browser session is available in this workspace.

## Remaining work

- Finish the file-by-file active/reference classification and recursively trace dynamic Blade includes and Livewire view mappings. A literal scan reaches 384 of 543 Blade files; framework error views and dynamic view names require manual classification.
- Review all active Blade markup against Bootstrap 5.3.3, including partials and components.
- Resolve DataTables Bootstrap 4 adapter use without dropping current search, pagination, responsive behavior, or exports.
- Verify migrated pagination in browser and continue the active-view utility/attribute scan.
- Complete the global shell and every active module using Skote references; track module status in `docs/UI_REDESIGN_PLAN.md`.
- Continue the ticket module through reception and delivery workflow review; list/detail/create/edit/history/media, diagnosis, repair, and ready-for-delivery screens have presentation passes, pending browser and workflow QA.
- Perform browser QA and critical workflow verification when an authenticated local browser session is available.
- Perform the final Bootstrap runtime and legacy-pattern scan; classify remaining third-party, PDF, email, and unreferenced assets.

## Data and behavior guarantees

- Database schema changes: **None**.
- Production data changes: **None**.
- Route changes: **None**.
- Business logic changes: **None**.
