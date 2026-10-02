# MaintenanceApp — Architecture & Code Audit

**Audit scope:** repository source, configuration, migrations, routes, views and existing automated checks. This is a static, read-only review; no application source or data was changed. The report itself is the only file created for this audit.

**Production safety:** MaintenanceApp is production software. No production database was queried or changed. Do not edit already-deployed migrations or make schema/financial changes from this source audit alone. All persisted-data changes require read-only compatibility checks, an approved forward-migration plan, verified backups and a rollback plan.

**Evidence labels:** **CONFIRMED FACT** is directly visible in repository code or command output; **INFERENCE** is a likely consequence that depends on runtime/deployment details; **RECOMMENDATION** describes a practical follow-up.

## 1. Executive Summary

MaintenanceApp is a Laravel application for repair intake and service operations, with a connected commercial document workflow. It tracks clients, repair tickets, diagnosis/repair reports, quotes, invoices, supplier orders, delivery notes and payment records. Laravel 13 / PHP 8.4 / Livewire 4 are present in Composer; PDF generation is implemented through a small application wrapper around mPDF.

The project has useful domain separation, route-level permissions on many staff operations, UUID route keys, dedicated request classes and database foreign keys in several core relationships. Its major risks are an unauthenticated developer-operation surface, an unauthenticated API that returns all client records, stored unescaped content rendered as HTML, and floating-point storage for financial values. The current suite contains only two example tests, one of which fails because it expects `/` to return 200 while the route redirects to `/app`.

**Finding counts:** Critical 0 · High 4 · Medium 5 · Low 3. These severities describe source-level risk. Whether public routes are reachable from the internet depends on deployment and network configuration.

## 2. Confirmed Technology Stack

- PHP `^8.4`; Laravel framework `^13.0`.
- Livewire `^4.4`; Sanctum `^4.3`; Laravel UI `^4.6`.
- `mpdf/mpdf:^8.2`, with app-owned `Pdf` facade, generator and document classes.
- Spatie packages: Permission, Media Library, Backup, Query Builder, Settings, Scout; Maatwebsite Excel 4.
- Redis client Predis, Guzzle, Google Drive and Dropbox Flysystem adapters, Laravel Phone.
- PHPUnit/Pest 5, Laravel Pint, Debugbar, Boost and Sail are development dependencies. The tests use PHPUnit-style classes.
- Frontend uses Laravel Mix 6, Sass and Bootstrap Icons. `package.json` has build/watch scripts but no frontend test or lint scripts.
- Composer autoloads custom package classes under `Elmarzougui\` from `packages/`; helper functions load from `helpers/helpers.php`.

## 3. Application Purpose

**CONFIRMED FACT:** the implementation manages maintenance/service tickets and commercial documents for clients. Ticket records include a device/article, description, status and repair state, client, technician, media, reports, delivery and document links. Finance records include estimates, invoices, credit notes (`InvoiceAvoir`), supplier purchase orders (`BCommand`), delivery slips (`BLivraison`) and bills/payment entries. Admin screens cover users, roles/permissions, categories, settings, reports and backups.

The implementation contains routes and controllers for these functions; README.md itself is mostly project branding and does not describe the application architecture. Several feature/refactor markdown files document specific work but should be treated as notes rather than the system specification.

## 4. Architecture Overview

The application is a conventional Laravel MVC application with older/customized bootstrap and HTTP kernel bindings. Routes are split into public web, authenticated admin, authenticated commercial, backup and developer route files. Domain code sits mainly under `app/Models`, `app/Http/Controllers`, `app/Http/Livewire`, `app/Services`, `app/Repositories`, `app/Actions`, and supporting directories. Blade templates live under `resources/views/theme`; PDF templates are separate Blade trees.

```text
Browser / API client
  ├─ public web routes ── auth/reset, public document PDFs, import endpoints
  ├─ /app routes ──────── auth + route permissions ── tickets / diagnostics / repairs / reports
  ├─ /app/commercial ──── auth + route permissions ── estimates / invoices / payments / orders
  └─ /dev routes ──────── web middleware only ─────── maintenance / migration / composer operations

Controllers ── Form Requests ── Eloquent models ── migrations/database
      ├─ Livewire components ── interactive ticket/document forms
      ├─ Jobs / queue batches ── CSV imports
      ├─ Blade views ── mPDF wrapper ── streamed PDF
      └─ Media Library / filesystem, mail, Excel, charts, backup and API resources
```

`RouteServiceProvider` attaches `web,auth` to the main staff and commercial groups, and `web,auth,role:SuperAdmin` to backups. The developer group gets only `web`. The console kernel defines no scheduled tasks. API routes use the `api` middleware group; only `/api/user` is protected by Sanctum in the route file.

## 5. Domain Map

| Area | Main code | Responsibility confirmed in source |
|---|---|---|
| Repair operations | `Ticket`, `Status`, `TicketStatus`, `TicketController`, `DiagnostiqueController`, `ReparationController` | Ticket intake, status history, diagnosis, repair completion, assigned technicians, images and reports |
| Clients | `Client`, `Category`, `Contact`, `Telephone`, client controllers/import/export | Customer identity and contact details, tickets, invoices, delivery slips and media |
| Commercial finance | `Finance\Estimate`, `Invoice`, `InvoiceAvoir`, `Article`, `Bill` | Quotes, invoices, credit notes, line items and bills/payment records |
| Purchasing/delivery | `Provider`, `BCommand`, `BLivraison` | Suppliers, purchase orders and delivery notes |
| Access/admin | `User`, Spatie roles/permissions, admin controllers, `Setting` | Staff accounts, access administration, app configuration and backups |
| Reports/documents | report controllers, chart models, PDF controllers and Blade templates | Service reports, commercial PDFs and dashboard/chart data |
| Import/export | Livewire CSV importer, queued `CSVImporterJob`, Excel classes/controllers | Client/sale CSV and spreadsheet workflows; route/controller variants coexist |
| Custom modules | `packages/Payment`, `packages/Roles` | Small local payment/role abstractions; usage and completeness need human verification |

## 6. Data Model

- `Client` has many tickets, invoices, credit notes and delivery slips; it also has polymorphic telephone/email records and a category/company association.
- `Ticket` belongs to a client and technician, has status history, reports, media, and document relationships. It can connect to estimates/invoices through pivot tables.
- `Estimate` and `Invoice` own polymorphic `Article` lines and connect to clients, companies, tickets and bills. The estimate stores `invoice_id`; invoices can connect to multiple tickets through `ticket_invoice`.
- `Bill` is polymorphic to billable finance documents. `BCommand` and `BLivraison` have their own articles and provider/company/client relationships.
- UUIDs are unique on many externally routed models. `GetModelByUuid` sets the route key to `uuid`; `UuidGenerator` fills UUIDs when the table has that column.
- The tickets table and several core finance/client tables have soft-delete columns. `Ticket` uses soft deletes; `Invoice` imports `SoftDeletes` but has the trait commented out.
- The migrations use foreign keys in many core tables and pivots, but this is not uniform. For example, `estimates.invoice_id` has no `constrained()` declaration, and some provider/client relationships added later are nullable without a declared foreign key. There are useful unique constraints for document numbers and client codes.
- Early finance migrations define monetary fields as unsigned big integers; later migrations convert article quantities/prices and document totals to `float`. The schema after the later migrations therefore does not preserve exact decimal money semantics.

## 7. Main Business Workflows

**Ticket lifecycle (confirmed, broad flow):** authenticated staff create a ticket with a client, article, description and required image. A default “not treated” status history is recorded. Staff diagnose and repair tickets, update status/repairability, add reports and media, and can complete delivery. Ticket states are stored as integer constants and history is maintained through a pivot.

**Estimate/invoice workflow (confirmed in routes/models/controllers):** an estimate can be created generally or from a ticket; estimates have article lines, client/company and ticket links, and a route exists to start invoice creation from an estimate. Invoice creation accepts a client, company, ticket links, dates, payment mode, conditions and articles; it calculates a 20% tax and creates history. An estimate can be associated with the new invoice and marked invoiced. Invoice mail and PDF paths exist.

**Payments and delivery documents (confirmed):** bills are created against finance documents; the app also manages purchase orders and delivery slips. Reports aggregate invoice totals and bills. The full business rules for partial payments, invoice numbering policy, approval state transitions and accounting treatment were not established by source alone.

**State consistency caveat:** invoice creation/update/deletion touches multiple records and relationships. The invoice store path does not wrap these operations in a database transaction, so failure after the header save can leave a partially assembled invoice. See High/Medium findings.

## 8. Critical Findings

**None confirmed at Critical severity.** The exposed developer routes below are High severity and could become critical in a deployment where they are reachable by untrusted users, particularly if the production app allows public access to `/dev`.

## 9. High-Priority Findings

### H1. Unauthenticated developer routes expose privileged operations

**Severity:** High  
**Confidence:** Confirmed  
**Location:** `app/Providers/RouteServiceProvider.php:97-103`; `routes/developper/routes.php:7-30`; `app/Http/Controllers/Developper/DevController.php:13-21, 34-59, 62-116`

**Evidence:** `/dev` is registered with only `web` middleware. GET routes invoke migrations, database seeding (including caller-supplied seeder class), maintenance mode on/off, storage link/unlink, cache changes, vendor publishing, `composer dump-autoload` and `composer update`. These operations are callable without `auth` or a role check. Several are state-changing GET actions. `composerUpdate()` executes `shell_exec('composer update')`.

**Impact:** an unauthenticated visitor could take the app offline, alter data through seeding/migrations, change runtime dependencies or modify filesystem links if the route is reachable. This is especially severe on a public production deployment.

**Recommendation:** remove these routes from deployed web routing or gate them behind authenticated, explicitly authorized, non-GET operational commands; keep destructive actions out of public request paths.

### H2. Public API exposes the complete client list

**Severity:** High  
**Confidence:** Confirmed  
**Location:** `routes/api.php:22-24`; `app/Http/Controllers/API/V1/Application/ClientController.php:11-19`; `app/Http/Resources/Application/Client/ClientResource.php:17-26`

**Evidence:** `GET /api/clients` has no authentication middleware and serializes `Client::all()`. The resource includes client business name, ICE identifier and logo. Only `/api/user` is explicitly Sanctum-protected in this route file.

**Impact:** anyone able to reach the API can enumerate client identities and business registration identifiers. The data can be harvested at scale because the controller loads all clients at once.

**Recommendation:** confirm whether anonymous access is intended; if not, require Sanctum or another explicit authorization rule and paginate/filter the response.

### H3. User-provided text is emitted as raw HTML

**Severity:** High  
**Confidence:** Confirmed  
**Location:** `app/Http/Requests/Application/Ticket/TicketFormRequest.php:26-34`; `app/Http/Requests/Application/Report/ReportFormRequest.php:26-34`; `app/Http/Requests/Commercial/Invoice/InvoiceFormRequest.php:43-50`; `resources/views/theme/pages/Ticket/__diagnostic/detail.blade.php:81`; `resources/views/theme/pages/Ticket/__pdf/Report/index.blade.php:207,224`; `resources/views/theme/invoices_template/template1/index.blade.php:281,284,328`

**Evidence:** ticket descriptions and report content are accepted as plain strings, and invoice article designation/description and general conditions are accepted as strings. Those values are rendered with Blade raw output (`{!! !!}`) in staff pages and PDF templates, with no sanitizer visible in the write path.

**Impact:** stored HTML/script can execute in staff browsers when affected records are viewed. Raw content also reaches PDF rendering. The reachable audience depends on which staff roles can create and view these records.

**Recommendation:** use escaped output for plain text; if rich text is a product requirement, sanitize to a strict HTML allowlist at the trust boundary and cover the stored-content rendering paths.

### H4. Financial values are converted to floating-point database columns

**Severity:** High  
**Confidence:** Confirmed (migration source); deployed schema state not inspected  
**Location:** `database/migrations/2025_05_06_180243_change_colums_types_in_articles_table.php:14-26`; `database/migrations/2025_05_07_145603_change_colums_types_in_others_table.php:14-34`; `database/migrations/2025_05_07_151020_change_colums_types_in_others2_table.php:14-20`; `app/Services/Commercial/Taxes/TVACalulator.php:7-15`; `app/Services/Commercial/Remise/RemiseCalculator.php:7-15`

**Evidence:** these migrations change quantities, unit prices, line totals, invoice/estimate totals, delivery-slip totals and credit-note totals to SQL `float`. Calculators multiply unrounded PHP numeric values for tax and discounts. Invoice form rules allow numeric unit prices with many digits.

**Impact:** binary floating-point representation and repeated calculations can introduce rounding discrepancies between line totals, tax, document totals, stored values and reports. Current migrations establish the risk; actual affected records depend on which migrations were applied to each database.

**Recommendation:** do not silently recalculate or rewrite historical invoices, estimates, credit notes, payments, article prices, taxes or totals. First establish production column types, stored-value ranges/precision, app conversion rules and reconciliation requirements using read-only checks. Any schema change must be a new forward migration; deployed migrations are historical records and must not be edited.

**Production Data Risk:** High.

**Existing Data Impact:** existing amounts may already have been converted to floats and may not equal an intended rounded currency value. This audit did not inspect production schema or records, so impact on actual financial documents is unknown. **REQUIRES READ-ONLY PRODUCTION DATA VERIFICATION.**

**Safe Migration Strategy:** agree currency/quantity precision with the business owner; take and verify backups; rehearse on a production-like copy; inventory actual column types and compare stored header totals with line/tax values without updating records. If conversion is approved, add new nullable fixed-precision columns in a forward migration, deploy code that can read old and new representations and dual-write new transactions, then backfill in idempotent batches only after reconciling each value against its preserved stored value. Do not derive or replace historical totals from current line calculations. Switch reads only after validation and retain old columns through a rollback window. Check locking, migration duration and app availability before rollout.

**Rollback Considerations:** retain the original columns and old-code compatibility until the new path is verified. Roll back application reads/writes while both representations remain available; do not drop columns or run a down migration that coerces new values back to float. Any historical correction requires explicit approval and a separately auditable reconciliation plan.

## 10. Medium-Priority Findings

### M1. Public document PDFs use UUIDs as bearer access

**Severity:** Medium  
**Confidence:** Confirmed  
**Location:** `routes/web.php:25-45`; `app/Http/Controllers/Web/PDFPublicController.php:16-98`

**Evidence:** invoice, credit note, estimate, purchase-order and delivery-slip PDFs are routed outside the authenticated groups. The controller loads the document and streams a PDF without checking user authorization or validating a signature/expiry. UUIDs are the model route key.

**Impact:** possession of a document URL grants access to commercial/customer data. UUIDs reduce accidental enumeration but do not revoke access or protect URLs leaked through messages, logs or browser history.

**Recommendation:** confirm these links are intentionally public bearer links; otherwise require auth or signed, expiring links and make the access policy explicit.

### M2. Invoice operations are not atomic

**Severity:** Medium  
**Confidence:** Confirmed  
**Location:** `app/Http/Controllers/Commercial/Invoice/InvoiceController.php:103-183,204-268,273-299`

**Evidence:** creation saves the invoice, optionally links/updates an estimate, creates line rows, attaches tickets and records history through separate writes without a surrounding transaction. Update similarly writes the invoice before lines/pivots/history; delete removes child data and header separately. Ticket creation does use `DB::transaction`, showing a local precedent.

**Impact:** exceptions or transient database failures can leave document totals, lines, estimate flags, ticket pivots and history out of sync.

**Recommendation:** after defining the invariants, wrap each multi-record financial operation in a transaction and add rollback-focused tests.

**Production Data Risk:** Medium.

**Existing Data Impact:** a code-only transaction change does not require rewriting existing rows. It changes all-or-nothing behavior for future invoice operations; existing partial or inconsistent documents must be identified and handled separately, not silently repaired by deployment.

**Safe Migration Strategy:** no schema migration is required. Document current workflow/invariants, add tests using an isolated database, then deploy the transaction boundary as an application change. Do not run tests or experiments against production data.

**Rollback Considerations:** revert the application change if operational behavior regresses; the transaction wrapper introduces no schema state to reverse. Preserve already-created records and reconcile exceptions separately.

### M3. Public upload/batch routes point to a broken legacy importer

**Severity:** Medium  
**Confidence:** Strong Evidence  
**Location:** `routes/web.php:47-49`; `app/Http/Controllers/Importer/ImporterController.php:16-68`; `app/Http/Controllers/Importer/CSVImporterController.php:17-49`

**Evidence:** `/upload` and `/batch` are public and target `ImporterController`. Its upload path uses `file($request->file)` on the uploaded-file request value without validation, then writes chunk files into a fixed storage directory. That class has no `batch()` method, although `/batch` routes to it. A different `CSVImporterController` has a `batch()` method, and a separate authenticated Livewire importer validates a CSV/TXT extension and dispatches queue batches. The legacy `store()` also unlinks `$file` after the loop, which is undefined when no matching files exist.

**Impact:** the public upload is likely to fail for normal multipart uploads, accepts no size/type bounds, and its batch endpoint will fail at dispatch because the action is absent. Multiple import paths make it unclear which importer is supported.

**Recommendation:** verify whether these legacy public routes are still used; inspect their runtime contract and remove or route them through the validated, authorized importer if needed.

**Production Data Risk:** Medium.

**Existing Data Impact:** changing the import path can affect incoming imports and queued batches, but should not modify existing records by itself. Do not delete temporary chunks, batches or imported records as part of route cleanup.

**Safe Migration Strategy:** no schema change is indicated. Identify active callers and outstanding queue batches read-only, document the accepted CSV format, then deploy a compatible route/authorization change. Validate parsing against fixtures and an isolated database.

**Rollback Considerations:** retain compatible behavior during a controlled cutover if the path is still required; preserve queued work and temporary files until their ownership/status is established. Restore the old application version if the import contract fails, without replaying jobs automatically.

### M4. Client document links are not checked for cross-record consistency

**Severity:** Medium  
**Confidence:** Strong Evidence  
**Location:** `app/Http/Requests/Commercial/Invoice/InvoiceFormRequest.php:28-54`; `app/Http/Controllers/Commercial/Invoice/InvoiceController.php:149-174`; `app/Http/Requests/Commercial/Invoice/InvoiceUpdateFormRequest.php:46-74`

**Evidence:** invoice requests validate client/company/ticket IDs as integers and ticket lists as arrays, but do not require those IDs to exist or validate that selected tickets belong to the selected client. The controller assigns those values and attaches submitted ticket IDs directly. No tenant boundary is evident in the repository, so cross-client exposure is not asserted.

**Impact:** malformed or manipulated requests can create invoices whose client, primary ticket and attached tickets refer to inconsistent records. The exact business impact depends on whether staff should be allowed to associate tickets across clients.

**Recommendation:** define and enforce the intended client/ticket/company ownership rules in validation and relationship-scoped queries.

**Production Data Risk:** Medium.

**Existing Data Impact:** application validation can block future inconsistent submissions but does not repair existing links. Existing documents may contain cross-client links that are valid by business policy or legacy behavior.

**Safe Migration Strategy:** confirm the business rule and inventory existing relationships read-only before considering a database constraint. Begin with request validation and compatibility tests. Do not add foreign keys, non-null constraints or ownership constraints until legacy rows have been checked and an approved cleanup plan exists. Any eventual constraint must be introduced in a new forward migration after validation and staged deployment.

**Rollback Considerations:** application validation can be rolled back independently. If a new constraint is later added, rollback must account for writes made while it was active; retain a compatible code path and do not delete/reassign legacy links automatically.

### M5. Client code generation is race-prone

**Severity:** Medium  
**Confidence:** Confirmed  
**Location:** `app/Models/Client.php:123-134`; `database/migrations/2021_11_27_120504_create_clients_table.php:16-20`

**Evidence:** the creating hook generates a sequential code by querying `max(id) + 1`; the database also enforces a unique code.

**Impact:** concurrent client creations can calculate the same next code. One insert may then fail on the unique constraint, or retry behavior may produce inconsistent user experience.

**Recommendation:** use a database-backed sequence or retry-safe code allocation that preserves the required external format.

**Production Data Risk:** Low to Medium.

**Existing Data Impact:** a code-only allocation change should leave existing client IDs and codes untouched. The existing unique constraint means current duplicates may be impossible in the active schema, but production state was not inspected.

**Safe Migration Strategy:** no data backfill is required for a retry-safe application allocation approach. If a new sequence/table is proposed, create it in a new forward migration, initialize it from the verified maximum existing code, and deploy compatible allocation logic without changing existing codes. Verify compatibility with imports and integrations first.

**Rollback Considerations:** keep the allocator's high-water mark and uniqueness guarantees; reverting code must not reset the sequence or reuse an issued code. Do not renumber existing codes during rollback.

## 11. Low-Priority Findings

### L1. Invoice date accessors reference absent or mismatched columns

**Severity:** Low  
**Confidence:** Confirmed  
**Location:** `app/Models/Finance/Invoice.php:154-162`; `database/migrations/2022_01_22_212716_create_invoices_table.php:33-35`

**Evidence:** `getIsPublishedAttribute()` dereferences `published_at`, which is not defined on the invoice migration; `getIsPassedAttribute()` dereferences `date_due`, while the invoice column is `due_date`. Neither field is cast/guarded against null in these accessors.

**Impact:** callers of these accessors can get undefined/null errors or incorrect due-date behavior. No active call site was confirmed in the audited paths.

**Recommendation:** search active callers before correcting/removing the accessors; add focused tests if they remain part of the UI contract.

### L2. Invoice soft-delete configuration disagrees with schema

**Severity:** Low  
**Confidence:** Confirmed  
**Location:** `app/Models/Finance/Invoice.php:15,25`; `database/migrations/2022_01_22_212716_create_invoices_table.php:59-60`

**Evidence:** invoice schema has `softDeletes()`, but the model's `SoftDeletes` import/use is commented out.

**Impact:** normal Eloquent queries will not automatically hide deleted-at rows, and `$invoice->delete()` will not act as a soft delete. Intent is unclear because the schema has a deleted-at column.

**Recommendation:** confirm retention/deletion behavior and align model behavior, migrations and UI semantics.

**Production Data Risk:** High if enabling `SoftDeletes` without review.

**Existing Data Impact:** rows with a populated `deleted_at` would become hidden from default Eloquent queries if the trait is enabled; current screens may presently include them. Production deleted-at values and expected retention behavior were not inspected. **REQUIRES READ-ONLY PRODUCTION DATA VERIFICATION.**

**Safe Migration Strategy:** do not change historical migrations. Inspect deleted-at counts and application/reporting expectations read-only; add regression tests for current behavior. Enabling the existing soft-delete column is code-only, but should happen only after confirming which rows should remain visible. No data rewrite is needed to evaluate it.

**Rollback Considerations:** reverting the trait restores prior query behavior while preserving `deleted_at` values. Do not force-delete rows or clear timestamps as part of rollback.

### L3. Legacy duplicates and stale comments remain in active source trees

**Severity:** Low  
**Confidence:** Strong Evidence  
**Location:** `app/Http/Controllers/Administration/Ticket/TicketController.php:65-110`; `app/Http/Controllers/Importer/ImporterController.php:45-94`; multiple `README.md`/refactor notes

**Evidence:** ticket controller retains multiple old listing methods with commented pagination; importer has duplicate `store`/`storeTow` implementations; README.md is not an architecture guide. This is suspicious duplication, not proof that code is unreachable.

**Impact:** maintenance changes can diverge across implementations and developers may follow outdated documentation.

**Recommendation:** establish actual route/call usage before retiring duplicates, and keep one concise current project overview.

## 12. Security Review

- **Authentication:** main staff and commercial route groups require `auth`; login checks the `active` flag and logout invalidates the session. Login throttling is configured in `RouteServiceProvider`. No conclusion about production session cookie/TLS settings was drawn from `.env.example` alone.
- **Authorization:** route-level `can(...)` checks and Spatie role middleware are used across many operations; controller-level `$this->authorize()` also appears. `AuthServiceProvider` has no explicit policy mappings and policy methods such as `InvoicePolicy::view()` are empty. Verify Laravel policy discovery and actual route behavior for all model policies; do not assume an empty method is safe. `Gate::before` grants all abilities to `SuperAdmin`.
- **High risks:** exposed `/dev`, public client API, and stored raw HTML findings H1-H3.
- **Document access:** public UUID document URLs act as bearer links (M1). Decide whether that is the intended sharing contract.
- **File uploads:** ticket image request checks file type and a 2 MB size limit. The separate public legacy importer lacks comparable validation. The authenticated Livewire importer requires CSV/TXT extension but has no explicit size or row-count bound in the shown rules.
- **SQL/mass assignment:** the inspected invoice path calculates totals server-side and uses Eloquent relationships; no SQL injection was confirmed in this sample. Several models expose broad fillable fields, so each write path still needs review. `Sale` uses `$guarded = []`; its importer feeds CSV header-keyed data directly to `Sale::create()` (`app/Jobs/Importer/CSV/CSVImporterJob.php:38-44`), making CSV headers capable of selecting any sale model columns. Whether this is exploitable depends on importer access and schema; verify permitted columns before production use.
- **Secrets:** a limited repository text scan for common key/private-key patterns found no matching committed credentials. This is not a full secrets audit and values were not printed.

## 13. Database Review

- Core entities use integer IDs and several use unique UUIDs for routing. Many client and finance relations have foreign keys; pivots have foreign keys and indexes.
- Constraints are inconsistent across tables and later-added relationships. For example, `estimates.invoice_id` is nullable but unconstrained; nullable provider relation additions should be reviewed against their model relationships. Do not infer all schema state from source without inspecting the target database.
- Document `full_number` columns use unique constraints, but visible code should be audited for race-safe number generation and scoped numbering rules before changing them.
- Money fields are initially integer and later migrated to floats (H4). This is the clearest schema-level integrity issue.
- Already-deployed migrations are historical records. Do not edit them to repair production; any approved schema evolution must use a new forward migration and be rehearsed with verified data assumptions and backups.
- `Ticket` migration uses soft deletes while `Invoice` model/schema mismatch is noted in L2. Other models should be checked for the same schema/model alignment.
- The client code generation uses `max(id)+1` (M5), rather than a database sequence.
- No live or production database was inspected; actual migration status, existing duplicate/orphan data and current schema cannot be confirmed by this report.

## 14. Laravel 13 Review

- Composer pins Laravel `^13.0` and PHP `^8.4`; `php artisan about` reported Laravel 13.34.0 and PHP 8.4.25 in the local environment.
- The app retains explicit `App\Http\Kernel`, `App\Console\Kernel` and classic application bindings in `bootstrap/app.php`. This is an older but functioning structure in the installed app; there is no demonstrated need to migrate it solely for style.
- Route groups use middleware, implicit model binding, Form Requests, policies/gates, Eloquent relations, queues, and framework facades. Avoid broad rewrites; prioritize the security and data consistency findings.
- Custom public operational routes bypass the intended authenticated route groups and need immediate deployment review.
- Controller responsibilities are large in places, especially commercial document controllers, but splitting them is an improvement only after invariants and tests are established.

## 15. PHP 8.4 Review

- Composer confirms PHP `^8.4`; the local CLI reported 8.4.25.
- Strict typing is present in a subset of code (for example a few newer controllers/migrations), while most legacy code is weakly typed. No PHP 8.4 syntax compatibility failure was found in the performed checks.
- Financial arithmetic uses ordinary PHP numeric multiplication and percentages, with float-backed persistence (H4). This is the material numeric risk.
- Several model accessors can dereference missing/null date fields (L1); more broad nullable/static analysis was not available because PHPStan/Larastan is not configured.
- No type checker is declared in the audited Composer scripts/dependencies.

## 16. Livewire 4 Review

- Composer pins Livewire `^4.4`. Components are class-based under `app/Http/Livewire`; the invoice/estimate forms use separate `Info`, `Tickets` and article-edit components. A CSV importer uses `WithFileUploads` and queue batches.
- The invoice ticket selector accepts an event-provided client ID and queries related tickets. Its parent page has an invoice-create permission, but the component action itself does not visibly re-check permission or validate the client ID. Confirm component reachability and authorization at the Livewire endpoint; IDs and event payloads are client-controlled.
- State and validation are not uniform: the importer validates file type, whereas several small selectors rely on page-level access and event values. Treat this as an authorization boundary to verify, rather than a confirmed bypass from the inspected snippets.
- Component render methods are relatively lean in the inspected invoice selector; the selector query only runs after `readyToLoad`. A comprehensive N+1 sweep of all components/views is not conclusive from static inspection alone.
- Prior `emit`-to-`dispatch` migration is reflected in the inspected `Tickets` component (`dispatch('select2')`). No active `emit()` call was identified in this targeted scan.

## 17. mPDF / Reporting Review

- `app/Support/PdfGenerator.php` creates mPDF with UTF-8, A4 and a storage temp directory, renders a Blade view to HTML and calls `WriteHTML()` synchronously. `PdfDocument` returns an inline PDF response after stripping CR/LF/quote characters from the filename.
- Public PDFs cover invoices, credit notes, estimates, purchase orders and delivery slips; staff-authenticated report generation also exists. PDFs are rendered on request, so large line-item/embedded-image documents can consume request memory/time.
- The shared `CompanyLogoDataUri` resolves logos from the public storage disk and avoids raw missing-path reads; this addresses the past missing-logo failure mode. Recheck if logo disks/paths or PDF outputs are changed.
- Unescaped invoice/article/report content reaches PDF HTML (H3). This is both a browser stored-XSS issue in staff views and an HTML trust-boundary concern for document generation.
- Public document links have no expiry/signature in the route shown (M1). Confirm intentional sharing semantics.
- No PDF-specific automated test is present; encoding, missing-image behavior, page breaks, non-Latin text and oversized documents are untested.

## 18. Performance Review

- Some invoice/ticket listings eager-load relationships and use pagination. Other ticket lists call `get()` and have their pagination commented out (`TicketController.php:47-56,65-75`), so large installations may load every matching ticket.
- Invoice filter page loads all companies and clients in addition to the paginated documents (`InvoiceController.php:69-71`); this may grow with customer count.
- Public client API materializes `Client::all()` (H2), increasing memory and response size as data grows.
- PDF generation is synchronous; large documents and media can raise response time/memory. The mPDF wrapper uses a dedicated temporary directory but cleanup/size monitoring was not established.
- The console scheduler is empty. No specific scheduled task requirement was inferred.
- These are confirmed query patterns; production impact depends on dataset size and traffic. Prioritize pagination on unbounded endpoints before speculative caching.

## 19. Code Quality & Maintainability

- Positive: code is separated into domain folders, custom packages are PSR-4 loaded, Form Requests exist for many write paths, and many routes use permission checks. Recent functions use explicit return types in places.
- Concern: domain behavior is distributed across controllers, model accessors/events, traits and Livewire forms; invoice math and persistence are controller-owned and repeated across finance areas. Use this as a change-risk signal, not a request for a wholesale service/repository rewrite.
- Naming has historic typos and inconsistent spelling (`Developper`, `TVACalulator`, `caluculateTva`, `storeTow`); these are maintenance concerns, not necessarily runtime defects.
- Documentation is fragmented among task/refactor notes; there is no maintained architecture map or clear importer/PDF operational contract.
- Several routes/controllers have multiple versions or placeholder methods. Confirm call sites before labeling code dead.

## 20. Test Coverage & Missing Tests

**Automated check:** `php artisan test --compact` — 1 failed, 1 passed (2 assertions); PHPUnit emitted a warning that the XML configuration uses a deprecated schema.

- `Tests\Feature\ExampleTest` expects `GET /` to return 200; actual route redirects to `/app`, producing 302. The current route contract and test expectation disagree (`tests/Feature/ExampleTest.php:14-19`, `routes/web.php:23`).
- `Tests\Unit\ExampleTest` only asserts `true`.
- No workflow tests were found for ticket status changes, estimate approval/conversion, invoice line/tax/discount calculations, invoice deletion/rollback, payment reconciliation, role boundaries, public API data exposure, CSV row validation or PDF rendering.
- Highest-value additions: route access tests for `/dev` and API clients; stored-XSS rendering tests; financial arithmetic/rounding tests using representative fractional inputs; invoice atomicity and client-ticket ownership tests; ticket lifecycle authorization tests; PDF output smoke tests with missing/valid logos.
- There are no meaningful coverage claims to make from the current example-only suite.

## 21. Dead / Suspicious / Legacy Code

- `/dev` operations appear developer-oriented but are registered in the main route service provider for all environments (H1).
- The root web routes use `ImporterController`, while a separate controller and a Livewire component implement different CSV flows (M3). The API V2 client controller exists but the inspected `routes/api.php` only imports V1; confirm whether V2 is intentionally unused.
- `TicketController::old()` and `oldTow()` coexist with the current list path; multiple pagination branches are commented.
- The invoice model has a `SoftDeletes` mismatch and date accessors with likely obsolete column names (L1/L2).
- Code search alone does not prove unused migrations, views, package classes or helper functions are dead; no deletion is recommended from this audit.

## 22. Architectural Strengths

- Main admin/commercial route groups use authentication and several sensitive routes add explicit `can` or role middleware.
- Form Requests are used for many user-facing writes, and ticket creation wraps related record changes in a transaction.
- Core ticket/client/document relationships are modeled in Eloquent, with pivot tables for many-to-many ticket links and polymorphic articles/history/media.
- UUID route keys reduce exposure of sequential internal IDs on many screens and document links.
- Reusable PDF generation is isolated behind app-owned facade/generator/document classes, and company logo resolution is shared.
- Queue batch support exists for CSV imports; backup, media, permissions, settings and Excel integrations use maintained Laravel ecosystem packages.

## 23. Technical Debt

### Confirmed technical debt

- Public developer operations registered without authentication (H1).
- Floating-point database columns/calculations for currency (H4).
- Raw HTML rendering of accepted user text (H3).
- Duplicate/legacy importer paths and inconsistent route targets (M3).
- Invoice multi-record writes without transaction (M2).
- Model/schema inconsistencies and sequential client-code allocation (L1, L2, M5).
- Very small test suite and failing root-route assertion.

### Optional improvements

- Add a maintained architecture/workflow overview.
- Decide whether controllers should be split after behavior is covered by tests.
- Add static analysis only if the team wants a defined type-safety baseline; it is not installed currently.
- Review unbounded lists and synchronous PDF paths against real data volumes before adding caching or queues.

## 24. Recommended Next Improvements

### Immediate

1. Confirm all deployment environments and remove or strictly protect `/dev` operational routes.
2. Decide whether `/api/clients` is public by design; require authorization if not.
3. Stop raw rendering of user-controlled text or sanitize rich text before it reaches staff views and PDFs.
4. Freeze the financial precision policy. Before proposing conversion, verify actual production schema and financial values read-only; preserve historical document totals unless an explicitly approved reconciliation says otherwise. Any approved schema change must use a new forward migration and the H4 safeguards.
5. Decide whether document PDFs are public bearer links; add explicit expiry/authentication if the answer is no.

### Short Term

1. Add tests for the five immediate security/data contracts above and fix the stale root route test to match intended behavior.
2. Make invoice create/update/delete atomic and validate client/ticket/company relationships, after confirming the business rules. These application changes should leave existing documents untouched; review M2 and M4 before deployment.
3. Consolidate the CSV import entry point, validate upload size/type/headers/row count, and authorize batch-status access. Check outstanding jobs and preserve import compatibility during rollout (M3).
4. Replace client `max(id)+1` code generation with retry-safe allocation while preserving all existing client codes (M5).
5. Review policy discovery, empty policy methods, Livewire event payloads and component-level authorization.

### Medium Term

1. Align model casts, deletion behavior and accessors across core ticket/finance models only after checking effects on existing production rows. Treat deployed migrations as immutable; use a new forward migration for any approved schema change. See L2 and H4 safeguards.
2. Add pagination to unbounded ticket/API reads and measure invoice page reference-data loading.
3. Add PDF regression checks for rendering, encoding, missing logos, user text and larger documents.
4. Document ticket and finance state transitions as the business owner confirms them.

### Optional

1. Consolidate stale/refactor markdown notes into a current contributor and architecture guide.
2. Adopt a static-analysis tool if maintenance benefits justify ongoing configuration.
3. Refactor large controllers only where tests show duplicated or fragile business rules.

## 25. Files Requiring Further Human Review

- `routes/developper/routes.php` and deployment web-server rules: confirm whether `/dev` is internet-reachable in each environment.
- `routes/api.php`, `ClientResource.php`: confirm whether public client enumeration is a product requirement and whether ICE identifiers are intended to be disclosed.
- Ticket/report rich-text product requirements: decide plain text versus approved HTML and expected user roles.
- Public PDF routes and generated email links: confirm whether UUID URLs are intended to be long-lived bearer links.
- Financial policy: currency, allowed fractional quantity, tax rounding point, discount order and migration status/data verification.
- Importer ownership: choose among legacy route importer, controller importer and Livewire importer; confirm expected CSV schema and access rules.
- `app/Policies/*` and route authorization: verify policy auto-discovery and permissions in a configured role database.
- Production schema and records: no production database was inspected. Verify migration status, float values, deleted-at rows, uniqueness assumptions and cross-record relationships through explicitly read-only queries before proposing persisted-data changes.

## 26. Open Questions

1. Is the application ever deployed with `/dev` reachable by an external user, or is it blocked at the proxy/network layer?
2. Should anonymous users be able to retrieve the whole client list and ICE identifiers from the API?
3. Are invoice/estimate PDFs intentionally accessible to anyone who has the UUID URL, and should those links expire?
4. Does the app support fractional item quantities or more than two decimal places for amounts?
5. What is the intended rule for associating tickets with clients and invoices, especially return tickets and converted estimates?
6. Which CSV importer is the supported production path, and who is allowed to run it?
7. Which databases/environments have applied the 2025 float migrations, what exact representations are currently stored, and what rounding/reconciliation requirements apply to existing documents? **REQUIRES READ-ONLY PRODUCTION DATA VERIFICATION.**

## Audit Commands and Limits

- `php artisan about --only=environment` — succeeded locally; reported Laravel 13.34.0, PHP 8.4.25, local environment, debug enabled, Africa/Casablanca timezone and French locale. These are local runtime values, not production configuration.
- `php artisan route:list --except-vendor --json` — succeeded; 199 application routes were reported.
- `composer validate --no-check-publish` — passed.
- `php artisan test --compact` — 1 failure and 1 pass; details in section 20.
- Dependency installs, migrations, seeders, destructive Artisan commands, write-mode formatters/builds and production data inspection were not run. No production database was queried or modified.
- Secret scan was a limited text-pattern search; it cannot certify that no credentials exist.
- This audit is source-based. It does not establish production reachability, database migration state, dataset quality, or business intent where noted.
