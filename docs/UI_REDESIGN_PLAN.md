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

## Phase 1 — Discovery

- [x] Read project AI rules and `docs/UI_DESIGN_SYSTEM.md`.
- [x] Inventory page folders, layouts, shared components, Livewire classes, asset entry points, and major view plugins.
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
- App Vite CSS and the shared Bootstrap bundle now declare **5.3.3**, matching Skote's reference assets. Keep runtime references in app-owned paths and verify both headers if updating the assets.
- Skote styles and theme fonts are already adapted into the app. Keep `admin-panel-theme/` read-only and do not make runtime assets depend on demo paths.
- Styles and scripts are split across `resources/css`, `resources/js`, `public/css/custom.css`, `public/assets/libs`, and view-level declarations. Consolidate ownership carefully rather than moving assets wholesale.
- Bootstrap 4 pagination templates are used in role/permission lists inside the Bootstrap 5 application.
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

### Phase 2 — Foundation (in progress)

- [x] Align the app's Bootstrap CSS and JavaScript assets to Skote Bootstrap 5.3.3; runtime assets remain app-owned.
- [x] Build the Vite assets; `npm run build` passes with Bootstrap 5.3.3.
- [x] Compare the app's shell structure with Skote's colored-sidebar and starter layouts; the existing shell already uses the same vertical-menu/main-content structure, so no shell markup changes were needed in this foundation pass.
- [ ] Visually inspect the app shell, navigation, page title/breadcrumbs, content gutters, shared alerts, modal, and footer against Skote layouts in a running browser.
- [ ] Establish reusable patterns only where the discovery pass confirms genuine repetition.
- [ ] Confirm authentication, role/permission navigation, responsive shell, Vite assets, and Livewire shell integration.

### Phase 3 — Core operations

- [ ] Dashboard and profile.
- [ ] Clients.
- [ ] Tickets, including create/edit/detail/history/media/ready-for-delivery views.
- [ ] Diagnosis queues and diagnosis report screen.
- [ ] Repair queues and repair report screen.
- [ ] Reception, delivery, and warranty.

### Phase 4 — Commercial

- [ ] Estimates and estimate-from-ticket flows.
- [ ] Invoices, invoice-from-estimate flows, article/ticket selection, and payment screens.
- [ ] Credit notes.
- [ ] Supplier bills/payments.
- [ ] Purchase orders, delivery slips, suppliers, and companies.

### Phase 5 — Administration and remaining active screens

- [ ] Users and role/permission management.
- [ ] Categories, settings, backups, reports, calendar, contacts, inbox, imports/exports, chat, and file manager.
- [ ] Authentication and error pages where they are part of the active UI scope.
- [ ] Confirm route reachability before labeling any remaining view as legacy.

### Phase 6 — Consistency and completion audit

- [ ] Scan active route/view mappings for remaining visual variants.
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

- **Phase 1 Discovery:** complete. Inventory and source map recorded above; no application views, routes, business logic, database schema, or production data have been changed.
- **Phase 2 Foundation:** in progress. Bootstrap CSS and JavaScript are aligned to 5.3.3, and `npm run build` passes. Static review confirms the app uses Skote's vertical-sidebar shell structure. Browser visual/interactive verification remains pending because no browser session is available in this workspace.
- **Phases 3–6:** not started.
