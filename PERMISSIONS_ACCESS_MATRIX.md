# MaintenanceApp Permission Access Matrix

Status: proposed grant plan only. No production users, roles, or permission assignments were inspected or changed.

The application assigns permissions directly to users through the user permission screen. `RoleSeeder` creates role names but does not attach permissions to roles. `FeaturePermissionSeeder` only creates permission records; it does not grant them. Therefore this matrix is a manual account review guide, not an automatic role mapping.

## Proposed initial grants

| Account group | Suggested permissions | Notes |
| --- | --- | --- |
| SuperAdmin | All abilities through the existing SuperAdmin Gate bypass | No permission list needs to be attached for the bypass to work. |
| Admin | Existing `ticket.*`, `client.*`, `invoices.*`, `estimates.*`, `bcommandes.*`, `providers.*`, `payments.*`, `blivraison.*`, `report.*`; new `companies.*`, `ticket.delivery.*`, `diagnostic.*`, `reparations.*`, `categories.*`, `client.import`, `report.clients.browse`, `warranty.*` | Do not grant `admin.*`, `roles_permissions.*`, `imports.csv.browse`, or `ticket.reassign` by default. They permit account administration, permission administration, importing, or ticket reassignment. Existing account management routes were SuperAdmin-only. |
| Technicien | `ticket.browse`, `ticket.read`, `diagnostic.browse`, `diagnostic.edit`, `reparations.browse`, `reparations.edit`, `reparations.complete` | Existing code restricts diagnosis and repair actions to the assigned technician or SuperTechnicien flow; permission grants alone do not replace those assignment checks. |
| Reception | `client.browse`, `ticket.browse`, `ticket.read`, `ticket.create`, `ticket.delivery.browse`, `ticket.delivery.confirm` | Do not grant `ticket.delivery.admin_confirm` by default. Client edit/delete and financial permissions are not included by default. |
| Developper | No default grant | The old sidebar linked to role management, while the route was SuperAdmin-only. Grant `roles_permissions.browse` or change permissions only when delegation is explicitly intended. |
| SuperTechnicien | Review whether this role exists, then consider the Technician set plus assigned-ticket diagnosis/repair abilities | This role is referenced in policies/controllers but is not created by the current `RoleSeeder`. |
| ASSISTANTE DIRECTEUR | Review whether this role exists, then grant only the diagnostic/report abilities required | This role is referenced in diagnostic code but is not created by the current `RoleSeeder`. |
| Other users | No access by default; grant only required browse/actions | Dashboard access remains available to authenticated users and is not permission-gated. |

`.*` above means the five existing CRUD permissions for that module (`browse`, `read`, `create`, `edit`, `delete`) where defined. The new `ticket.delivery.*` group contains browse, confirm, and admin confirm.

## New permissions added by the code change

- `companies.browse`, `companies.create`, `companies.edit`, `companies.delete`
- `ticket.delivery.browse`, `ticket.delivery.confirm`, `ticket.delivery.admin_confirm`, `ticket.reassign`
- `diagnostic.browse`, `diagnostic.edit`, `diagnostic.send_report`, `diagnostic.confirm`
- `reparations.browse`, `reparations.edit`, `reparations.complete`
- `categories.browse`, `categories.create`, `categories.delete`
- `client.import`, `report.clients.browse`
- `warranty.browse`, `warranty.create`
- `roles_permissions.browse`, `roles_permissions.roles.create`, `roles_permissions.roles.delete`, `roles_permissions.permissions.create`, `roles_permissions.permissions.delete`
- `imports.csv.browse`

## Items to resolve before permission-only deployment

1. Assign the approved abilities to individual accounts before deploying route gates. A user who previously relied only on a role will lose access until their permissions are granted.
2. Verify whether `SuperTechnicien` and `ASSISTANTE DIRECTEUR` exist and identify affected accounts. **REQUIRES READ-ONLY PRODUCTION DATA VERIFICATION.** Do not infer these account groups from `RoleSeeder`.
3. Route/sidebar checks have moved to permissions, but inner business logic still uses roles. `DiagnostiqueController` and `ReparationController` choose their data sets by role; `TicketPolicy` still role-gates diagnose, repair, and reassignment; and `DashboardController::ticketLivrable()` builds different results by role. A non-SuperAdmin with only the new permission may pass the route but receive an empty/null result or fail the inner authorization. Refactor these checks to permissions while preserving technician-to-ticket assignment rules before granting those abilities to non-role users.
4. `ticket.reassign` is now a route permission, but `TicketPolicy::canReassign()` still requires SuperAdmin. Decide whether that action must remain exclusive to SuperAdmin or be delegated through the new permission, then align the policy.
5. Test the matrix with representative accounts in staging, including denied direct URLs and allowed actions, before production rollout.

## Production data and rollout

- No migrations or seeders were run for this change.
- Never run a seeder against production under the current production safety rule.
- First provision these permission definitions and account grants in staging. For production, use only an explicitly approved, reviewed deployment mechanism after confirming backups, rollback, and the exact account-to-permission list. Do not deploy permission-only route checks before grants are in place.
- Permission changes affect access to financial documents, client data, imports, and ticket operations. Keep the assignment list private and review grants with the responsible administrator before applying them.
