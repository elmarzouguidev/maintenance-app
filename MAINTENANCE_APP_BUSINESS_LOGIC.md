# MaintenanceApp — Business Logic Discovery

**Purpose:** describe the business behavior visible in the repository as implemented. This is a static source review, not a code change proposal.

**Production safety:** no production database was queried, and no migration, seeder, or data operation was run. No customer data is included. Existing migration files are treated as historical evidence only.

**Evidence labels:** **CONFIRMED** means directly implemented or shown in a view/route; **INFERENCE** means suggested by code but not guaranteed; **UNKNOWN** means repository evidence cannot settle the business intent. File references point to the implementation to verify the claim.

## 1. Product Overview

MaintenanceApp is an internal service desk for receiving customer devices, assigning technical work, recording diagnosis and repair reports, and managing the related commercial paperwork. Staff register a client and a ticket, technicians document the diagnosis and repair, and commercial users can prepare estimates and invoices associated with one or more tickets. Reception can track delivery, while finance features cover invoice payment records, credit notes, suppliers, purchase orders, and delivery slips. The application also provides user access management, customer communication, warranty records, dashboards, reports, and generated PDFs. The repository shows a staff-facing workflow; a full customer self-service portal is not confirmed.

**Evidence:** `routes/app-routes/routes.php`; `routes/app-routes/commercial_routes.php`; `app/Models/Ticket.php`; `app/Models/Finance/`.

## 2. Business Domain Map

| Domain | Purpose in code | Main implementation |
|---|---|---|
| Customer intake | Maintain customer records and contacts | `Client`, `Category`, `Telephone`, `Email`, `ClientController` |
| Service operations | Register, diagnose, repair, return, and deliver devices | `Ticket`, `Report`, `Delivery`, `Warranty`; ticket, diagnosis, repair controllers |
| Commercial documents | Quote, invoice, credit note, payment entry | `Estimate`, `Invoice`, `InvoiceAvoir`, `Bill`, `Article` |
| Purchasing | Supplier records, purchase orders and delivery slips | `Provider`, `BCommand`, `BLivraison` |
| Company identity | Company details, document prefixes and starting numbers | `Company` |
| People/access | Staff accounts, roles and permissions | `User`, Spatie roles/permissions, policies and route gates |
| Operational reporting | Ticket reports, dashboards, exports and PDFs | `RapportController`, dashboard/statistics controllers, PDF controllers/templates |
| Supporting operations | Media, email, import/export, backup, settings | Media Library, mail classes, importer jobs, backup job, settings |

## 3. Main Entities

- **Client:** customer/business identity, category, company association, contact details, service tickets and commercial documents. Phones/emails may also be separate polymorphic records. (`app/Models/Client.php`)
- **Ticket:** the service case and device intake record. Links customer, technician, status history, reports, media, delivery, warranty, estimates, and invoices. (`app/Models/Ticket.php`)
- **Status / ticket_status:** status catalog and a many-to-many event/history trail. Current status is also stored on the ticket itself. (`app/Models/Utilities/Status.php`, `Ticket.php`)
- **Report:** a typed report attached to a ticket, with author and close flag. The implementation uses types `diagnostique` and `reparation`. (`app/Models/Utilities/Report.php`)
- **Estimate:** commercial quote, lines, customer/company, optional direct ticket and ticket pivot links, optional invoice link, status/send flags. (`app/Models/Finance/Estimate.php`)
- **Invoice:** billable customer document with article lines, client/company, ticket links and bills/payment entries. (`app/Models/Finance/Invoice.php`)
- **InvoiceAvoir:** credit-note document related to an invoice, client/company, article lines and a bill relation. (`app/Models/Finance/InvoiceAvoir.php`)
- **Bill:** polymorphic payment/bill entry; controller paths primarily create it for invoices or credit notes. It stores amount snapshots, date, mode, reference, notes and company. (`app/Models/Finance/Bill.php`)
- **Article:** polymorphic document line. Despite its name, these line records are not necessarily catalog products. (`app/Models/Finance/Article.php`)
- **Provider / BCommand / BLivraison:** supplier contact, purchase order and delivery-slip records. (`app/Models/Finance/Provider.php`, `BCommand.php`, `BLivraison.php`)
- **Delivery / Warranty:** ticket delivery metadata and warranty dates/description/notification flags. (`app/Models/Utilities/Delivery.php`, `Warranty.php`)
- **Company:** business identity and per-document number settings. (`app/Models/Finance/Company.php`)

## 4. Client Management

**Confirmed behavior:** staff can create, edit, view, and delete clients through `ClientController` routes. Client creation assigns a code using a configured prefix and `max(id)+1`, padded to five digits. Client has a primary set of company/contact/address/registration/category/description fields, plus morph-many email and telephone collections and Media Library support. Client is linked to tickets, invoices, credit notes and delivery slips; its company/category relationships use `withDefault()`.

**Individual vs company:** the primary identity field is `entreprise`, accompanied by `contact`; the model does not define separate individual and corporate client subtypes. The intended distinction is **UNKNOWN**.

**Deletion/history:** the initial clients migration includes a soft-delete column, but the `Client` model does not use Laravel's `SoftDeletes` trait. The delete controller's exact behavior is source-controlled in `app/Http/Controllers/Administration/Client/ClientController.php`; repository relationships and DB foreign keys mean business consequences of deleting a customer with historical documents need owner confirmation. No customer records were inspected.

**Evidence:** `routes/app-routes/routes.php` client routes; `app/Models/Client.php`; `app/Http/Controllers/Administration/Client/ClientController.php`; `database/migrations/2021_11_27_120504_create_clients_table.php`.

## 5. Ticket Lifecycle

### Intake and record

The ticket create request requires an integer client, device/article name, description, and one PNG/JPG/JPEG image up to 2 MB. The controller associates the customer, adds the uploaded image to `tickets-images`, then saves the ticket inside a database transaction. A new non-return ticket receives a `NON_TRAITE` status-history row. (`TicketFormRequest`; `TicketController::store`)

The `Ticket` model has `code`, `code_retour`, `is_retour`, `retour_number`, device description/reference, `etat`, `status`, `user_id`, `client_id`, invoice/delivery flags and timestamps. Its active model hook assigns the main code from the current table maximum (or configured starting value for the first ticket). A separate `GenerateTicketCode` action creates a seven-character random code, but a repository search found no caller; it does not describe the active creation path found here. Return-ticket numbering is described under section 20. (`app/Models/Ticket.php`; `app/Actions/Application/Ticket/GenerateTicketCode.php`)

### Assignment and diagnosis

A technician opening an unassigned ticket can become its assigned technician. That visit moves the ticket to status 2 (diagnosis in progress), sets `started_at`, and appends status history. Diagnosis is a typed report associated with the ticket; saving/updating a diagnosis also updates the ticket's repairability (`etat`). (`DiagnostiqueController::diagnose`, `storeDiagnose`)

### Estimate/customer decision

When diagnosis is submitted with the “send report” action and repairability is true, the ticket moves to status 7 (awaiting estimate). If not repairable, it moves to status 4. Creating an estimate linked to a ticket moves it to status 8 (awaiting purchase order). Admin/SuperAdmin confirmation records status 10 (ready/to repair) for accepted estimates, or status 5 (estimate declined/return) for declined estimates. The code updates the estimate response field too. (`DiagnostiqueController`; `EstimateController::store`; `sendConfirm`)

### Repair, delivery, invoice

Opening repair can move a ticket to status 3 (repair in progress). Completing the repair report moves it to status 11 (ready for delivery), sets `can_invoiced=true`, sets `finished_at`, and closes the repair report. Reception delivery confirmation creates a `Delivery`, sets status 13 (delivered), and for repairable tickets creates a warranty lasting three months from delivery confirmation. One invoiceable view includes status 11 and 13; `invoiceable2` selects delivered status 13 only. Status 12 (“ready to invoice”) is defined but no controller assignment was found. (`DashboardController::confirmLivrable`, `invoiceable`, `invoiceable2`)

Return tickets inherit selected prior context: parent estimate/invoice pivot links, assigned technician, status-history entries, current status and repairability. This behavior can carry historical trail forward, but its business meaning is not documented in code. (`TicketController::store`)

### Lifecycle limits

There is no single enforced transition graph. Current status is an integer field and code paths append status-history records separately. Some screens/actions rely on current status and other flags (`etat`, `can_invoiced`, report closure). Direct edits and multiple code paths can therefore yield combinations not represented by the conceptual lifecycle.

**Evidence:** `TicketController`; `DiagnostiqueController`; `ReparationController`; `DashboardController`; `TicketPolicy`; `app/Constants/Status.php`; `app/Constants/Etat.php`.

## 6. Diagnosis Workflow

Diagnosis is an editable `Report` of type `diagnostique`, not a separate diagnosis model. The diagnosis screen shows ticket and technician context. Technicians can start work by opening an unassigned ticket, and the policy restricts report storage to the assigned technician or an eligible SuperTechnicien with an assigned user. Diagnosis saving updates `etat`; the report is closed when submitted onward as repairable or unrepairable. Admin roles can access the diagnosis page and confirm estimates, but `canStoreDiagnose` only allows technician/SuperTechnicien roles to save through this action.

Multiple diagnoses are not an intended visible pattern: `diagnoseReports()` is a `hasOne` filtered by type and storage uses `updateOrCreate` keyed on `ticket_id` + `type`. The database constraint guaranteeing one row per ticket/type was not found, so uniqueness is **PARTIALLY ENFORCED** by this controller path.

Approval is split: technician records diagnosis and repairability; a later admin confirmation action decides accepted/rejected estimate response. Report sharing/email behavior exists through `sendReport`; whether it sends to a customer or internal address depends on controller/mail code and is not inferred here.

**Evidence:** `Ticket::diagnoseReports`; `Report`; `DiagnostiqueController`; `TicketPolicy`; `routes/app-routes/routes.php`.

## 7. Repair Workflow

Repair is a second `Report` type, `reparation`, on the same ticket. It is therefore a separate business phase stored as another report, not a separate repair entity. A technician assigned to the ticket, or an authorized SuperTechnicien with a ticket assignment, opens repair. That action sets status 3 if necessary. The technician saves report content; explicitly marking `reparation_done` closes the report, sets status 11, enables invoicing, and records finish time.

The repair form displays diagnosis content and repair report content; ticket attachments and the report PDF include media. “Unrepairable” handling occurs in diagnosis status/repairability; no separate repair-failure workflow was confirmed. The exact meaning of `can_make_report` and `livrable` flags is not consistently enforced throughout the inspected lifecycle.

**Evidence:** `ReparationController`; `resources/views/theme/pages/Reparation/__single/section_a.blade.php`, `section_b.blade.php`; `resources/views/theme/pages/Ticket/__pdf/Report/index.blade.php`; `TicketPolicy`.

## 8. Technician Workflow

Roles used directly in business code include `Technicien` and `SuperTechnicien`. Technicians see tickets through their `tickets()` relation (the `user_id` one-to-many relation), can diagnose assigned cases, and can write repair reports for assigned cases. `SuperTechnicien` views broader assigned-ticket queues and can act on assigned tickets under policy checks. SuperAdmin can reassign a ticket; the route/controller requires a reason and validates that the target user has the Technicien role.

A separate `ticket_user` many-to-many relationship exists, but the main diagnosis/repair ownership paths use `tickets.user_id`. Whether the pivot represents additional technicians, history, or an older workflow is **UNKNOWN**.

**Evidence:** `User::tickets`; `Ticket::users` and `Ticket::technicien`; `DiagnostiqueController::index`; `TicketPolicy::canDiagnose/canStoreDiagnose/canRepear/canRepearStore/canReassign`; `TicketController::reassign`.

## 9. Estimate Lifecycle

An estimate may be created standalone or from a ticket. `createFromTicket` presents a ticket-specific form; estimate storage accepts one `ticket` field and/or a list of `tickets`. The estimate stores customer/company, dates, payment mode, notes/terms, totals and article lines. For one linked ticket or each pivot-linked ticket, creation moves ticket status to 8 (awaiting purchase order). The estimate has an independent `status` response value and `is_invoiced`/`invoice_id` fields.

Line calculation at creation is: `gross line = prix_unitaire × quantity`; when `remise > 0`, `net line = gross line − gross line × remise / 100`; otherwise net equals gross. Estimate HT is the sum of net lines. VAT/TTC are calculated through the shared calculator (see section 21). On update, the inspected controller adds the newly submitted lines' amount to existing `price_ht` and creates lines; it does not visibly replace the complete prior set in that operation. An item-delete route separately removes one article. This is implementation behavior; business intent needs owner confirmation.

A response endpoint accepts estimate response codes. Accepted sets estimate status 1 and linked ticket status 10; declined sets status 2 and linked ticket status 5. The constants also define pending 0, not sent 3 and sent 4, but use of every value as a persisted estimate state is not confirmed. Email send action exists. An estimate-to-invoice route opens a conversion form; conversion persistence is not established merely by that route and needs careful follow-through when documenting any later behavior.

**Workflow:** draft/create → optional send → response (accepted/rejected) → optional invoice creation. A strict one-time-conversion guarantee is **not confirmed**.

**Evidence:** `EstimateController`; `EstimateFormRequest`; `EstimateUpdateFormRequest`; `DiagnostiqueController::sendConfirm`; `Estimate`; `Response`.

## 10. Invoice Lifecycle

Invoices can be created directly through the invoice form, including selecting one or more tickets. The invoice Livewire UI narrows selectable tickets by repairable state, ready-for-delivery status and `can_invoiced`; another ticket-selection component allows repairable tickets but has some status conditions commented out. Invoice fields include customer, company, dates, payment mode, notes/terms, reference fields for BC/BL, and line items. Invoice can be associated with many tickets through `ticket_invoice`; model also exposes a single-ticket relation.

Creation stores invoice totals and lines, starts status as `non-paid`, and attaches selected tickets. Editing can update ticket association and adds line totals using a separate controller path. Deletion is exposed and permitted by invoice permissions. Email and PDF actions exist.

The estimate UI has an invoice creation route that preloads estimate customer, company, tickets, lines and totals. The supplied controller code for `EstimateController::createInvoice` returns the form; direct proof of how the resulting invoice is linked back to estimate is not established in the reviewed path. Therefore “estimate converts exactly once and is immutable afterward” is **UNKNOWN**.

**Evidence:** `InvoiceController`; `Invoice` relationships; `Invoice/Create/Info.php`, `Tickets.php`; `EstimateController::createInvoice`; `routes/app-routes/commercial_routes.php`.

## 11. Payment Logic

`Bill` is the payment-like entity in the business UI. It is polymorphic, has date, mode, reference, notes, amounts, company, and `added_by`. Invoice-specific payment paths create a bill whose amount fields copy the invoice's full HT/VAT/TTC. These paths then set invoice status paid; one also sets `is_paid=true`. A delete path removes the bill and may return invoice status to non-paid. The model relation can hold multiple bills, but the inspected invoice-specific create path snapshots full invoice totals rather than accepting a partial amount.

The dashboard defines unpaid/paid/late partly by invoice status and partly by whether a bill exists. The dashboard's filtered and default-company branches use different criteria. Invoice and credit-note models expose a singular `morphOne` Bill relation. Partial payments, overpayments, remaining balance, allocation across invoices, and payment reconciliation are **not confirmed**. Do not interpret `Bill` as proof that cash was received for an independently supplied amount.

**Evidence:** `BillController`; `Bill`; `Invoice::bill/bills`; `DashboardController`; `BillFormRequest`.

## 12. Credit Notes

`InvoiceAvoir` is a credit-note document linked to an invoice, client and company, with polymorphic article lines and own number/status. Creation selects an invoice without an existing `avoir`, associates the invoice, copies invoice code into `invoice_number`, sums line gross prices, applies shared VAT calculation, and initializes its status to `paid`. Deletion and editing routes exist; its policy and controller checks do not establish that all issued credit notes are immutable. It is also related to a bill, but the exact accounting effect of that relation is **UNKNOWN**.

**Evidence:** `InvoiceAvoirController`; `InvoiceAvoir`; `InvoiceAvoirPolicy`; commercial routes.

## 13. Delivery Workflow

There are two distinct delivery concepts:

1. **Ticket/customer device delivery:** `Delivery` belongs to a ticket and reception user. Dashboard routes list tickets marked deliverable and post confirmation. The visible status catalog includes 11 (ready for delivery), 12 (ready to invoice), 13 (delivered), and 6 (return delivered). The exact ordering and whether confirmation always creates a `Delivery` row should be verified in `DashboardController::confirmLivrable*`; no single global transition rule guarantees it.
2. **Commercial delivery slip (`BLivraison`):** independent company/client document with date, code, totals, article lines, history and send flag. It is not the ticket `Delivery` model.

The distinction matters: the code label “BL” is a commercial Bon de Livraison and does not itself prove that the repaired device was handed back.

**Evidence:** `Delivery`; `BLivraison`; `DashboardController`; `routes/app-routes/routes.php`; `routes/app-routes/commercial_routes.php`.

## 14. Suppliers & Purchasing

`Provider` represents suppliers with code, contact, phone/email and purchase orders. `BCommand` (Bon de Commande) stores supplier/company, date/status, line items, totals and history; its controller supports create, update, delete, email and detail. The supplier purchase order has no confirmed automated inventory receiving or direct relation to a ticket estimate. A code/comment in ticket status refers to “awaiting purchase order,” but an explicit business link between a specific supplier order and ticket is not confirmed.

`BLivraison` stores a client/company and its own line items and totals; the reviewed relationship does not directly connect it to a purchase order. Receiving stock, stock quantities and supplier bill-payables are not confirmed.

**Evidence:** `Provider`, `BCommand`, `BLivraison`; their controllers; commercial route file.

## 15. Articles / Line Items

The `Article` model is a polymorphic line item used by estimates, invoices, credit notes, purchase orders and delivery slips. Fields include designation, integer-cast quantity, unit price, line HT amount, discount flags/values and discount amount. The catalog-like model `Product` exists but inspected document controllers use `Article` rows for lines; an integrated inventory or product-price source is not established.

Creation formula is usually unit price × quantity. Estimate/invoice estimate workflows apply a percentage discount if supplied. Purchase order and delivery-slip calculations use unit price × quantity without the estimate percentage formula in their shown create paths. `remise_fix` is present, but a full rule for fixed vs percent discounts is not confirmed.

**Evidence:** `Article`; commercial create/update controllers; `Product`.

## 16. Companies

A `Company` is the issuer context for commercial documents. Controllers pass selected companies into forms; document models use company relationships and per-company start-number/prefix settings. There is a default-company trait/scope used by dashboard queries. This appears to support multiple issuer entities inside one app (**INFERENCE**). The repository does not prove legal entity separation rules or whether users are restricted to one company.

**Evidence:** `Company`; `DefaultCompanyTrait`; `CompanyController`; invoice/estimate and purchasing models.

## 17. Users / Roles / Permissions

Roles found in seeders and application checks include `SuperAdmin`, `Admin`, `Technicien`, `Reception`, `Developper`, `SuperTechnicien`, and `ASSISTANTE DIRECTEUR`. The latter role appears in diagnosis dashboard branching; not every role is declared in the primary role seeder. Role data can be changed in administration, so seeders are evidence of intended names, not proof of current production assignments.

| Role/permission | Responsibility visible in implementation |
|---|---|
| Reception | Ticket creation; ticket-delivery confirmation views/actions |
| Technicien | Assigned ticket diagnosis and repair reports; ticket visibility |
| SuperTechnicien | Broader technician queue and actions on assigned cases |
| Admin / SuperAdmin | Estimate response and administrative ticket functions; SuperAdmin manages staff/roles and reassignment |
| Commercial permissions | Route permissions for estimates, invoices, payments, suppliers, purchase orders, delivery slips |
| Developper | Seeded role; exact business responsibilities not established |

Policies and route `can(...)` checks are mixed with role middleware and direct role checks. This matrix reports code paths, not current deployed grants.

**Evidence:** `database/seeders/RoleSeeder.php`; `AddSuperTechnicienRoleSeeder.php`; `AddNewRolesSeeder.php`; `TicketPolicy`; `routes/app-routes/routes.php`; `routes/app-routes/commercial_routes.php`; finance policies.

## 18. Reports

- **Repair reports:** typed diagnosis and repair report content stored against tickets; admin report editing and PDF generation combine the ticket, customer/technician, report text, dates and media. (`Report`, `RapportController`, `GenerateReportController`, report PDF view)
- **Operational/dashboard reports:** dashboard counts tickets, invoice/estimate states and sums financial columns; statistical/report controllers expose chart or filtered data. The dashboard uses different aggregation predicates for filtered versus default-company views, so displayed totals are not necessarily based on identical business definitions. (`DashboardController`, `StatistiqueController`)
- **Commercial document listings/filters:** estimate, invoice, bill, supplier order, delivery-slip lists support date/company/client/status/mode filters according to controller scopes.
- **Exports:** client export, CSV importer/job, and Excel screens exist. The purpose of every import/export format should be checked from its specific UI before relying on it as a business ledger.

## 19. PDFs & Business Documents

Confirmed generated PDF families include:

| Document | Source and purpose | Generation / sharing |
|---|---|---|
| Ticket/service report | Intake, diagnosis, repair, dates, customer/technician, attachments | Streamed from report controller; source is ticket and typed reports |
| Estimate | Quote document with customer/company and article lines | Public PDF route and email notification path |
| Invoice | Customer invoice with lines/totals/company | PDF builder route and email path |
| Credit note | InvoiceAvoir and lines | PDF builder route and email path |
| Purchase order | BCommand | Email route and document template |
| Commercial delivery slip | BLivraison | Document template/controller paths |

Templates reside under `resources/views/theme/{invoices_template,estimates_template,bons_template}` and `resources/views/theme/pages/Ticket/__pdf`. PDF output is generated dynamically and streamed; no evidence in these paths that each rendered PDF binary is stored as an immutable historical artifact. Public accessibility differs by route; public estimate/document routes should be treated separately from staff-only routes. Ticket report PDFs include stored report content and media paths.

**Evidence:** `routes/app-routes/commercial_routes.php`; `routes/web.php`; `PDFBuilderController`; `GenerateReportController`; `RapportController`; template folders; mail classes.

## 20. Numbering Rules

| Record | Visible strategy | Scope/reset/concurrency caveat |
|---|---|---|
| Client | configured prefix + `max(id)+1`, five-digit padding | Global table sequence; no year reset; race possible in simultaneous creates (**INFERENCE**) |
| Provider | configured provider prefix + `max(id)+1`, five digits | Global sequence; race possible (**INFERENCE**) |
| Ticket | Active `Ticket` model hook uses configured start value for the first ticket, then `max(code)+1`; `GenerateTicketCode` random 7-character action exists but no caller was found. Return code is parent code + `-R-` + incremented parent return count. | No year reset visible. Main `code` is nullable and no unique index was found in the ticket create migration; return code is unique. Numeric max+1 may collide under concurrent creates (**INFERENCE**). |
| Estimate | company start number if no estimates for company, else company maximum code + 1; left-pad to 5; prepend company prefix | Per-company; no year reset visible; max+1 is not serialized against concurrent requests |
| Invoice | same pattern with `invoice_start_number` and `prefix_invoice` | Per-company; no year reset visible; same concurrency caveat |
| Credit note | same pattern with `invoice_avoir_start_number` and `prefix_invoice_avoir` | Per-company; no year reset visible |
| Purchase order | same pattern with `bcommand_start_number` and `prefix_bcommand` | Per-company; same caveat |
| Delivery slip | same pattern with `blivraison_start_number` and `prefix_blivraison` | Per-company; same caveat |
| Bill/payment | global maximum ID + 1 padded to five, prefix `REGL-` | Global sequence; no company/year reset visible |

`full_number`/codes have unique constraints on several document tables, but no visible locking/sequence allocation protects `max+1` generation. Unique database indexes may reject a collision; the user-facing retry/recovery behavior is **UNKNOWN**. Existing migrations should not be edited to change numbering.

**Evidence:** model `creating` callbacks; `GenerateTicketCode`; `CompanyController`; migrations for the unique document columns.

## 21. Financial Calculations

### Confirmed create-path formulas

- **Gross line:** `prix_unitaire × quantity`.
- **Estimate discounted line:** if `remise > 0`, `net HT = gross × (1 − remise / 100)`; stored `taux_remise` is the computed discount amount. Otherwise net HT is gross.
- **Document HT:** create paths generally sum line HT/net amounts.
- **TVA:** `price_tva = HT × 0.2`.
- **TTC:** `price_total = HT × 1.2`.

The shared methods do not explicitly round before persistence. Blade/accessor formatting displays values to two decimal places via `number_format`, but display formatting does not establish stored rounding behavior. **Rounding policy not confirmed from source.** Some update paths add submitted lines to the existing document total rather than recomputing from all existing lines; therefore the invariant “document totals always equal current article sum” is not consistently enforced.

### Payments and dashboard amounts

Payment-like bill creation copies invoice totals into Bill fields in the invoice-specific paths. This does not establish support for arbitrary partial payment amounts. Dashboard revenue sums invoice HT/TTC values; its bill totals sum Bill TTC/VAT. These are reporting definitions in code, not a confirmed accounting policy.

### Persistence warning

Earlier migrations define multiple financial columns as unsigned big integers; later 2025 migrations change article and other document price/amount fields to `float`. Models also cast BL totals to float. Existing production financial values must not be recalculated or converted based on this static review. Any future change requires a separately approved reconciliation plan and read-only compatibility verification.

**Evidence:** `TVACalulator`; `RemiseCalculator`; commercial create/update controllers; `Article`; migrations `2025_05_06_180243_change_colums_types_in_articles_table.php`, `2025_05_07_145603_change_colums_types_in_others_table.php`, `2025_05_07_151020_change_colums_types_in_others2_table.php`.

## 22. Statuses & State Transitions

### Ticket status IDs

| ID | Constant | UI meaning from French labels |
|---:|---|---|
| 1 | `NON_TRAITE` | Non traité / unprocessed |
| 2 | `EN_COURS_DE_DIAGNOSTIC` | Diagnosis in progress |
| 3 | `EN_COURS_DE_REPARATION` | Repair in progress |
| 4 | `RETOUR_NON_REPARABLE` | Non-repairable return |
| 5 | `RETOUR_DEVIS_NON_CONFIRME` | Estimate declined |
| 6 | `RETOUR_LIVRE` | Return delivered |
| 7 | `EN_ATTENTE_DE_DEVIS` | Awaiting estimate |
| 8 | `EN_ATTENTE_DE_BON_DE_COMMAND` | Awaiting purchase order |
| 9 | `DEVIS_CONFIRME` | Estimate confirmed (defined; paths may use status 10 instead) |
| 10 | `A_REPARER` | Repair approved / to repair |
| 11 | `PRET_A_ETRE_LIVRE` | Ready for delivery |
| 12 | `PRET_A_ETRE_FACTURE` | Ready to invoice |
| 13 | `LIVRE` | Delivered |

Labels come from `app/Constants/Status.php` and `resources/lang/fr/status.php`; certain wording differs between status-label and history-label translations. The seed status list matches the same broad sequence. `Etat` is separate: 0 not diagnosed, 1 repairable, 2 non-repairable. Estimate responses are also separate: 0 pending, 1 accepted, 2 not accepted, 3 not sent, 4 sent.

### Observed transitions (not an exhaustive allowed graph)

```text
New ticket (1)
  └─ technician opens unassigned ticket → diagnosis in progress (2)
       ├─ diagnosis says repairable + report sent → awaiting estimate (7)
       └─ diagnosis says non-repairable + report sent → non-repairable return (4)

Estimate created for linked ticket → awaiting purchase order (8)
  ├─ admin accepts response → to repair (10)
  └─ admin declines response → estimate declined (5)

Repair screen opened → repair in progress (3)
  └─ repair marked done → ready for delivery (11), can_invoiced=true
       ├─ one invoiceable view accepts status 11 or 13
       ├─ another invoiceable route selects status 13 (delivered)
       └─ delivery confirmation creates Delivery, sets status 13, and starts a 3-month warranty when repairable

Return ticket intake → may copy parent's current status/history and links
```

The transition list is confirmed where indicated but is not exhaustive. Status 9 has a label/constant but accepted response code writes status 10 in the inspected controller. Status 12 is defined and displayed as “ready to invoice” in some UI conditions, but no controller assignment was found; actual invoiceable predicates use `can_invoiced` and status 11 or 13 depending on screen. No central state machine or DB enum enforces legal transitions.

## 23. Business Invariants

| Candidate invariant | Classification | Evidence / limitation |
|---|---|---|
| Ticket has customer at intake | **PARTIALLY ENFORCED** | Create FormRequest requires integer `client`; migration FK exists, but update/import paths differ |
| Ticket code unique | **PARTIALLY ENFORCED** | The unused random generator checks in application; the active model hook does not. Ticket UUID and return code are unique; primary `code` has no unique index in the create migration |
| Ticket current status matches last history row | **IMPLIED / not guaranteed** | Both `tickets.status` and `ticket_status` pivot are written independently in multiple actions |
| Diagnosis report is one per ticket/type | **PARTIALLY ENFORCED** | `updateOrCreate` keyed by ticket/type; no unique constraint confirmed |
| Repair complete enables invoicing | **ENFORCED in repair completion path** | sets status 11, `can_invoiced=true`, finish date together in that controller path |
| Invoice total equals sum of its lines | **PARTIALLY ENFORCED** | creation calculates; some update operations add to stored total; no DB constraint |
| TTC equals HT + VAT | **PARTIALLY ENFORCED** | create calculators calculate as HT×1.2 and VAT as HT×0.2; stored totals can be edited/updated across paths |
| Payment cannot exceed invoice balance | **UNKNOWN / not enforced in inspected paths** | payment-like record copies invoice total and has no visible balance guard |
| Estimate converts only once | **UNKNOWN** | model has invoice and is_invoiced fields, but no complete conversion invariant confirmed |
| Credit note only once per invoice | **PARTIALLY ENFORCED** | creation form excludes invoices already having an avoir; no DB uniqueness constraint confirmed |
| Document numbers unique | **DB ENFORCED for select tables** | migrations define unique code/full_number for some docs; not all code fields are consistently unique |
| Historical document never changes after send/payment | **NOT ENFORCED** | document edit/delete routes remain present; intended business rule unknown |
| Ticket customer and invoice customer always match | **NOT CONFIRMED** | no universal cross-entity validation identified |

## 24. Cross-Domain Relationships

- Client is parent of tickets and customer-facing documents.
- Ticket may connect to estimates and invoices using both direct one-to-one-ish relations and many-to-many pivots; code uses these relations differently in different flows.
- Estimate belongs to a client and company; it may have one direct ticket plus multiple pivot-linked tickets, and an optional invoice.
- Invoice belongs to a client and company and may reference multiple tickets; bills attach polymorphically.
- Credit note belongs to an invoice and separately stores client/company.
- Article lines polymorphically attach to commercial documents; there is no confirmed stock-on-hand relationship.
- Ticket reports, comments, media, delivery and warranty represent service operations.
- Company document prefixes/start numbers determine document identity; provider orders point to provider/company.

## 25. Hidden Business Rules

1. **Technician claim-on-open:** first technician access to an unassigned ticket assigns it and begins diagnosis.
2. **Diagnosis repairability gates next status:** only submitted diagnosis with `Etat::REPARABLE` or `NON_REPARABLE` follows the send paths.
3. **Estimate response requires an assigned technician and status 8 for admin confirmation.**
4. **Repair completion sets `can_invoiced=true`** before delivery is confirmed. One invoiceable screen includes ready-for-delivery tickets; another selects delivered tickets only.
5. **Return tickets inherit parent context** including prior status trail, assignment and commercial pivot links.
6. **Estimate discount is percentage-based in the inspected create path**, notwithstanding `remise_fix` field.
7. **Invoice/credit-note/purchase document codes depend on company configuration** while client/provider/bill codes use global sequences.
8. **Dashboard “paid”/“unpaid” classifications use both flags/status and bill relation existence**, depending on branch.

**Evidence:** controllers/models cited in sections 5, 9, 11, 20 and 21.

## 26. Business Rules That Are Not Enforced

- No central transition map validates that every status change is permitted from the previous state.
- Status history and current status are separate writes; consistency is not universally constrained.
- No database-level one-report-per-ticket-type or one-credit-note-per-invoice guarantee was identified.
- No general financial equation constraint ties header totals to line totals or HT to VAT/TTC.
- No balance check prevents overpayment or proves partial-payment settlement behavior.
- No universal validation confirms invoice customer equals ticket customer for all linked tickets.
- No invariant found that prevents editing/deleting an estimate/invoice after send, payment or conversion.
- `can_invoiced`, `livrable`, `can_make_report`, `etat` and `status` overlap in deciding workflow eligibility; their synchronization is code-path-specific.

These are descriptions of source guarantees, not recommendations to alter production behavior.

## 27. Ambiguous Business Rules

- Whether a ticket can have multiple estimates or invoices over its lifetime, including return tickets.
- What event constitutes client acceptance: a recorded response field or some external approval evidence.
- Whether estimate acceptance should use status 9 or status 10; current code writes 10.
- Whether a supplier purchase order must be linked to an estimate/ticket and whether it affects repair authorization.
- Whether `Bill` means full settlement, a receipt, or a ledger/payment event; code supports a polymorphic model but visible creation copies full totals.
- Whether credit notes are full reversals or can be partial; line items allow arbitrary amounts but no formal balancing rule is visible.
- Whether changing a client on an existing ticket is allowed after associated documents exist; update logic permits client changes and records a status-history note.
- Whether the same report history copied to a return ticket should be read as new work or inherited context.
- Whether invoice/payment and purchasing amounts are intended to include VAT at a fixed 20% for every company/document.
- Whether document PDFs must be immutable archived copies after sending.

## 28. Questions for the Business Owner

1. After a quote is accepted and the ticket is marked to repair, should a supplier purchase order still be required before technicians may start?
2. Should “Devis Confirmé” (status 9) or “à réparer” (status 10) be the stored status after client approval?
3. Can one ticket have multiple estimates or invoices, especially when it is a return ticket? If yes, which documents should the ticket overview show as primary?
4. Does each `Bill` represent a full invoice settlement, a partial payment, or any payment receipt? Should the application allow multiple partial payments and calculate a remaining balance?
5. Can credit notes be partial, and must they be linked to one original invoice exactly once?
6. Should an estimate/invoice remain editable after it has been emailed, accepted, paid, or converted?
7. Is a ticket considered delivered only after reception records a `Delivery` row, and should invoice readiness happen before or after delivery?
8. Is the 20% VAT rate intentionally fixed for every company and every document type?
9. Should changing a ticket's client remain allowed after estimates, invoices, or delivery paperwork exist?
10. Are historical PDFs expected to preserve the exact rendered document that was sent, or is generating the PDF from current records acceptable?

## 29. Current End-to-End Workflow

```text
┌────────────┐
│   Client   │── phones / emails / category / company
└─────┬──────┘
      │ registers service case with device description + required image
      ▼
┌────────────────────────────┐
│ Ticket: status 1, etat 0   │
└─────────────┬──────────────┘
              │ technician opens/claims
              ▼
┌────────────────────────────────────┐
│ Diagnosis report; status 2         │
│ etat = repairable / non-repairable │
└───────────┬────────────────┬───────┘
            │ repairable     │ not repairable
            │ report sent    ▼
            │          ┌──────────────────┐
            │          │ Return status 4  │
            │          └──────────────────┘
            ▼
┌────────────────────────────┐
│ Awaiting estimate: status 7│
└─────────────┬──────────────┘
              │ estimate created: ticket moves to status 8
              ▼
┌────────────────────────────┐
│ Estimate + article lines   │── emailed / client response
└─────────────┬──────────────┘
        accepted│                     │declined
               ▼                     ▼
     ┌──────────────────┐   ┌───────────────────────┐
     │ To repair: 10    │   │ Declined return: 5    │
     └────────┬─────────┘   └───────────────────────┘
              │ technician repair report; status 3
              ▼
┌────────────────────────────────────┐
│ Repair complete: status 11         │
│ can_invoiced=true; finished_at set │
└────────────┬─────────────┬─────────┘
             │             │
             │ invoice     │ reception delivery
             ▼             ▼
┌────────────────────┐  ┌─────────────────────┐
│ Invoice + lines    │  │ Delivery record /   │
│ status non-paid    │  │ delivered status 13│
└──────────┬─────────┘  └─────────────────────┘
           │ bill/payment entry (visible path copies invoice totals)
           ▼
┌────────────────────┐
│ Invoice marked paid│
└────────────────────┘

Parallel commercial workflows:
Provider → Purchase Order (BCommand) → (no confirmed receiving link)
Client   → Commercial Delivery Slip (BLivraison)
Invoice  → Credit Note (InvoiceAvoir)
```

**Diagram caveat:** this is a synthesis of observed controller paths, not a guaranteed single state machine. The repository allows multiple paths and separates current status, status-history records, diagnosis outcome, invoice eligibility and delivery flags.

# Final Summary

## What MaintenanceApp is

MaintenanceApp helps a service business keep track of customer devices that need diagnosis or repair. Staff register the customer and open a ticket with a description and photo. Technicians record what they found and what work they completed. The business can prepare estimates and invoices connected to tickets, then record payment-like entries. Reception can track device delivery, and the app produces service reports and commercial documents. It also manages suppliers, purchase orders, delivery slips, company document numbering, warranties, staff roles and reporting. The code does not fully define all accounting or approval policies, so those points need the owner’s confirmation.

## Core business workflow

Client intake → ticket → technician diagnosis and repairability decision → estimate and client response when applicable → repair report → ticket becomes ready for delivery and invoicing → delivery and invoice/payment actions. The exact order between invoice and delivery is not enforced consistently.

## Most important entities

Client, Ticket, Status history, Report, Estimate, Invoice, Bill, InvoiceAvoir, Article, Delivery, Warranty, Company, Provider, BCommand, and BLivraison.

## Most important business rules

- New intake requires client, device/article, description and an image.
- Technician opening an unassigned ticket claims it and starts diagnosis.
- Submitted diagnosis determines repairable/non-repairable path.
- Estimate creation for a ticket moves it to awaiting purchase order.
- Admin estimate decision moves ticket to accepted/to-repair or declined-return status.
- Completing repair enables ticket invoicing.
- Estimate discounts are applied as percentage reductions on line HT.
- The shared tax helper calculates TVA at 20% and TTC at 120% of HT.
- Ticket status, repairability and invoice/delivery eligibility are distinct fields and are not governed by one universal transition machine.

## Rules enforced only by application code

Ticket status updates/history, technician assignment, diagnosis/repair report closure, estimate response transitions, invoicing eligibility and number allocation depend on controller/model code. They are not all represented by database constraints. Some permissions are also enforced at route/policy level.

## Rules enforced by database constraints

Migrations define foreign keys for several core relations and unique constraints for UUIDs and selected document numbers/codes (including invoice full number, credit-note full number, bill identifiers, and ticket return code). Constraint coverage is not uniform; check the specific migration before relying on a rule. This audit did not query the deployed schema.

## Dangerous implicit assumptions

- A stored ticket status is expected to correspond to its latest status-history row.
- Document header totals are expected to correspond to current line items.
- Bill presence/status is treated as paid state in dashboards.
- A return ticket may inherit prior status history and document links.
- Per-company `max(code)+1` numbering is assumed to be safe under concurrent document creation.
- Fixed 20% tax appears to apply broadly.

These are assumptions visible in code, not statements about your intended policy.

## Questions for you

Please answer the questions in section 28, especially the required purchase-order approval step, partial-payment meaning, status 9 vs 10, document editability after issue, and delivery versus invoice order. Those answers determine whether the current behavior matches your business.

## Primary source index

- Routes: `routes/app-routes/routes.php`, `routes/app-routes/commercial_routes.php`, `routes/web.php`
- Ticket business logic: `app/Http/Controllers/Administration/Ticket/TicketController.php`, `Diagnostique/DiagnostiqueController.php`, `Reparation/ReparationController.php`, `app/Policies/TicketPolicy.php`
- Commercial document logic: `app/Http/Controllers/Commercial/Estimate/EstimateController.php`, `Invoice/InvoiceController.php`, `InvoiceAvoir/InvoiceAvoirController.php`, `Bill/BillController.php`, `BCommand/BCommandController.php`, `BL/BLController.php`
- Data model: `app/Models/Ticket.php`, `app/Models/Client.php`, `app/Models/Finance/*.php`, `app/Models/Utilities/*.php`
- Constants and labels: `app/Constants/Status.php`, `Etat.php`, `Response.php`, `resources/lang/fr/status.php`
- Schema history: `database/migrations/`; no migration was run and deployed DB state was not inspected.
