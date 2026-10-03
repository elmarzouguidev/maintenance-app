# MaintenanceApp UI Redesign Plan

## Scope and constraints

This plan covers the active, user-facing Laravel Blade and Livewire application. The inventory is based on repository views, route declarations, controllers, shared components, and theme references. View counts include partials and workflow fragments; they are not counts of unique pages. Route reachability and legacy status must be confirmed as each phase is implemented.

The redesign is presentation-only. Preserve routes and URLs, permissions, authorization, request validation, business rules, workflow transitions, calculations, relationships, and existing production data. No schema changes, migrations, seeders, PDF redesign, or business-code cleanup are part of this plan.

Visual source priority:

1. `docs/UI_DESIGN_SYSTEM.md`
2. Established MaintenanceApp views and components
3. Read-only references in `admin-panel-theme/`
4. New Bootstrap markup adapted to the existing app

The design system guide and `AGENTS.md` already contain the durable Skote, Bootstrap, asset, Livewire, and no-new-framework rules. The existing guidance was clarified in `AGENTS.md`; no duplicate rules were added.

## Phase 1 — Audit (in progress)

- [x] Read project AI rules and `docs/UI_DESIGN_SYSTEM.md`.
- [x] Inventory page folders, layouts, shared components, Livewire classes, asset entry points, and major view plugins.
- [x] Generate a file-level type and migration-status matrix for all 543 Blade files under `resources/views/`.
- [ ] Recursively resolve active routes, controller views, Livewire view names, nested includes/components, and dynamic includes; classify every file as active, excluded output, or demonstrably legacy.
- [ ] Audit all CSS/JS Bootstrap references, package locks, duplicate runtime candidates, and plugin compatibility; document unresolved third-party dependencies.
- [x] Inspect Skote layout, form, table, card, alert, modal, dashboard, and invoice references.
- [x] Record version and integration risks before implementation.

## Application screen inventory

| Area | Repository locations / discovered flows | UI notes and preservation risks |
| --- | --- | --- |
| Authentication | `theme/Authentification`: login, forgot password, reset password | Keep auth routes, guards, validation, and redirect behavior. Adapt Skote `auth-login.html` and recovery examples. |
| Dashboard | `theme/pages/Home`, `theme/livewire/dasboard`, `App\Http\Livewire\Dasboard\Dashboard` | Reuse existing KPI values and chart data. Skote `dashboard-saas.html` is a layout reference only; do not invent metrics. ApexCharts integration needs Livewire lifecycle review. |
| Clients | `theme/pages/Client`: listing, create, edit, profile, contacts/history | Keep current relations and available client actions. Theme references: `contacts-profile.html`, form and table examples. |
| Tickets | `theme/pages/Ticket`: listing, create, edit, detail, history, media, invoiceable and ready-for-delivery lists, diagnosis entry points | Highest operational priority. Preserve ticket URLs, lifecycle, assignment, media, action permissions, and status meanings. Use `pages-timeline.html` only as a visual reference for history. |
| Diagnosis | `theme/pages/Diagnostic`, `theme/pages/Ticket/__diagnostic`, `components/diagnostic` | Separate technician and manager queues without changing access rules. Preserve report editing, repairability, send/confirm actions, and assignment checks. |
| Repair | `theme/pages/Reparation` | Preserve technician ownership, repair reports, completion, attachments, and status transitions. |
| Reception and delivery | Ticket ready-for-delivery views, sidebar reception component, warranty screens | Preserve delivery confirmation and role/permission visibility. |
| Estimates | `theme/pages/Commercial/Estimate`, `theme/livewire/commercial/estimate` | High risk: Livewire form state, ticket conversion, totals, discounts, tax, and document actions must remain unchanged. |
| Invoices | `theme/pages/Commercial/Invoice`, `theme/livewire/commercial/invoice` | High risk: Livewire ticket selection and article editing, totals, payment links, and historical financial values. |
| Credit notes | `theme/pages/Commercial/InvoiceAvoir`, `theme/livewire/commercial/avoir` and `invoice-avoir` | Preserve source invoice links and recorded financial values. |
| Payments and bills | `theme/pages/Commercial/Bill` | Preserve existing payment actions, amount handling, and invoice/client links. |
| Purchase orders | `theme/pages/Commercial/BC`, `theme/livewire/commercial/bon-command` | Preserve provider, article, and order behavior. |
| Delivery slips | `theme/pages/Commercial/BL`, `theme/livewire/commercial/b-l` | Preserve ticket/order relationships and document actions. |
| Suppliers and companies | `theme/pages/Commercial/Provider`, `theme/pages/Commercial/Company` | Keep current CRUD permissions, validation, and commercial relationships. |
| Ticket reports | `theme/pages/TicketRapport` | Preserve report editing, ticket links, generated reports, and authorization. |
| Reports and analytics | `theme/pages/Report` and dashboard reporting sections | Present current aggregates only; avoid new queries or altered calculations during styling. |
| Administration | `theme/pages/Admin`, `PermissionRole`, `Category`, `Setting`, `Profile`, `Backup`, `Warranty` | Preserve role/permission checks, user settings, backup controls, and destructive-action confirmation. |
| Operational utilities | `Calendar`, `Contact`, `Email`, `Excel`, `Importer`, `Chat`, `FileManager` | Review actual route/menu reachability before classifying older fragments as active or legacy. Respect existing plugins and access boundaries. |
| Errors and public screens | `views/errors`, `welcome.blade.php`, plus any routed non-theme views discovered during implementation | Align active pages with the app shell where appropriate; keep public/error behavior intact. |
| PDFs and email | `theme/pdf`, `theme/*_template`, `theme/Emails`, export and mail templates | Intentionally excluded from web UI redesign. Treat generated business documents and email output as separate projects. |

The current `theme/pages` tree contains 22 top-level areas. The Commercial subtree has 8 child areas: Invoice, Estimate, InvoiceAvoir, Bill, BL, BC, Provider, and Company. The inventory includes many nested partials, alternate table variants, and potentially legacy views; do not delete these based on naming or static appearance alone.

## Current foundation and inconsistencies

- Shared shell: `theme/layouts/app.blade.php`, header/navbar/sidebar partials, modal/footer, vendor scripts, Livewire assets, and script stacks.
- Existing shared Blade UI is limited and domain-focused: sidebar, diagnostic layouts/tables/tabs, and form input/loading components. Expand components only after confirming repeated markup and preserving each view's contracts.
- A shared page-header component has now been added and applied to the dashboard and active client list/create/edit views. Continue migrating other page headers in their module phases.
- App Vite CSS and the shared Bootstrap bundle now declare **5.3.3**, matching Skote's reference assets. Keep runtime references in app-owned paths and verify both headers if updating the assets.
- Skote styles and theme fonts are already adapted into the app. Keep `admin-panel-theme/` read-only and do not make runtime assets depend on demo paths.
- Styles and scripts are split across `resources/css`, `resources/js`, `public/css/custom.css`, `public/assets/libs`, and view-level declarations. Consolidate ownership carefully rather than moving assets wholesale.
- Role, permission, and ticket list pagination now uses Laravel's Bootstrap 5 template; verify page links and query retention during browser QA.
- Multiple existing icon families are installed (Material Design Icons, Boxicons, Font Awesome 5, Dripicons). Reuse the closest existing family; add no icon dependency.
- Major view plugins include DataTables and responsive/buttons extensions, Select2, bootstrap-datepicker, jQuery repeater, SweetAlert2, TinyMCE, Dropzone, TUI Calendar, and ApexCharts. Several DataTables integrations reference Bootstrap 4 adapters. Inspect actual page use before changing adapters or initialization.
- The documented theme palette is Skote blue `#556ee6`, secondary `#74788d`, success `#34c38f`, info `#50a5f1`, warning `#f1b44c`, and danger `#f46a6a`. Keep status-to-color mapping tied to current constants/translations and review the full status set before centralizing it.

## Theme reference map

| App surface | Skote references |
| --- | --- |
| Global shell and page title | `layouts-colored-sidebar.html`, `layouts-light-sidebar.html`, `pages-starter.html` |
| Dashboard and charts | `dashboard-saas.html`, `charts-apex.html` |
| Forms and validation | `form-elements.html`, `form-layouts.html`, `form-validation.html`, `form-advanced.html` |
| Cards, buttons, badges, alerts | `ui-cards.html`, `ui-buttons.html`, `ui-general.html`, `ui-alerts.html`, `ui-colors.html` |
| Tables and pagination | `tables-basic.html`, `tables-responsive.html`, `tables-datatable.html` |
| Tabs, modals, timelines | `ui-tabs-accordions.html`, `ui-modals.html`, `pages-timeline.html` |
| Clients and profiles | `contacts-profile.html` |
| Commercial documents | `invoices-list.html`, `invoices-detail.html` |
| Authentication | `auth-login.html`, `auth-recoverpw.html`, `auth-recoverpw-2.html` |

These files are visual references; their sample data, routes, scripts, and plugin initialization are not copied blindly.

## Shared UI work candidates

Confirm repeated patterns in the relevant modules before extracting each item:

- Page heading with breadcrumb, optional description, and permission-aware actions.
- Card shell for lists and grouped content.
- Form field wrappers that preserve existing input names, errors, old input, and Livewire bindings.
- Status badge presentation based on existing constants and translations.
- Table wrapper and empty state that preserve DataTables behavior and required operational columns.
- Consistent action button/dropdown patterns and destructive confirmation.
- Loading state for existing Livewire actions where users currently wait for server work.

No giant component framework is planned. Prefer a small number of Blade components with stable, explicit inputs.

## Implementation order

### Phase 2 — Bootstrap 5.3.3 Foundation (in progress)

- [x] Align the app's Bootstrap CSS and JavaScript assets to Skote Bootstrap 5.3.3; runtime assets remain app-owned.
- [x] Build the Vite assets; `npm run build` passes with Bootstrap 5.3.3.
- [x] Confirm Bootstrap is not a direct npm dependency and no app view references a Bootstrap CDN.
- [x] Confirm one shared Bootstrap 5.3.3 script reference in the application layout; record the unreferenced Bootstrap 4.5 backup panel CSS candidate.
- [ ] Resolve Bootstrap 4 DataTables adapters while preserving filtering, pagination, responsive behavior, and exports.
- [x] Migrate role, permission, and ticket pagination templates to Bootstrap 5; continue scanning active utility/data-attribute markup.
- [x] Compare the app's shell structure with Skote's colored-sidebar and starter layouts; the existing shell already uses the same vertical-menu/main-content structure, so no shell markup changes were needed in this foundation pass.
- [ ] Visually inspect the app shell, navigation, page title/breadcrumbs, content gutters, shared alerts, modal, and footer against Skote layouts in a running browser.
- [x] Establish a reusable responsive page-header pattern from the repeated Skote `page-title-box` markup.
- [ ] Confirm authentication, role/permission navigation, responsive shell, Vite assets, and Livewire shell integration.

### Phase 3 — Global theme

- [~] Create and adopt the shared page-header component on the dashboard, client screens, ticket flows migrated so far, and discovered module page-title partials.
- [~] Modernize the global shell, header, footer, and document language; sidebar, content wrapper, navigation, auth/error layouts, and browser verification remain.
- [ ] Confirm theme JavaScript, jQuery plugins, Vite, and Livewire integration with one Bootstrap runtime.
- [ ] Add verified shared patterns for cards, forms, tables, alerts, status badges, empty/loading states, and actions where repetition supports them.

### Phase 4 — Core service workflow

- [~] Dashboard header migrated; dashboard content and profile remain.
- [~] Client list/create/edit headers migrated; profile and remaining client flows remain.
- [~] Ticket list/detail/create/edit/history/media, diagnosis, repair, and ready-for-delivery views received Bootstrap 5.3.3 presentation passes; reception and warranty behavior still need review.
- [ ] Diagnosis queues and shared diagnostic components under `theme/pages/Diagnostic` and `components/diagnostic`.
- [x] Ticket diagnosis report screen and repair queue/report screens; route actions, form names, permissions, report closure, and confirmation scripts retained.
- [~] Ready-for-delivery list and confirmation dialogs plus the warranty list/form presentation updated; no delivery route or field changed. Warranty create/store controller actions are empty and remain outside this UI pass.

### Phase 5 — Commercial

- [ ] Estimates and estimate-from-ticket flows.
- [ ] Invoices, invoice-from-estimate flows, article/ticket selection, and payment screens.
- [ ] Credit notes.
- [ ] Supplier bills/payments.
- [ ] Purchase orders, delivery slips, suppliers, and companies.

### Phase 6 — Administration and remaining active screens

- [ ] Users and role/permission management.
- [~] Category list/create UI redesigned; settings, backups, reports, calendar, contacts, inbox, imports/exports, chat, and file manager remain.
- [x] Login, password recovery, and active Laravel error pages use localized Bootstrap 5.3.3 presentation; the unused `welcome.blade.php` remains documented as unserved.
- [ ] Confirm route reachability before labeling any remaining view as legacy.

### Phase 7 — Complete Blade sweep

- [ ] Scan active route/view mappings for remaining visual variants.
- [ ] Re-scan all 543 Blade files and update each matrix row with verified type, active status, Bootstrap compatibility, redesign, and verification evidence.
- [ ] Confirm no active view or nested partial is left in the old UI.
- [ ] Search and classify every remaining Bootstrap 4 runtime, markup pattern, plugin adapter, and utility occurrence.

### Phase 8 — Verification

- [ ] Review status labels/colors against existing constants and translations.
- [ ] Check desktop, tablet, and narrow mobile layouts, especially tables and commercial forms.
- [ ] Verify relevant permissions, routes, Livewire actions, validation, modals, and plugins for each changed phase.
- [ ] Classify remaining views as active and redesigned, active and pending, legacy needing verification, PDF, email, or theme reference.
- [ ] Record open issues and completed phase checks here.

## High-risk preservation checklist

- Tickets, diagnosis, repair, delivery, and warranty: preserve state transitions, ownership, route names, action guards, and authorization.
- Estimates, invoices, credit notes, bills, purchase orders, delivery slips: preserve financial inputs and calculations, document numbers, line ordering, payment behavior, and historical values. No monetary data recalculation or migration.
- Livewire 4: preserve component classes, public state, validation, event/action names, DOM keys, and `wire:*` directives. Review third-party DOM mutation and initialization lifecycle before changing markup.
- Navigation: preserve existing role and permission conditions. Hiding a link is not an authorization substitute.
- PDFs, document templates, emails: keep outside this redesign and avoid adding Bootstrap/theme CSS to mPDF output.
- Production: no database schema or record changes; no production migrations, seeders, or destructive commands.

## Verification approach

For each implementation phase, run only checks relevant to the changed views and existing behavior. The owner requested functional checks as part of this redesign; use available focused tests and safe local rendering/build checks. Do not use production records for testing. Verify permission-aware links and action conditions from code/tests, and inspect browser behavior where a local authenticated session is available. Record commands and outcomes under the completed phase. Never claim visual verification when only static inspection was performed.

## Current progress

- **Phase 1 Audit:** in progress. The 543-file matrix and top-level module/asset inventory exist; recursive activity classification is still pending.
- **Phase 2 Bootstrap 5.3.3 Foundation:** in progress. App CSS/JS headers report 5.3.3 and `npm run build` passes. One shared runtime path is referenced. DataTables still use Bootstrap 4 adapters, and an unreferenced Bootstrap 4.5 backup-panel stylesheet remains a candidate pending package-route verification.
- **Phase 3 Global theme:** shell header, footer, locale attribute, and shared page header are updated; page-header adoption includes dashboard and client list/create/edit. Browser visual/interactive verification is pending because no browser session is available in this workspace.
- **Authentication and errors:** login/recovery screens and active 401/403/404/419/429/500/503 pages now use localized Bootstrap 5.3.3 styling and accessible feedback. `welcome.blade.php` has no active route; `errors/layout.blade.php` has no current reference.
- **Phase 4 Core service workflow:** in progress. Ticket intake, list, detail, edit, history, media, diagnosis, repair, and ready-for-delivery screens have presentation passes; reception and workflow QA remain.
- **Phase 5 Commercial:** pending. **Phase 6 Administration:** partial header/auth/category work is recorded above; screen redesign and route classification remain. **Phases 7–8:** pending active-view classification, full compatibility sweep, and browser/workflow verification.

## File-level Blade migration matrix

This generated inventory contains **543 Blade files** under `resources/views/`. Types are path-based. Active reachability remains pending until recursive route, controller, component, and dynamic-include references are confirmed. PDF and email templates are excluded from the admin UI migration. “Blade compile pass” records the successful `php artisan view:cache --no-interaction` run; it does not claim functional or visual verification.

| Blade file | Type | Active? | Theme reference | Bootstrap 5.3.3 | Redesigned | Verified |
| --- | --- | --- | --- | --- | --- | --- |
| `__pdf_templates/a.blade.php` | PDF_VIEW | Excluded from web UI redesign | Separate document layout | N/A | No | Blade compile pass |
| `__pdf_templates/b.blade.php` | PDF_VIEW | Excluded from web UI redesign | Separate document layout | N/A | No | Blade compile pass |
| `__pdf_templates/c.blade.php` | PDF_VIEW | Excluded from web UI redesign | Separate document layout | N/A | No | Blade compile pass |
| `components/app/page-header.blade.php` | ACTIVE_COMPONENT | Yes — referenced from active route view headers | Skote page title and breadcrumb pattern | Yes — Bootstrap 5.3.3 | Yes — shared title, optional description, breadcrumb, and action slot | Blade compile pass |
| `components/diagnostic/admin/diagnostic-layout.blade.php` | ACTIVE_COMPONENT | Pending usage trace | Nearest Skote component equivalent | Pending markup audit | No | Blade compile pass |
| `components/diagnostic/admin/filters.blade.php` | ACTIVE_COMPONENT | Pending usage trace | Nearest Skote component equivalent | Pending markup audit | No | Blade compile pass |
| `components/diagnostic/admin/js-filters.blade.php` | ACTIVE_COMPONENT | Pending usage trace | Nearest Skote component equivalent | Pending markup audit | No | Blade compile pass |
| `components/diagnostic/admin/tab-content.blade.php` | ACTIVE_COMPONENT | Pending usage trace | Nearest Skote component equivalent | Pending markup audit | No | Blade compile pass |
| `components/diagnostic/admin/tab-nav.blade.php` | ACTIVE_COMPONENT | Pending usage trace | Nearest Skote component equivalent | Pending markup audit | No | Blade compile pass |
| `components/diagnostic/admin/ticket-table.blade.php` | ACTIVE_COMPONENT | Pending usage trace | Nearest Skote component equivalent | Pending markup audit | No | Blade compile pass |
| `components/diagnostic/assets.blade.php` | ACTIVE_COMPONENT | Pending usage trace | Nearest Skote component equivalent | Pending markup audit | No | Blade compile pass |
| `components/diagnostic/diagnostic-layout.blade.php` | ACTIVE_COMPONENT | Pending usage trace | Nearest Skote component equivalent | Pending markup audit | No | Blade compile pass |
| `components/diagnostic/tab-content.blade.php` | ACTIVE_COMPONENT | Pending usage trace | Nearest Skote component equivalent | Pending markup audit | No | Blade compile pass |
| `components/diagnostic/tab-nav.blade.php` | ACTIVE_COMPONENT | Pending usage trace | Nearest Skote component equivalent | Pending markup audit | No | Blade compile pass |
| `components/diagnostic/ticket-table.blade.php` | ACTIVE_COMPONENT | Pending usage trace | Nearest Skote component equivalent | Pending markup audit | No | Blade compile pass |
| `components/diagnostic/workflow-layout.blade.php` | ACTIVE_COMPONENT | Pending usage trace | Nearest Skote component equivalent | Pending markup audit | No | Blade compile pass |
| `components/forms/input.blade.php` | ACTIVE_COMPONENT | Pending usage trace | Nearest Skote component equivalent | Pending markup audit | No | Blade compile pass |
| `components/forms/loading.blade.php` | ACTIVE_COMPONENT | Pending usage trace | Nearest Skote component equivalent | Pending markup audit | No | Blade compile pass |
| `components/sidebar/commercial-menu.blade.php` | ACTIVE_COMPONENT | Pending usage trace | Nearest Skote component equivalent | Pending markup audit | No | Blade compile pass |
| `components/sidebar/dashboard-item.blade.php` | ACTIVE_COMPONENT | Pending usage trace | Nearest Skote component equivalent | Pending markup audit | No | Blade compile pass |
| `components/sidebar/developer-menu.blade.php` | ACTIVE_COMPONENT | Pending usage trace | Nearest Skote component equivalent | Pending markup audit | No | Blade compile pass |
| `components/sidebar/diagnostic-menu.blade.php` | ACTIVE_COMPONENT | Pending usage trace | Nearest Skote component equivalent | Pending markup audit | No | Blade compile pass |
| `components/sidebar/main-sidebar.blade.php` | ACTIVE_COMPONENT | Pending usage trace | Nearest Skote component equivalent | Pending markup audit | No | Blade compile pass |
| `components/sidebar/reception-menu.blade.php` | ACTIVE_COMPONENT | Pending usage trace | Nearest Skote component equivalent | Pending markup audit | No | Blade compile pass |
| `components/sidebar/reports-menu.blade.php` | ACTIVE_COMPONENT | Pending usage trace | Nearest Skote component equivalent | Pending markup audit | No | Blade compile pass |
| `components/sidebar/settings-menu.blade.php` | ACTIVE_COMPONENT | Pending usage trace | Nearest Skote component equivalent | Pending markup audit | No | Blade compile pass |
| `components/sidebar/ticket-menu.blade.php` | ACTIVE_COMPONENT | Pending usage trace | Nearest Skote component equivalent | Pending markup audit | No | Blade compile pass |
| `errors/401.blade.php` | ACTIVE_APPLICATION_VIEW | Yes — Laravel exception renderer | Bootstrap 5.3.3 error card through `errors::minimal` | Yes — Bootstrap 5.3.3 | Yes — uses shared minimal error presentation | Blade compile pass |
| `errors/403.blade.php` | ACTIVE_APPLICATION_VIEW | Yes — Laravel exception renderer | Bootstrap 5.3.3 error card through `errors::minimal` | Yes — Bootstrap 5.3.3 | Yes — safe text output and shared error presentation | Blade compile pass |
| `errors/404.blade.php` | ACTIVE_APPLICATION_VIEW | Yes — Laravel exception renderer | Bootstrap 5.3.3 error card through `errors::minimal` | Yes — Bootstrap 5.3.3 | Yes — uses shared minimal error presentation | Blade compile pass |
| `errors/419.blade.php` | ACTIVE_APPLICATION_VIEW | Yes — Laravel exception renderer | Bootstrap 5.3.3 error card through `errors::minimal` | Yes — Bootstrap 5.3.3 | Yes — uses shared minimal error presentation | Blade compile pass |
| `errors/429.blade.php` | ACTIVE_APPLICATION_VIEW | Yes — Laravel exception renderer | Bootstrap 5.3.3 illustrated error card | Yes — Bootstrap 5.3.3 | Yes — responsive illustration and safe message output | Blade compile pass |
| `errors/500.blade.php` | ACTIVE_APPLICATION_VIEW | Yes — Laravel exception renderer | Bootstrap 5.3.3 error card through `errors::minimal` | Yes — Bootstrap 5.3.3 | Yes — uses shared minimal error presentation | Blade compile pass |
| `errors/503.blade.php` | ACTIVE_APPLICATION_VIEW | Yes — Laravel maintenance mode | Skote maintenance/error screen | Yes — Bootstrap 5.3.3 | Yes — locale-aware and safe external link | Blade compile pass |
| `errors/illustrated-layout.blade.php` | ACTIVE_COMPONENT | Yes — extended by `errors/429` | Bootstrap 5.3.3 illustrated error card | Yes — Bootstrap 5.3.3 | Yes — responsive layout and escaped error message | Blade compile pass |
| `errors/layout.blade.php` | LEGACY_OR_UNUSED | No current framework/app reference found | Legacy Laravel error template | Separate legacy layout | No — retained pending package-level verification | Blade compile pass |
| `errors/minimal.blade.php` | ACTIVE_COMPONENT | Yes — extended by 401/403/404/419/500 | Bootstrap 5.3.3 error card | Yes — Bootstrap 5.3.3 | Yes — responsive error message, locale, and home action | Blade compile pass |
| `exports/clients.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote starter/forms/tables | Pending markup audit | No | Blade compile pass |
| `theme/Authentification/Login/index.blade.php` | ACTIVE_APPLICATION_VIEW | Yes — returned by `AuthController` | Skote authentication layout | Yes — Bootstrap 5.3.3 | Yes — localized labels, accessible form errors, current locale, and unchanged login/remember actions | Blade compile pass |
| `theme/Authentification/Password/forgot.blade.php` | ACTIVE_APPLICATION_VIEW | Yes — returned by forgot-password controller | Skote recovery layout | Yes — Bootstrap 5.3.3 | Yes — localized recovery form and visible email validation | Blade compile pass |
| `theme/Authentification/Password/reset.blade.php` | ACTIVE_APPLICATION_VIEW | Yes — returned by reset-password controller | Skote recovery layout | Yes — Bootstrap 5.3.3 | Yes — localized password fields and visible validation states | Blade compile pass |
| `theme/Authentification/auth_footer.blade.php` | ACTIVE_PARTIAL | Yes — included by all authentication screens | Skote authentication footer | Yes — Bootstrap 5.3.3 | Yes — server-rendered year and safe external link | Blade compile pass |
| `theme/Emails/Commercial/BC/SendBCMail.blade.php` | ACTIVE_PARTIAL | Pending reference audit | Nearest Skote component equivalent | Pending markup audit | No | Blade compile pass |
| `theme/Emails/Commercial/Estimate/DeletedEstimateMail.blade.php` | ACTIVE_PARTIAL | Pending reference audit | Nearest Skote component equivalent | Pending markup audit | No | Blade compile pass |
| `theme/Emails/Commercial/Estimate/SendEstimateMail.blade.php` | ACTIVE_PARTIAL | Pending reference audit | Nearest Skote component equivalent | Pending markup audit | No | Blade compile pass |
| `theme/Emails/Commercial/Invoice/SendInvoiceMail.blade.php` | ACTIVE_PARTIAL | Pending reference audit | Nearest Skote component equivalent | Pending markup audit | No | Blade compile pass |
| `theme/Emails/Commercial/InvoiceAvoir/SendInvoiceAvoirMail.blade.php` | ACTIVE_PARTIAL | Pending reference audit | Nearest Skote component equivalent | Pending markup audit | No | Blade compile pass |
| `theme/Emails/mail_template.blade.php` | ACTIVE_PARTIAL | Pending reference audit | Nearest Skote component equivalent | Pending markup audit | No | Blade compile pass |
| `theme/bons_template/template1/bl.blade.php` | PDF_VIEW | Excluded from web UI redesign | Separate document layout | N/A | No | Blade compile pass |
| `theme/bons_template/template1/index.blade.php` | PDF_VIEW | Excluded from web UI redesign | Separate document layout | N/A | No | Blade compile pass |
| `theme/estimates_template/template1/index.blade.php` | PDF_VIEW | Excluded from web UI redesign | Separate document layout | N/A | No | Blade compile pass |
| `theme/estimates_template/template2/index.blade.php` | PDF_VIEW | Excluded from web UI redesign | Separate document layout | N/A | No | Blade compile pass |
| `theme/invoices_template/avoirs/index.blade.php` | PDF_VIEW | Excluded from web UI redesign | Separate document layout | N/A | No | Blade compile pass |
| `theme/invoices_template/template1/index.blade.php` | PDF_VIEW | Excluded from web UI redesign | Separate document layout | N/A | No | Blade compile pass |
| `theme/layouts/_helpers/apexchart/LineChart.blade.php` | ACTIVE_PARTIAL | Pending reference audit | Nearest Skote component equivalent | Pending markup audit | No | Blade compile pass |
| `theme/layouts/_helpers/apexchart/__apex_chart.blade.php` | ACTIVE_PARTIAL | Pending reference audit | Nearest Skote component equivalent | Pending markup audit | No | Blade compile pass |
| `theme/layouts/_parts/__header.blade.php` | ACTIVE_PARTIAL | Yes — included by active app layout | Skote vertical navigation and user menu | Yes — Bootstrap 5.3.3 | Yes — responsive header, profile/settings links, keyboard-accessible POST logout | Blade compile pass |
| `theme/layouts/_parts/__loader.blade.php` | ACTIVE_PARTIAL | Pending reference audit | Nearest Skote component equivalent | Pending markup audit | No | Blade compile pass |
| `theme/layouts/_parts/__messages.blade.php` | ACTIVE_PARTIAL | Pending reference audit | Nearest Skote component equivalent | Pending markup audit | No | Blade compile pass |
| `theme/layouts/_parts/_footer.blade.php` | ACTIVE_PARTIAL | Yes — included by active app layout | Skote app shell footer | Yes — Bootstrap 5.3.3 | Yes — responsive footer; dynamic year | Blade compile pass |
| `theme/layouts/_parts/_leftSidebar.blade.php` | ACTIVE_PARTIAL | Pending reference audit | Nearest Skote component equivalent | Pending markup audit | No | Blade compile pass |
| `theme/layouts/_parts/_leftSidebar_commercial.blade.php` | ACTIVE_PARTIAL | Pending reference audit | Nearest Skote component equivalent | Pending markup audit | No | Blade compile pass |
| `theme/layouts/_parts/_modal.blade.php` | ACTIVE_PARTIAL | Pending reference audit | Nearest Skote component equivalent | Pending markup audit | No | Blade compile pass |
| `theme/layouts/_parts/_navbar.blade.php` | ACTIVE_PARTIAL | Pending reference audit | Nearest Skote component equivalent | Pending markup audit | No | Blade compile pass |
| `theme/layouts/_parts/_overly.blade.php` | ACTIVE_PARTIAL | Pending reference audit | Nearest Skote component equivalent | Pending markup audit | No | Blade compile pass |
| `theme/layouts/_parts/_rightSidebar.blade.php` | ACTIVE_PARTIAL | Pending reference audit | Nearest Skote component equivalent | Pending markup audit | No | Blade compile pass |
| `theme/layouts/_parts/_subscribe.blade.php` | ACTIVE_PARTIAL | Pending reference audit | Nearest Skote component equivalent | Pending markup audit | No | Blade compile pass |
| `theme/layouts/_parts/vendor-scripts.blade.php` | ACTIVE_PARTIAL | Pending reference audit | Nearest Skote component equivalent | Pending markup audit | No | Blade compile pass |
| `theme/layouts/app.blade.php` | ACTIVE_PARTIAL | Yes — active application layout | Skote application shell | Yes — Bootstrap 5.3.3 | Yes — locale-aware document language | Blade compile pass |
| `theme/livewire/application/importer/c-s-v-file-importer.blade.php` | LIVEWIRE_VIEW | Pending component/include trace | Skote forms, cards, tables, or tabs | Pending markup audit | No | Blade compile pass |
| `theme/livewire/commercial/avoir/create/info.blade.php` | LIVEWIRE_VIEW | Pending component/include trace | Skote forms, cards, tables, or tabs | Pending markup audit | No | Blade compile pass |
| `theme/livewire/commercial/b-l/b-l-info.blade.php` | LIVEWIRE_VIEW | Pending component/include trace | Skote forms, cards, tables, or tabs | Pending markup audit | No | Blade compile pass |
| `theme/livewire/commercial/bon-command/info.blade.php` | LIVEWIRE_VIEW | Pending component/include trace | Skote forms, cards, tables, or tabs | Pending markup audit | No | Blade compile pass |
| `theme/livewire/commercial/estimate/create/from-ticket.blade.php` | LIVEWIRE_VIEW | Pending component/include trace | Skote forms, cards, tables, or tabs | Pending markup audit | No | Blade compile pass |
| `theme/livewire/commercial/estimate/create/info.blade.php` | LIVEWIRE_VIEW | Pending component/include trace | Skote forms, cards, tables, or tabs | Pending markup audit | No | Blade compile pass |
| `theme/livewire/commercial/estimate/create/new_info.blade.php` | LIVEWIRE_VIEW | Pending component/include trace | Skote forms, cards, tables, or tabs | Pending markup audit | No | Blade compile pass |
| `theme/livewire/commercial/estimate/create/tickets.blade.php` | LIVEWIRE_VIEW | Pending component/include trace | Skote forms, cards, tables, or tabs | Pending markup audit | No | Blade compile pass |
| `theme/livewire/commercial/estimate/edit/edit-article.blade.php` | LIVEWIRE_VIEW | Pending component/include trace | Skote forms, cards, tables, or tabs | Pending markup audit | No | Blade compile pass |
| `theme/livewire/commercial/invoice/avoir/info.blade.php` | LIVEWIRE_VIEW | Pending component/include trace | Skote forms, cards, tables, or tabs | Pending markup audit | No | Blade compile pass |
| `theme/livewire/commercial/invoice/create/articles.blade.php` | LIVEWIRE_VIEW | Pending component/include trace | Skote forms, cards, tables, or tabs | Pending markup audit | No | Blade compile pass |
| `theme/livewire/commercial/invoice/create/info.blade.php` | LIVEWIRE_VIEW | Pending component/include trace | Skote forms, cards, tables, or tabs | Pending markup audit | No | Blade compile pass |
| `theme/livewire/commercial/invoice/create/info2.blade.php` | LIVEWIRE_VIEW | Pending component/include trace | Skote forms, cards, tables, or tabs | Pending markup audit | No | Blade compile pass |
| `theme/livewire/commercial/invoice/create/tickets.blade.php` | LIVEWIRE_VIEW | Pending component/include trace | Skote forms, cards, tables, or tabs | Pending markup audit | No | Blade compile pass |
| `theme/livewire/commercial/invoice/edit/edit-article.blade.php` | LIVEWIRE_VIEW | Pending component/include trace | Skote forms, cards, tables, or tabs | Pending markup audit | No | Blade compile pass |
| `theme/livewire/commercial/invoice-avoir/edit/edit-article.blade.php` | LIVEWIRE_VIEW | Pending component/include trace | Skote forms, cards, tables, or tabs | Pending markup audit | No | Blade compile pass |
| `theme/livewire/dasboard/dashboard.blade.php` | LIVEWIRE_VIEW | Pending component/include trace | Skote forms, cards, tables, or tabs | Pending markup audit | No | Blade compile pass |
| `theme/pages/Admin/__create/form.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote contacts/profile, forms, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Admin/__create/index.blade.php` | ACTIVE_APPLICATION_VIEW | Pending route/include trace | Skote contacts/profile, forms, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Admin/__grid/index.blade.php` | ACTIVE_APPLICATION_VIEW | Pending route/include trace | Skote contacts/profile, forms, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Admin/__grid/section_a_contacts.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote contacts/profile, forms, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Admin/__list/index.blade.php` | ACTIVE_APPLICATION_VIEW | Pending route/include trace | Skote contacts/profile, forms, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Admin/__list/section_a_contacts.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote contacts/profile, forms, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Admin/__profile/__checkbox_permissions.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote contacts/profile, forms, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Admin/__profile/__select_multi_roles.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote contacts/profile, forms, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Admin/__profile/__select_one_role.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote contacts/profile, forms, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Admin/__profile/__select_permissions.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote contacts/profile, forms, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Admin/__profile/form.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote contacts/profile, forms, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Admin/__profile/index.blade.php` | ACTIVE_APPLICATION_VIEW | Pending route/include trace | Skote contacts/profile, forms, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Admin/index.blade.php` | ACTIVE_APPLICATION_VIEW | Pending route/include trace | Skote contacts/profile, forms, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Admin/section_0_page_title.blade.php` | ACTIVE_PARTIAL | Yes — included by admin list/create/profile views | Shared page header | Yes — Bootstrap 5.3.3 | Yes — common user heading and create action | Blade compile pass |
| `theme/pages/Backup/__title.blade.php` | ACTIVE_PARTIAL | Yes — included by backup view | Shared page header | Yes — Bootstrap 5.3.3 | Yes — French backup heading and breadcrumb | Blade compile pass |
| `theme/pages/Backup/index.blade.php` | ACTIVE_APPLICATION_VIEW | Pending route/include trace | Skote starter/forms/tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Backup/section_a_backups.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote tables and action buttons | Yes — Bootstrap 5.3.3 classes | Partial — converted spacing/alignment utilities | Blade compile pass |
| `theme/pages/Calendar/index.blade.php` | ACTIVE_APPLICATION_VIEW | Pending route/include trace | Skote starter/forms/tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Calendar/scripts.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote starter/forms/tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Calendar/sections/section_0_page_title.blade.php` | ACTIVE_PARTIAL | Yes — included by calendar route view | Shared page header | Yes — Bootstrap 5.3.3 | Yes — app-specific French heading; removed demo breadcrumb | Blade compile pass |
| `theme/pages/Calendar/sections/section_a_calendar.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Existing TUI Calendar interface | Yes — removed unused Bootstrap 4 data attribute | Partial — preserved TUI click handler | Blade compile pass |
| `theme/pages/Calendar/styles.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote starter/forms/tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Category/__grid/categories_grid.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote cards and badges | Yes — Bootstrap 5.3.3 | Yes — removed demo invoice content; real category fields only | Blade compile pass |
| `theme/pages/Category/__grid/index.blade.php` | ACTIVE_APPLICATION_VIEW | Pending route/include trace | Skote starter/forms/tables | Yes — Bootstrap 5.3.3 | No — not currently included by the active category route view | Blade compile pass |
| `theme/pages/Category/__list/index.blade.php` | ACTIVE_APPLICATION_VIEW | Yes — included by active category route view | Skote table and form cards | Yes — Bootstrap 5.3.3 | Yes — responsive page structure | Blade compile pass |
| `theme/pages/Category/__list/section_a_categories.blade.php` | ACTIVE_PARTIAL | Yes — included by active category route view | Skote tables, forms, badges, empty state | Yes — Bootstrap 5.3.3 | Yes — real fields, responsive table/form, accessible submit action; removed fake pagination | Blade compile pass |
| `theme/pages/Category/index.blade.php` | ACTIVE_APPLICATION_VIEW | Yes — returned by category controller | Skote shared page header | Yes — Bootstrap 5.3.3 | Yes — shared French title and breadcrumb | Blade compile pass |
| `theme/pages/Category/section_0_page_title.blade.php` | ACTIVE_PARTIAL | Pending reference audit | Skote page title | Yes — Bootstrap 5.3.3 | No — retained pending dynamic-reference audit | Blade compile pass |
| `theme/pages/Chat/chat.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote chat/form patterns | Yes — Bootstrap 5.3.3 classes | Partial — removed legacy `form-group` wrapper | Blade compile pass |
| `theme/pages/Chat/index.blade.php` | ACTIVE_APPLICATION_VIEW | Pending route/include trace | Skote starter/forms/tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Chat/section_0_page_title.blade.php` | ACTIVE_PARTIAL | Yes — included by chat route view | Shared page header | Yes — Bootstrap 5.3.3 | Yes — app-specific French heading; removed demo breadcrumb | Blade compile pass |
| `theme/pages/Client/__create/__add_phones.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote contacts/profile, forms, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Client/__create/form_2.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote contacts/profile, forms, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Client/__create/index.blade.php` | ACTIVE_APPLICATION_VIEW | Yes — referenced by active route/view | Skote contacts/profile, forms, tables | Yes — Bootstrap 5.3.3 | Yes — shared page header | Blade compile pass |
| `theme/pages/Client/__datatable/__clients_table.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote contacts/profile, forms, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Client/__datatable/__import_clients.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote contacts/profile, forms, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Client/__datatable/__with_options.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote contacts/profile, forms, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Client/__datatable/index.blade.php` | ACTIVE_APPLICATION_VIEW | Yes — referenced by active route/view | Skote contacts/profile, forms, tables | Yes — Bootstrap 5.3.3 | Yes — shared page header | Blade compile pass |
| `theme/pages/Client/__edit/__add_phones.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote contacts/profile, forms, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Client/__edit/__edit_emails.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote contacts/profile, forms, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Client/__edit/__edit_phones.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote contacts/profile, forms, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Client/__edit/form_2.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote contacts/profile, forms, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Client/__edit/index.blade.php` | ACTIVE_APPLICATION_VIEW | Yes — referenced by active route/view | Skote contacts/profile, forms, tables | Yes — Bootstrap 5.3.3 | Yes — shared page header | Blade compile pass |
| `theme/pages/Client/__profile/index.blade.php` | ACTIVE_APPLICATION_VIEW | Pending route/include trace | Skote contacts/profile, forms, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Client/__profile/profile.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote contacts/profile, forms, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Client/index.blade.php` | ACTIVE_APPLICATION_VIEW | Pending route/include trace | Skote contacts/profile, forms, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Client/section_0_page_title.blade.php` | ACTIVE_PARTIAL | Yes — included by client views and technical report edit view | Shared page header | Yes — Bootstrap 5.3.3 | Yes — shared client title/action; report edit was corrected to its own title partial | Blade compile pass |
| `theme/pages/Commercial/BC/__create/__add_articles.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/BC/__create/__form_create.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/BC/__create/__info.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/BC/__create/index.blade.php` | ACTIVE_APPLICATION_VIEW | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/BC/__datatable/__documents_table.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/BC/__datatable/__filters.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/BC/__datatable/__send_bc.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/BC/__datatable/__with_options.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/BC/__datatable/index.blade.php` | ACTIVE_APPLICATION_VIEW | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/BC/__detail/BC.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/BC/__detail/BC_detail_top.blade.php` | ACTIVE_PARTIAL | Yes — included by purchase-order detail view | Shared page header | Yes — Bootstrap 5.3.3 | Yes — order code and correct breadcrumb | Blade compile pass |
| `theme/pages/Commercial/BC/__detail/index.blade.php` | ACTIVE_APPLICATION_VIEW | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/BC/__edit/__add_articles.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/BC/__edit/__bc_actions.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/BC/__edit/__delete_articles_bc_ajax.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/BC/__edit/__edit_articles.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/BC/__edit/__form_edit.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/BC/__edit/__historique_bc.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/BC/__edit/__info.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/BC/__edit/__print_document.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/BC/__edit/index.blade.php` | ACTIVE_APPLICATION_VIEW | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/BC/__js.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/BC/index.blade.php` | ACTIVE_APPLICATION_VIEW | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/BC/section_0_page_title.blade.php` | ACTIVE_PARTIAL | Yes — included by purchase-order create/edit/list views | Shared page header | Yes — Bootstrap 5.3.3 | Yes — commercial title and create action | Blade compile pass |
| `theme/pages/Commercial/BL/__create/__add_articles.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/BL/__create/__form_create.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/BL/__create/__info.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/BL/__create/index.blade.php` | ACTIVE_APPLICATION_VIEW | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/BL/__datatable/__documents_table.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/BL/__datatable/__filters.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/BL/__datatable/__send_bc.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/BL/__datatable/__with_options.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/BL/__datatable/index.blade.php` | ACTIVE_APPLICATION_VIEW | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/BL/__detail/BC.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/BL/__detail/BC_detail_top.blade.php` | ACTIVE_PARTIAL | Yes — included by delivery-slip detail view | Shared page header | Yes — Bootstrap 5.3.3 | Yes — delivery-slip code and correct breadcrumb | Blade compile pass |
| `theme/pages/Commercial/BL/__detail/index.blade.php` | ACTIVE_APPLICATION_VIEW | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/BL/__edit/__add_articles.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/BL/__edit/__bc_actions.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/BL/__edit/__delete_articles_bc_ajax.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/BL/__edit/__edit_articles.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/BL/__edit/__form_edit.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/BL/__edit/__historique_bc.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/BL/__edit/__info.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/BL/__edit/__print_document.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/BL/__edit/index.blade.php` | ACTIVE_APPLICATION_VIEW | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/BL/__js.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/BL/index.blade.php` | ACTIVE_APPLICATION_VIEW | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/BL/section_0_page_title.blade.php` | ACTIVE_PARTIAL | Yes — included by delivery-slip create/edit/list views | Shared page header | Yes — Bootstrap 5.3.3 | Yes — commercial title and create action | Blade compile pass |
| `theme/pages/Commercial/Bill/__create/__form_create.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/Bill/__create/__info.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/Bill/__create/__info_bill.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/Bill/__create/index.blade.php` | ACTIVE_APPLICATION_VIEW | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/Bill/__create_normal/__form_create.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/Bill/__create_normal/__info.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/Bill/__create_normal/__info_bill.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/Bill/__create_normal/index.blade.php` | ACTIVE_APPLICATION_VIEW | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/Bill/__datatable/__add_bill.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/Bill/__datatable/__bills_table.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/Bill/__datatable/__filters.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/Bill/__datatable/__js_filters.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/Bill/__datatable/__with_options.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/Bill/__datatable/index.blade.php` | ACTIVE_APPLICATION_VIEW | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/Bill/__detail/Bill.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/Bill/__detail/Bill_detail_top.blade.php` | ACTIVE_PARTIAL | Yes — included by bill detail route view | Shared page header | Yes — Bootstrap 5.3.3 | Yes — bill number heading and working payments breadcrumb | Blade compile pass |
| `theme/pages/Commercial/Bill/__detail/index.blade.php` | ACTIVE_APPLICATION_VIEW | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/Bill/__edit/__form_edit.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/Bill/__edit/__info.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/Bill/__edit/__info_bill.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/Bill/__edit/__send_invoice_section.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/Bill/__edit/index.blade.php` | ACTIVE_APPLICATION_VIEW | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/Bill/index.blade.php` | ACTIVE_APPLICATION_VIEW | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/Bill/section_0_page_title.blade.php` | ACTIVE_PARTIAL | Yes — included by bill/payment create/edit/list views | Shared page header | Yes — Bootstrap 5.3.3 | Yes — fixed the empty create link and permission-gated payment action | Blade compile pass |
| `theme/pages/Commercial/Company/__create/form_2.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/Company/__create/index.blade.php` | ACTIVE_APPLICATION_VIEW | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/Company/__datatable/__companies_table.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/Company/__datatable/__with_options.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/Company/__datatable/index.blade.php` | ACTIVE_APPLICATION_VIEW | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/Company/__edit/form_2.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/Company/__edit/index.blade.php` | ACTIVE_APPLICATION_VIEW | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/Company/index.blade.php` | ACTIVE_APPLICATION_VIEW | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/Company/section_0_page_title.blade.php` | ACTIVE_PARTIAL | Yes — included by company list/create/edit views | Shared page header | Yes — Bootstrap 5.3.3 | Yes — common company title and breadcrumb | Blade compile pass |
| `theme/pages/Commercial/Estimate/__create/__add_articles.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/Estimate/__create/__form_create.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/Estimate/__create/__info.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/Estimate/__create/__mutli_tickets.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/Estimate/__create/__tickets.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/Estimate/__create/index.blade.php` | ACTIVE_APPLICATION_VIEW | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/Estimate/__create_from_ticket/__add_articles.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/Estimate/__create_from_ticket/__form_create.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/Estimate/__create_from_ticket/__info.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/Estimate/__create_from_ticket/index.blade.php` | ACTIVE_APPLICATION_VIEW | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/Estimate/__datatable/__estimates_table.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/Estimate/__datatable/__filter.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/Estimate/__datatable/__js_filter.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/Estimate/__datatable/__send_estimate.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/Estimate/__datatable/__with_options.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/Estimate/__datatable/index.blade.php` | ACTIVE_APPLICATION_VIEW | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/Estimate/__detail/estimate.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/Estimate/__detail/estimate_detail_top.blade.php` | ACTIVE_PARTIAL | Yes — included by estimate detail route view | Shared page header | Yes — Bootstrap 5.3.3 | Yes — estimate code and correct list breadcrumb | Blade compile pass |
| `theme/pages/Commercial/Estimate/__detail/index.blade.php` | ACTIVE_APPLICATION_VIEW | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/Estimate/__edit/__add_article.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/Estimate/__edit/__delete_article_ajax.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/Estimate/__edit/__edit_articles.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/Estimate/__edit/__edit_tickets.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/Estimate/__edit/__estimate_actions.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/Estimate/__edit/__estimate_historique.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/Estimate/__edit/__form_edit.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/Estimate/__edit/__info.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/Estimate/__edit/__print_document.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/Estimate/__edit/__update_article_ajax.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/Estimate/__edit/index.blade.php` | ACTIVE_APPLICATION_VIEW | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/Estimate/index.blade.php` | ACTIVE_APPLICATION_VIEW | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/Estimate/section_0_title.blade.php` | ACTIVE_PARTIAL | Yes — included by estimate list/create/edit views | Shared page header | Yes — Bootstrap 5.3.3 | Yes — localized title and permission-gated create action | Blade compile pass |
| `theme/pages/Commercial/Invoice/__create/__add_articles.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/Invoice/__create/__form_create.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/Invoice/__create/__info.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/Invoice/__create/__send_invoice_section.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/Invoice/__create/b_info.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/Invoice/__create/index.blade.php` | ACTIVE_APPLICATION_VIEW | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/Invoice/__create_from_estimate/__add_articles.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/Invoice/__create_from_estimate/__form_create.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/Invoice/__create_from_estimate/__info.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/Invoice/__create_from_estimate/__ticket.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/Invoice/__create_from_estimate/__tickets.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/Invoice/__create_from_estimate/index.blade.php` | ACTIVE_APPLICATION_VIEW | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/Invoice/__datatable/__add_payment.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/Invoice/__datatable/__add_payment_form.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/Invoice/__datatable/__add_payment_info.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/Invoice/__datatable/__default.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/Invoice/__datatable/__filters.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/Invoice/__datatable/__invoices_table.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/Invoice/__datatable/__new_filter.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/Invoice/__datatable/__payment_detail_modal.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/Invoice/__datatable/__send_invoice.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/Invoice/__datatable/__show_invoice_tickets.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/Invoice/__datatable/__show_invoice_tickets_table.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/Invoice/__datatable/__top.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/Invoice/__datatable/__with_options.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/Invoice/__datatable/index.blade.php` | ACTIVE_APPLICATION_VIEW | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/Invoice/__detail/index.blade.php` | ACTIVE_APPLICATION_VIEW | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/Invoice/__detail/invoice.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/Invoice/__detail/invoice_detail_top.blade.php` | ACTIVE_PARTIAL | Yes — included by invoice detail route view | Shared page header | Yes — Bootstrap 5.3.3 | Yes — invoice number heading and corrected label | Blade compile pass |
| `theme/pages/Commercial/Invoice/__edit/__add_articles.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/Invoice/__edit/__delete_article_invoice_ajax.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/Invoice/__edit/__edit_articles.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/Invoice/__edit/__form_edit.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/Invoice/__edit/__historique_invoice.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/Invoice/__edit/__info.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/Invoice/__edit/__info_with_tickets.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/Invoice/__edit/__invoice_actions.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/Invoice/__edit/__print_document.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/Invoice/__edit/__tickets.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/Invoice/__edit/b_info.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/Invoice/__edit/index.blade.php` | ACTIVE_APPLICATION_VIEW | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/Invoice/__js.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/Invoice/index.blade.php` | ACTIVE_APPLICATION_VIEW | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/Invoice/section_0_title.blade.php` | ACTIVE_PARTIAL | Yes — included by invoice list/create/edit views | Shared page header | Yes — Bootstrap 5.3.3 | Yes — localized title and permission-gated create action | Blade compile pass |
| `theme/pages/Commercial/InvoiceAvoir/__create_avoir/__add_articles.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/InvoiceAvoir/__create_avoir/__form_create.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/InvoiceAvoir/__create_avoir/__info.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/InvoiceAvoir/__create_avoir/__send_invoice_section.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/InvoiceAvoir/__create_avoir/index.blade.php` | ACTIVE_APPLICATION_VIEW | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/InvoiceAvoir/__datatable/__default.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/InvoiceAvoir/__datatable/__filters.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/InvoiceAvoir/__datatable/__invoices_table.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/InvoiceAvoir/__datatable/__payment_detail_modal.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/InvoiceAvoir/__datatable/__send_invoice_avoir.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/InvoiceAvoir/__datatable/__with_options.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/InvoiceAvoir/__datatable/index.blade.php` | ACTIVE_APPLICATION_VIEW | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/InvoiceAvoir/__detail/index.blade.php` | ACTIVE_APPLICATION_VIEW | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/InvoiceAvoir/__detail/invoice.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/InvoiceAvoir/__detail/invoice_detail_top.blade.php` | ACTIVE_PARTIAL | Yes — included by credit-note detail route view | Shared page header | Yes — Bootstrap 5.3.3 | Yes — credit-note code and correct list breadcrumb | Blade compile pass |
| `theme/pages/Commercial/InvoiceAvoir/__edit/__add_articles.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/InvoiceAvoir/__edit/__delete_article_invoice_avoir_ajax.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/InvoiceAvoir/__edit/__edit_articles.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/InvoiceAvoir/__edit/__form_edit.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/InvoiceAvoir/__edit/__historique_avoir.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/InvoiceAvoir/__edit/__info.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/InvoiceAvoir/__edit/__invoice_avoir_actions.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/InvoiceAvoir/__edit/__print_document.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/InvoiceAvoir/__edit/index.blade.php` | ACTIVE_APPLICATION_VIEW | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/InvoiceAvoir/__js.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/InvoiceAvoir/index.blade.php` | ACTIVE_APPLICATION_VIEW | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/InvoiceAvoir/section_0_title.blade.php` | ACTIVE_PARTIAL | Yes — included by credit-note list/create/edit views | Shared page header | Yes — Bootstrap 5.3.3 | Yes — corrected create link to existing credit-note route with permission gate | Blade compile pass |
| `theme/pages/Commercial/Provider/__create/form_2.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/Provider/__create/index.blade.php` | ACTIVE_APPLICATION_VIEW | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/Provider/__datatable/__providers_table.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/Provider/__datatable/__with_options.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/Provider/__datatable/index.blade.php` | ACTIVE_APPLICATION_VIEW | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/Provider/__edit/__edit_emails.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/Provider/__edit/__edit_phones.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/Provider/__edit/form_2.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/Provider/__edit/index.blade.php` | ACTIVE_APPLICATION_VIEW | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/Provider/index.blade.php` | ACTIVE_APPLICATION_VIEW | Pending route/include trace | Skote invoices, forms, cards, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Commercial/Provider/section_0_page_title.blade.php` | ACTIVE_PARTIAL | Yes — included by provider list/create/edit views | Shared page header | Yes — Bootstrap 5.3.3 | Yes — French heading and permission-gated create action | Blade compile pass |
| `theme/pages/Contact/__grid/index.blade.php` | ACTIVE_APPLICATION_VIEW | Pending route/include trace | Skote starter/forms/tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Contact/__grid/section_a_contacts.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote starter/forms/tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Contact/__list/index.blade.php` | ACTIVE_APPLICATION_VIEW | Pending route/include trace | Skote starter/forms/tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Contact/__list/section_a_contacts.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote starter/forms/tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Contact/__profile/index.blade.php` | ACTIVE_APPLICATION_VIEW | Pending route/include trace | Skote starter/forms/tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Contact/index.blade.php` | ACTIVE_APPLICATION_VIEW | Pending route/include trace | Skote starter/forms/tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Contact/section_0_page_title.blade.php` | ACTIVE_PARTIAL | Yes — included by contact route view | Shared page header | Yes — Bootstrap 5.3.3 | Yes — replaced demo Users Grid label with Contacts | Blade compile pass |
| `theme/pages/Diagnostic/__admin/__datatable/__default.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote cards, tables, timeline, status badges | Pending markup audit | No | Blade compile pass |
| `theme/pages/Diagnostic/__admin/__datatable/__tickets_table.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote cards, tables, timeline, status badges | Pending markup audit | No | Blade compile pass |
| `theme/pages/Diagnostic/__admin/__datatable/__with_options.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote cards, tables, timeline, status badges | Pending markup audit | No | Blade compile pass |
| `theme/pages/Diagnostic/__admin/__datatable/index.blade.php` | ACTIVE_APPLICATION_VIEW | Pending route/include trace | Skote cards, tables, timeline, status badges | Pending markup audit | No | Blade compile pass |
| `theme/pages/Diagnostic/__admin/__tap_view/__tickets.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote cards, tables, timeline, status badges | Pending markup audit | No | Blade compile pass |
| `theme/pages/Diagnostic/__admin/__tap_view/index.blade.php` | ACTIVE_APPLICATION_VIEW | Pending route/include trace | Skote cards, tables, timeline, status badges | Pending markup audit | No | Blade compile pass |
| `theme/pages/Diagnostic/__admin/__tap_view/tables/__taps.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote cards, tables, timeline, status badges | Pending markup audit | No | Blade compile pass |
| `theme/pages/Diagnostic/__admin/__tap_view/tables/__taps_2.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote cards, tables, timeline, status badges | Pending markup audit | No | Blade compile pass |
| `theme/pages/Diagnostic/__admin/__tap_view/tables/diagnistique-attend-bc.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote cards, tables, timeline, status badges | Pending markup audit | No | Blade compile pass |
| `theme/pages/Diagnostic/__admin/__tap_view/tables/diagnistique-attend.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote cards, tables, timeline, status badges | Pending markup audit | No | Blade compile pass |
| `theme/pages/Diagnostic/__admin/__tap_view/tables/diagnistique-non.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote cards, tables, timeline, status badges | Pending markup audit | No | Blade compile pass |
| `theme/pages/Diagnostic/__admin/index.blade.php` | ACTIVE_APPLICATION_VIEW | Pending route/include trace | Skote cards, tables, timeline, status badges | Pending markup audit | No | Blade compile pass |
| `theme/pages/Diagnostic/__admin/title.blade.php` | ACTIVE_PARTIAL | Yes — included by diagnostic manager view | Shared page header | Yes — Bootstrap 5.3.3 | Yes — manager queue description and breadcrumb | Blade compile pass |
| `theme/pages/Diagnostic/__list/__pagination.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote cards, tables, timeline, status badges | Pending markup audit | No | Blade compile pass |
| `theme/pages/Diagnostic/__list/diagnostic.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote cards, tables, timeline, status badges | Pending markup audit | No | Blade compile pass |
| `theme/pages/Diagnostic/__list/index.blade.php` | ACTIVE_APPLICATION_VIEW | Pending route/include trace | Skote cards, tables, timeline, status badges | Pending markup audit | No | Blade compile pass |
| `theme/pages/Diagnostic/__tap_view/__tickets.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote cards, tables, timeline, status badges | Pending markup audit | No | Blade compile pass |
| `theme/pages/Diagnostic/__tap_view/index.blade.php` | ACTIVE_APPLICATION_VIEW | Pending route/include trace | Skote cards, tables, timeline, status badges | Pending markup audit | No | Blade compile pass |
| `theme/pages/Diagnostic/__tap_view/tables/__taps.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote cards, tables, timeline, status badges | Pending markup audit | No | Blade compile pass |
| `theme/pages/Diagnostic/__tap_view/tables/__taps_2.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote cards, tables, timeline, status badges | Yes — Bootstrap 5.3.3 accessibility utility | Partial — `sr-only` migrated to `visually-hidden` | Blade compile pass |
| `theme/pages/Diagnostic/__tap_view/tables/diagnistique-a-reparer.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote cards, tables, timeline, status badges | Pending markup audit | No | Blade compile pass |
| `theme/pages/Diagnostic/__tap_view/tables/diagnistique-attend-bc.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote cards, tables, timeline, status badges | Pending markup audit | No | Blade compile pass |
| `theme/pages/Diagnostic/__tap_view/tables/diagnistique-cancled.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote cards, tables, timeline, status badges | Pending markup audit | No | Blade compile pass |
| `theme/pages/Diagnostic/__tap_view/tables/diagnistique-encours-de-reparation.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote cards, tables, timeline, status badges | Pending markup audit | No | Blade compile pass |
| `theme/pages/Diagnostic/__tap_view/tables/diagnistique-open.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote cards, tables, timeline, status badges | Pending markup audit | No | Blade compile pass |
| `theme/pages/Diagnostic/__tap_view/tables/diagnistique-pret-a-livre.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote cards, tables, timeline, status badges | Pending markup audit | No | Blade compile pass |
| `theme/pages/Diagnostic/__tap_view/tables/diagnistique-wait.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote cards, tables, timeline, status badges | Pending markup audit | No | Blade compile pass |
| `theme/pages/Diagnostic/index.blade.php` | ACTIVE_APPLICATION_VIEW | Pending route/include trace | Skote cards, tables, timeline, status badges | Pending markup audit | No | Blade compile pass |
| `theme/pages/Diagnostic/section_0_page_title.blade.php` | ACTIVE_PARTIAL | Yes — included by diagnostic admin view | Shared page header | Yes — Bootstrap 5.3.3 | Yes — preserved page description and home breadcrumb | Blade compile pass |
| `theme/pages/Email/compose/composeModal.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote starter/forms/tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Email/emails.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote starter/forms/tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Email/index.blade.php` | ACTIVE_APPLICATION_VIEW | Pending route/include trace | Skote starter/forms/tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Email/read/content.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote starter/forms/tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Email/read/index.blade.php` | ACTIVE_APPLICATION_VIEW | Pending route/include trace | Skote starter/forms/tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Email/section_0_page_title.blade.php` | ACTIVE_PARTIAL | Yes — included by inbox and email read views | Shared page header | Yes — Bootstrap 5.3.3 | Yes — localized communication heading | Blade compile pass |
| `theme/pages/Excel/__content.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote starter/forms/tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Excel/__folders.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote starter/forms/tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Excel/__rightbar.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote starter/forms/tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Excel/__section_title.blade.php` | ACTIVE_PARTIAL | Yes — included by backup management view | Shared page header | Yes — Bootstrap 5.3.3 | Yes — localized administration heading | Blade compile pass |
| `theme/pages/Excel/__sidebar.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote starter/forms/tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Excel/index.blade.php` | ACTIVE_APPLICATION_VIEW | Pending route/include trace | Skote starter/forms/tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/FileManager/index.blade.php` | ACTIVE_APPLICATION_VIEW | Pending route/include trace | Skote starter/forms/tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Home/filter_js.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote dashboard and charts | Pending markup audit | No | Blade compile pass |
| `theme/pages/Home/index.blade.php` | ACTIVE_APPLICATION_VIEW | Yes — referenced by active route/view | Skote dashboard and charts | Yes — Bootstrap 5.3.3 | Yes — shared page header | Blade compile pass |
| `theme/pages/Home/sections/section_0_page_title.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote dashboard and charts | Pending markup audit | No | Blade compile pass |
| `theme/pages/Home/sections/section_a_a.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote dashboard and charts | Pending markup audit | No | Blade compile pass |
| `theme/pages/Home/sections/section_a_chart.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote dashboard and charts | Pending markup audit | No | Blade compile pass |
| `theme/pages/Home/sections/section_a_orders.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote dashboard and charts | Pending markup audit | No | Blade compile pass |
| `theme/pages/Home/sections/section_a_period.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote dashboard and charts | Pending markup audit | No | Blade compile pass |
| `theme/pages/Home/sections/section_b_activity.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote dashboard and charts | Pending markup audit | No | Blade compile pass |
| `theme/pages/Home/sections/section_b_b.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote dashboard and charts | Pending markup audit | No | Blade compile pass |
| `theme/pages/Home/sections/section_b_social_source.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote dashboard and charts | Pending markup audit | No | Blade compile pass |
| `theme/pages/Home/sections/section_b_top.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote dashboard and charts | Pending markup audit | No | Blade compile pass |
| `theme/pages/Home/sections/section_c_c.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote dashboard and charts | Pending markup audit | No | Blade compile pass |
| `theme/pages/Home/sections/section_c_transactions.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote dashboard and charts | Pending markup audit | No | Blade compile pass |
| `theme/pages/Home/sections/section_dd.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote dashboard and charts | Pending markup audit | No | Blade compile pass |
| `theme/pages/Importer/__form.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote starter/forms/tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Importer/index.blade.php` | ACTIVE_APPLICATION_VIEW | Pending route/include trace | Skote starter/forms/tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Importer/section_0_page_title.blade.php` | ACTIVE_PARTIAL | Yes — included by CSV import route view | Shared page header | Yes — Bootstrap 5.3.3 | Yes — localized tool heading | Blade compile pass |
| `theme/pages/PermissionRole/Permission/__permissions.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote starter/forms/tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/PermissionRole/Permission/_items.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote list and pagination | Yes — Bootstrap 5.3.3 pagination | Partial — Laravel Bootstrap 5 pagination template | Blade compile pass |
| `theme/pages/PermissionRole/Permission/index.blade.php` | ACTIVE_APPLICATION_VIEW | Pending route/include trace | Skote starter/forms/tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/PermissionRole/Role/_items.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote list and pagination | Yes — Bootstrap 5.3.3 pagination | Partial — Laravel Bootstrap 5 pagination template | Blade compile pass |
| `theme/pages/PermissionRole/Role/index.blade.php` | ACTIVE_APPLICATION_VIEW | Pending route/include trace | Skote starter/forms/tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/PermissionRole/section_0_page_title.blade.php` | ACTIVE_PARTIAL | Yes — included by role and permission lists | Shared page header | Yes — Bootstrap 5.3.3 | Yes — French administration heading | Blade compile pass |
| `theme/pages/Profile/index.blade.php` | ACTIVE_APPLICATION_VIEW | Pending route/include trace | Skote contacts/profile, forms, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Profile/profile.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote contacts/profile, forms, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Profile/section_0_page_title.blade.php` | ACTIVE_PARTIAL | Yes — included by profile/settings views | Shared page header | Yes — Bootstrap 5.3.3 | Yes — French account heading | Blade compile pass |
| `theme/pages/Profile/settings/_company_form.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote contacts/profile, forms, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Profile/settings/_user_info_form.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote contacts/profile, forms, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Profile/settings/index.blade.php` | ACTIVE_APPLICATION_VIEW | Pending route/include trace | Skote contacts/profile, forms, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Profile/settings/settings.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote contacts/profile, forms, tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Reparation/__list/__pagination.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote cards, tables, timeline, status badges | Pending markup audit | No | Blade compile pass |
| `theme/pages/Reparation/__list/index.blade.php` | ACTIVE_PARTIAL | Yes — included by the repair queue view | Skote responsive cards, grouped work queue, empty states | Yes — Bootstrap 5.3.3 | Yes — unchanged status groups and ticket links | Blade compile pass |
| `theme/pages/Reparation/__list/reparation.blade.php` | ACTIVE_PARTIAL | Yes — included by `Reparation/index` returned from the index controller | Skote grouped cards and status badges | Yes — Bootstrap 5.3.3 | Yes — three responsive stages, counts, dates, existing repair links, empty states | Blade compile pass |
| `theme/pages/Reparation/__single/detail.blade.php` | ACTIVE_PARTIAL | Yes — included by active repair detail view | Skote cards and responsive detail layout | Yes — Bootstrap 5.3.3 | Yes — composes diagnosis context and repair report | Blade compile pass |
| `theme/pages/Reparation/__single/index.blade.php` | ACTIVE_APPLICATION_VIEW | Yes — returned by the repair detail controller | Shared page header and Skote workflow cards | Yes — Bootstrap 5.3.3 | Yes — active repair detail wrapper and existing scripts preserved | Blade compile pass |
| `theme/pages/Reparation/__single/section_a.blade.php` | ACTIVE_PARTIAL | Yes — included by active repair detail partial | Skote information cards | Yes — Bootstrap 5.3.3 | Yes — device, diagnosis report, and technician context | Blade compile pass |
| `theme/pages/Reparation/__single/section_b.blade.php` | ACTIVE_PARTIAL | Yes — included by active repair detail partial | Skote form card and validation states | Yes — Bootstrap 5.3.3 | Yes — repair report, completion action, closed-report state and permission checks retained | Blade compile pass |
| `theme/pages/Reparation/index.blade.php` | ACTIVE_APPLICATION_VIEW | Yes — returned by the repair queue controller | Shared page header and Skote cards | Yes — Bootstrap 5.3.3 | Yes — queue wrapper | Blade compile pass |
| `theme/pages/Reparation/section_0_page_title.blade.php` | ACTIVE_PARTIAL | Yes — included by both active repair views | Shared page-header component | Yes — Bootstrap 5.3.3 | Yes — queue/detail titles and breadcrumbs | Blade compile pass |
| `theme/pages/Report/__datatable/__with_options.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote starter/forms/tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Report/__datatable/graphs.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote starter/forms/tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Report/__datatable/index.blade.php` | ACTIVE_APPLICATION_VIEW | Pending route/include trace | Skote starter/forms/tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Report/__datatable/periode_filter.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote starter/forms/tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Report/section_0_page_title.blade.php` | ACTIVE_PARTIAL | Yes — included by report route view | Shared page header | Yes — Bootstrap 5.3.3 | Yes — analysis breadcrumb | Blade compile pass |
| `theme/pages/Setting/__menu.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote starter/forms/tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Setting/compose/composeModal.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote starter/forms/tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Setting/emails.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote starter/forms/tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Setting/index.blade.php` | ACTIVE_APPLICATION_VIEW | Pending route/include trace | Skote starter/forms/tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Setting/read/content.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote starter/forms/tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Setting/read/index.blade.php` | ACTIVE_APPLICATION_VIEW | Pending route/include trace | Skote starter/forms/tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/Setting/section_0_page_title.blade.php` | ACTIVE_PARTIAL | Yes — included by application settings view | Shared page header | Yes — Bootstrap 5.3.3 | Yes — administration heading without dead JavaScript link | Blade compile pass |
| `theme/pages/Ticket/__create/__create_client_modal.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote cards, tables, timeline, status badges | Pending markup audit | No | Blade compile pass |
| `theme/pages/Ticket/__create/__create_client_modal_form.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote cards, tables, timeline, status badges | Pending markup audit | No | Blade compile pass |
| `theme/pages/Ticket/__create/form.blade.php` | ACTIVE_PARTIAL | Yes — included by ticket create route view | Skote forms and cards | Yes — Bootstrap 5.3.3 | Yes — responsive intake form, accessible labels, old input retained | Blade compile pass |
| `theme/pages/Ticket/__create/index.blade.php` | ACTIVE_APPLICATION_VIEW | Pending route/include trace | Skote cards, tables, timeline, status badges | Pending markup audit | No | Blade compile pass |
| `theme/pages/Ticket/__datatable/__default.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote cards, tables, timeline, status badges | Pending markup audit | No | Blade compile pass |
| `theme/pages/Ticket/__datatable/__filters.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote cards, tables, timeline, status badges | Pending markup audit | No | Blade compile pass |
| `theme/pages/Ticket/__datatable/__js_filters.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote cards, tables, timeline, status badges | Pending markup audit | No | Blade compile pass |
| `theme/pages/Ticket/__datatable/__settings_modal.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote cards, tables, timeline, status badges | Pending markup audit | No | Blade compile pass |
| `theme/pages/Ticket/__datatable/__tickets_table.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote cards, tables, timeline, status badges | Pending markup audit | No | Blade compile pass |
| `theme/pages/Ticket/__datatable/__with_options.blade.php` | ACTIVE_PARTIAL | Yes — included by the ticket index route view | Skote table, card, action groups | Yes — Bootstrap 5.3.3 table utilities | Partial — responsive card/table presentation and accessible action labels; DataTables BS4 adapter remains | Blade compile pass |
| `theme/pages/Ticket/__datatable/index.blade.php` | ACTIVE_APPLICATION_VIEW | Pending route/include trace | Skote cards, tables, timeline, status badges | Pending markup audit | No | Blade compile pass |
| `theme/pages/Ticket/__diagnostic/detail.blade.php` | ACTIVE_PARTIAL | Yes — included by the active diagnosis route view | Skote ticket summary, image gallery, and workflow cards | Yes — Bootstrap 5.3.3 | Yes — diagnosis form, estimate response, closure states, and existing authorization retained | Blade compile pass |
| `theme/pages/Ticket/__diagnostic/index.blade.php` | ACTIVE_APPLICATION_VIEW | Yes — returned by ticket diagnosis action | Shared ticket page header and workflow detail | Yes — Bootstrap 5.3.3 | Yes — responsive diagnostic workflow view | Blade compile pass |
| `theme/pages/Ticket/__edit/__form_edit.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote cards, tables, timeline, status badges | Pending markup audit | No | Blade compile pass |
| `theme/pages/Ticket/__edit/__info.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote cards, tables, timeline, status badges | Pending markup audit | No | Blade compile pass |
| `theme/pages/Ticket/__edit/__reassignment.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote cards, tables, timeline, status badges | Pending markup audit | No | Blade compile pass |
| `theme/pages/Ticket/__edit/__ticket_actions.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote cards, tables, timeline, status badges | Pending markup audit | No | Blade compile pass |
| `theme/pages/Ticket/__edit/index.blade.php` | ACTIVE_APPLICATION_VIEW | Pending route/include trace | Skote cards, tables, timeline, status badges | Pending markup audit | No | Blade compile pass |
| `theme/pages/Ticket/__edit_old/form.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote cards, tables, timeline, status badges | Pending markup audit | No | Blade compile pass |
| `theme/pages/Ticket/__edit_old/index.blade.php` | ACTIVE_APPLICATION_VIEW | Pending route/include trace | Skote cards, tables, timeline, status badges | Pending markup audit | No | Blade compile pass |
| `theme/pages/Ticket/__historical/index.blade.php` | ACTIVE_APPLICATION_VIEW | Yes — returned by ticket historical action | Skote timeline | Yes — Bootstrap 5.3.3 | Partial — shared ticket heading and history timeline | Blade compile pass |
| `theme/pages/Ticket/__historical/style_1.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote cards, tables, timeline, status badges | Pending markup audit | No | Blade compile pass |
| `theme/pages/Ticket/__historical/style_2.blade.php` | ACTIVE_PARTIAL | Yes — included by active ticket historical route view | Skote timeline and cards | Yes — Bootstrap 5.3.3 | Yes — renders ticket status pivot descriptions and timestamps with empty state | Blade compile pass |
| `theme/pages/Ticket/__invoiceable/__datatable/__tickets_table.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote cards, tables, timeline, status badges | Pending markup audit | No | Blade compile pass |
| `theme/pages/Ticket/__invoiceable/__datatable/__with_options.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote cards, tables, timeline, status badges | Pending markup audit | No | Blade compile pass |
| `theme/pages/Ticket/__invoiceable/__datatable/__with_options2.blade.php` | ACTIVE_PARTIAL | Yes — invoiceable ticket route | Skote responsive table | Yes — Bootstrap 5.3.3 table utilities | Partial — responsive table presentation; DataTables adapter migration remains outside this file | Blade compile pass |
| `theme/pages/Ticket/__invoiceable/__datatable/index.blade.php` | ACTIVE_APPLICATION_VIEW | Pending route/include trace | Skote cards, tables, timeline, status badges | Pending markup audit | No | Blade compile pass |
| `theme/pages/Ticket/__media/__files.blade.php` | ACTIVE_PARTIAL | Yes — included by ticket media route view | Skote file table and upload card | Yes — Bootstrap 5.3.3 | Yes — accessible media listing, delete forms, upload validation feedback and empty state | Blade compile pass |
| `theme/pages/Ticket/__media/index.blade.php` | ACTIVE_APPLICATION_VIEW | Yes — returned by ticket media action | Skote file management | Yes — Bootstrap 5.3.3 | Partial — shared ticket heading and media management layout | Blade compile pass |
| `theme/pages/Ticket/__new_create/__form_new_create.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote cards, tables, timeline, status badges | Pending markup audit | No | Blade compile pass |
| `theme/pages/Ticket/__new_create/__info.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote cards, tables, timeline, status badges | Pending markup audit | No | Blade compile pass |
| `theme/pages/Ticket/__new_create/__ticket_actions.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote cards, tables, timeline, status badges | Pending markup audit | No | Blade compile pass |
| `theme/pages/Ticket/__new_create/index.blade.php` | ACTIVE_APPLICATION_VIEW | Pending route/include trace | Skote cards, tables, timeline, status badges | Pending markup audit | No | Blade compile pass |
| `theme/pages/Ticket/__normal_table/__filters.blade.php` | LEGACY_OR_UNUSED | No active references; parent view is not routed | Legacy ticket filters | Pending markup audit | No — retained, not deleted | Blade compile pass |
| `theme/pages/Ticket/__normal_table/index.blade.php` | LEGACY_OR_UNUSED | Only referenced from a commented include in `Ticket/index` | Legacy ticket list variant | Pending markup audit | No — retained, not deleted | Blade compile pass |
| `theme/pages/Ticket/__normal_table/table.blade.php` | LEGACY_OR_UNUSED | Included only by the unrouted legacy list view | Legacy ticket table | Yes — Bootstrap 5 pagination view name corrected | No — retained, not deleted | Blade compile pass |
| `theme/pages/Ticket/__pdf/Report/index.blade.php` | ACTIVE_APPLICATION_VIEW | Pending route/include trace | Skote cards, tables, timeline, status badges | Pending markup audit | No | Blade compile pass |
| `theme/pages/Ticket/__pret_livre/__datatable/__filters.blade.php` | ACTIVE_PARTIAL | Yes — included by active ready-for-delivery view | Skote filter card and Bootstrap 5 dropdowns | Yes — Bootstrap 5.3.3 | Partial — existing filters retained | Blade compile pass |
| `theme/pages/Ticket/__pret_livre/__datatable/__js_filters.blade.php` | ACTIVE_PARTIAL | Yes — pushed by active ready-for-delivery view | Existing filter behavior | Yes — Bootstrap 5.3.3 | Reviewed; route/query behavior retained | Blade compile pass |
| `theme/pages/Ticket/__pret_livre/__datatable/__tickets_table.blade.php` | ACTIVE_PARTIAL | Yes — included by active ready-for-delivery view | Skote responsive delivery table | Yes — Bootstrap 5.3.3 | Yes — wraps the shared ticket table | Blade compile pass |
| `theme/pages/Ticket/__pret_livre/__datatable/__with_options.blade.php` | ACTIVE_PARTIAL | Yes — included by active ready-for-delivery view | Skote responsive table and action controls | Yes — Bootstrap 5.3.3 | Yes — status badges, delivery states, accessible confirmation actions; permission branches retained | Blade compile pass |
| `theme/pages/Ticket/__pret_livre/__datatable/confirm_form.blade.php` | ACTIVE_PARTIAL | Yes — included by regular delivery dialog | Skote modal form | Yes — Bootstrap 5.3.3 | Yes — unique notes ID, accessible errors, unchanged POST field names | Blade compile pass |
| `theme/pages/Ticket/__pret_livre/__datatable/confirm_form_info.blade.php` | ACTIVE_PARTIAL | Yes — included by regular delivery form | Skote form controls | Yes — Bootstrap 5.3.3 | Yes — accessible labels/errors; existing date/mode/client fields retained | Blade compile pass |
| `theme/pages/Ticket/__pret_livre/__datatable/confirme.blade.php` | ACTIVE_PARTIAL | Yes — rendered per ticket with `@each` | Bootstrap 5.3.3 delivery dialog | Yes — Bootstrap 5.3.3 | Yes — corrected dialog label and ticket-specific title | Blade compile pass |
| `theme/pages/Ticket/__pret_livre/__datatable/confirme_admin.blade.php` | ACTIVE_PARTIAL | Yes — rendered per ticket with `@each` | Bootstrap 5.3.3 admin confirmation dialog | Yes — Bootstrap 5.3.3 | Yes — corrected dialog label and accessible cancel/confirm actions | Blade compile pass |
| `theme/pages/Ticket/__pret_livre/__datatable/index.blade.php` | ACTIVE_APPLICATION_VIEW | Yes — returned by `DashboardController` | Shared ticket header, filters, delivery table, and dialogs | Yes — Bootstrap 5.3.3 presentation; legacy DataTables BS4 adapter remains | Partial — workflow view and nested delivery actions updated | Blade compile pass |
| `theme/pages/Ticket/__single_v2/article_detail.blade.php` | ACTIVE_PARTIAL | Yes — included by active ticket detail route view | Skote product-detail imagery adapted to a service-ticket summary | Yes — Bootstrap 5.3.3 tabs, cards, responsive grid | Yes — device summary, status, description, photo tabs/empty state, preserved actions | Blade compile pass |
| `theme/pages/Ticket/__single_v2/index.blade.php` | ACTIVE_APPLICATION_VIEW | Yes — returned by ticket show action | Skote cards, tabs, and detail layout | Yes — Bootstrap 5.3.3 | Yes — ticket detail structure | Blade compile pass |
| `theme/pages/Ticket/__single_v2/section_a_title.blade.php` | ACTIVE_PARTIAL | Yes — included by ticket detail route view | Shared page header | Yes — Bootstrap 5.3.3 | Yes — shared title and breadcrumb | Blade compile pass |
| `theme/pages/Ticket/__single_v2/section_attached_files.blade.php` | ACTIVE_PARTIAL | Yes — included by ticket detail route view under `ticket.read` | Skote document table/card | Yes — Bootstrap 5.3.3 | Yes — readable linked estimate/invoice/report rows; guarded inclusion retained | Blade compile pass |
| `theme/pages/Ticket/__single_v2/section_bas.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote cards, tables, timeline, status badges | Pending markup audit | No | Blade compile pass |
| `theme/pages/Ticket/__single_v2/section_logs.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote cards, tables, timeline, status badges | Pending markup audit | No | Blade compile pass |
| `theme/pages/Ticket/__single_v2/section_ticket_info.blade.php` | ACTIVE_PARTIAL | Yes — included by active ticket detail route view | Skote card and information table | Yes — Bootstrap 5.3.3 | Yes — ticket status, assignment, client, and dates grouped clearly | Blade compile pass |
| `theme/pages/Ticket/index.blade.php` | ACTIVE_APPLICATION_VIEW | Yes — returned by ticket index and old-list actions | Skote shared page header, card, responsive DataTable | Yes — Bootstrap 5.3.3 presentation; legacy DataTables BS4 adapters remain | Partial — ticket queue layout, table and action labels | Blade compile pass |
| `theme/pages/Ticket/section_0_page_title.blade.php` | ACTIVE_PARTIAL | Yes — included by active ticket list/create/edit/media/history views | Shared page header | Yes — Bootstrap 5.3.3 | Yes — shared title, ticket breadcrumb, permission-aware create action | Blade compile pass |
| `theme/pages/TicketRapport/Edition/Edit/__form_edit.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote starter/forms/tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/TicketRapport/Edition/Edit/__info.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote starter/forms/tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/TicketRapport/Edition/Edit/__ticket_actions.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote starter/forms/tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/TicketRapport/Edition/Edit/index.blade.php` | ACTIVE_APPLICATION_VIEW | Pending route/include trace | Skote starter/forms/tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/TicketRapport/Edition/__datatable/__clients_table.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote starter/forms/tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/TicketRapport/Edition/__datatable/__with_options.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote starter/forms/tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/TicketRapport/Edition/__datatable/index.blade.php` | ACTIVE_APPLICATION_VIEW | Pending route/include trace | Skote starter/forms/tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/TicketRapport/__datatable/__clients_table.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote starter/forms/tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/TicketRapport/__datatable/__with_options.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote starter/forms/tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/TicketRapport/__datatable/index.blade.php` | ACTIVE_APPLICATION_VIEW | Pending route/include trace | Skote starter/forms/tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/TicketRapport/__edit/__add_phones.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote starter/forms/tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/TicketRapport/__edit/__edit_emails.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote starter/forms/tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/TicketRapport/__edit/__edit_phones.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote starter/forms/tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/TicketRapport/__edit/form_2.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote starter/forms/tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/TicketRapport/__edit/index.blade.php` | ACTIVE_APPLICATION_VIEW | Yes — returned by technical report edit controller | Shared technical-report page header | Yes — Bootstrap 5.3.3 | Partial — now uses the technical report title instead of the client title | Blade compile pass |
| `theme/pages/TicketRapport/__profile/index.blade.php` | ACTIVE_APPLICATION_VIEW | Pending route/include trace | Skote starter/forms/tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/TicketRapport/__profile/profile.blade.php` | ACTIVE_PARTIAL | Pending route/include trace | Skote starter/forms/tables | Pending markup audit | No | Blade compile pass |
| `theme/pages/TicketRapport/section_0_page_title.blade.php` | ACTIVE_PARTIAL | Yes — included by technical report list/edit views | Shared page header | Yes — Bootstrap 5.3.3 | Yes — localized service heading | Blade compile pass |
| `theme/pages/Warranty/__datatable/__add_warranty.blade.php` | ACTIVE_PARTIAL | Included by active warranty page, but no active trigger found; controller create/store methods are empty | Skote modal form | Yes — Bootstrap 5.3.3 | Partial — form presentation updated without exposing the modal or changing its route/fields | Blade compile pass |
| `theme/pages/Warranty/__datatable/__with_options.blade.php` | ACTIVE_PARTIAL | Yes — included by active warranty page | Skote responsive table and empty state | Yes — Bootstrap 5.3.3 | Yes — readable status/dates and accessible selection labels | Blade compile pass |
| `theme/pages/Warranty/__datatable/index.blade.php` | ACTIVE_APPLICATION_VIEW | Yes — returned by `WarrantyController` | Shared warranty header and Skote table | Yes — Bootstrap 5.3.3 presentation; legacy DataTables BS4 adapter remains | Partial — active warranty list and included form presentation | Blade compile pass |
| `theme/pages/Warranty/index.blade.php` | LEGACY_OR_UNUSED | No controller/route reference found; controller returns the `__datatable` view | Legacy warranty page | Pending markup audit | No — retained, not deleted | Blade compile pass |
| `theme/pages/Warranty/section_0_page_title.blade.php` | ACTIVE_PARTIAL | Yes — included by warranty list | Shared page header | Yes — Bootstrap 5.3.3 | Yes — simplified service breadcrumb | Blade compile pass |
| `theme/pdf/partials/footer.blade.php` | PDF_VIEW | Excluded from web UI redesign | Separate document layout | N/A | No | Blade compile pass |
| `vendor/honeypot/honeypotFormFields.blade.php` | ACTIVE_PARTIAL | Pending package/config reference audit | Package override; inspect usage | Pending markup audit | No | Blade compile pass |
| `vendor/invoices/templates/default.blade.php` | PDF_VIEW | Excluded from web UI redesign | Separate document layout | N/A | No | Blade compile pass |
| `vendor/livewire/bootstrap.blade.php` | LIVEWIRE_VIEW | Pending component/include trace | Skote forms, cards, tables, or tabs | Pending markup audit | No | Blade compile pass |
| `vendor/livewire/simple-bootstrap.blade.php` | LIVEWIRE_VIEW | Pending component/include trace | Skote forms, cards, tables, or tabs | Pending markup audit | No | Blade compile pass |
| `vendor/livewire/simple-tailwind.blade.php` | LIVEWIRE_VIEW | Pending component/include trace | Skote forms, cards, tables, or tabs | Pending markup audit | No | Blade compile pass |
| `vendor/livewire/tailwind.blade.php` | LIVEWIRE_VIEW | Pending component/include trace | Skote forms, cards, tables, or tabs | Pending markup audit | No | Blade compile pass |
| `vendor/mail/html/button.blade.php` | EMAIL_VIEW | Excluded from web UI redesign | Email layout | N/A | No | Blade compile pass |
| `vendor/mail/html/footer.blade.php` | EMAIL_VIEW | Excluded from web UI redesign | Email layout | N/A | No | Blade compile pass |
| `vendor/mail/html/header.blade.php` | EMAIL_VIEW | Excluded from web UI redesign | Email layout | N/A | No | Blade compile pass |
| `vendor/mail/html/layout.blade.php` | EMAIL_VIEW | Excluded from web UI redesign | Email layout | N/A | No | Blade compile pass |
| `vendor/mail/html/message.blade.php` | EMAIL_VIEW | Excluded from web UI redesign | Email layout | N/A | No | Blade compile pass |
| `vendor/mail/html/panel.blade.php` | EMAIL_VIEW | Excluded from web UI redesign | Email layout | N/A | No | Blade compile pass |
| `vendor/mail/html/subcopy.blade.php` | EMAIL_VIEW | Excluded from web UI redesign | Email layout | N/A | No | Blade compile pass |
| `vendor/mail/html/table.blade.php` | EMAIL_VIEW | Excluded from web UI redesign | Email layout | N/A | No | Blade compile pass |
| `vendor/mail/text/button.blade.php` | EMAIL_VIEW | Excluded from web UI redesign | Email layout | N/A | No | Blade compile pass |
| `vendor/mail/text/footer.blade.php` | EMAIL_VIEW | Excluded from web UI redesign | Email layout | N/A | No | Blade compile pass |
| `vendor/mail/text/header.blade.php` | EMAIL_VIEW | Excluded from web UI redesign | Email layout | N/A | No | Blade compile pass |
| `vendor/mail/text/layout.blade.php` | EMAIL_VIEW | Excluded from web UI redesign | Email layout | N/A | No | Blade compile pass |
| `vendor/mail/text/message.blade.php` | EMAIL_VIEW | Excluded from web UI redesign | Email layout | N/A | No | Blade compile pass |
| `vendor/mail/text/panel.blade.php` | EMAIL_VIEW | Excluded from web UI redesign | Email layout | N/A | No | Blade compile pass |
| `vendor/mail/text/subcopy.blade.php` | EMAIL_VIEW | Excluded from web UI redesign | Email layout | N/A | No | Blade compile pass |
| `vendor/mail/text/table.blade.php` | EMAIL_VIEW | Excluded from web UI redesign | Email layout | N/A | No | Blade compile pass |
| `vendor/media-library/image.blade.php` | ACTIVE_PARTIAL | Pending package/config reference audit | Package override; inspect usage | Pending markup audit | No | Blade compile pass |
| `vendor/media-library/placeholderSvg.blade.php` | ACTIVE_PARTIAL | Pending package/config reference audit | Package override; inspect usage | Pending markup audit | No | Blade compile pass |
| `vendor/media-library/responsiveImage.blade.php` | ACTIVE_PARTIAL | Pending package/config reference audit | Package override; inspect usage | Pending markup audit | No | Blade compile pass |
| `vendor/media-library/responsiveImageWithPlaceholder.blade.php` | ACTIVE_PARTIAL | Pending package/config reference audit | Package override; inspect usage | Pending markup audit | No | Blade compile pass |
| `vendor/notifications/email.blade.php` | EMAIL_VIEW | Excluded from web UI redesign | Email layout | N/A | No | Blade compile pass |
| `vendor/pagination/bootstrap-4.blade.php` | ACTIVE_PARTIAL | Pending package/config reference audit | Package override; inspect usage | Pending markup audit | No | Blade compile pass |
| `vendor/pagination/bootstrap.blade.php` | ACTIVE_PARTIAL | Pending package/config reference audit | Package override; inspect usage | Pending markup audit | No | Blade compile pass |
| `vendor/pagination/default.blade.php` | ACTIVE_PARTIAL | Pending package/config reference audit | Package override; inspect usage | Pending markup audit | No | Blade compile pass |
| `vendor/pagination/semantic-ui.blade.php` | ACTIVE_PARTIAL | Pending package/config reference audit | Package override; inspect usage | Pending markup audit | No | Blade compile pass |
| `vendor/pagination/simple-bootstrap-4.blade.php` | ACTIVE_PARTIAL | Pending package/config reference audit | Package override; inspect usage | Pending markup audit | No | Blade compile pass |
| `vendor/pagination/simple-default.blade.php` | ACTIVE_PARTIAL | Pending package/config reference audit | Package override; inspect usage | Pending markup audit | No | Blade compile pass |
| `vendor/pagination/simple-tailwind.blade.php` | ACTIVE_PARTIAL | Pending package/config reference audit | Package override; inspect usage | Pending markup audit | No | Blade compile pass |
| `vendor/pagination/tailwind.blade.php` | ACTIVE_PARTIAL | Pending package/config reference audit | Package override; inspect usage | Pending markup audit | No | Blade compile pass |
| `welcome.blade.php` | LEGACY_OR_UNUSED | No application route/view reference; `/` redirects to `/app` | Laravel welcome screen | Separate standalone screen | No — retained, not served by the current route map | Blade compile pass |
