# MaintenanceApp Permission Access Matrix

Status: proposed grant plan only. No production users, roles, or permission assignments were inspected or changed.

The application assigns permissions directly to users through the user permission screen. `RoleSeeder` creates role names but does not attach permissions to roles. `FeaturePermissionSeeder` only creates permission records; it does not grant them. Therefore this matrix is a manual account review guide, not an automatic role mapping.

## Proposed initial grants

| Account group | Suggested permissions | Notes |
| --- | --- | --- |
| SuperAdmin | All abilities through the existing SuperAdmin Gate bypass | No permission list needs to be attached for the bypass to work. |
| Admin | Existing `ticket.*`, `client.*`, `invoices.*`, `estimates.*`, `bcommandes.*`, `providers.*`, `payments.*`, `blivraison.*`, `report.*`; new `dashboard.analytics.browse`, `calendar.browse`, `contacts.browse`, `companies.*`, `ticket.delivery.browse`, `ticket.delivery.browse_all`, `ticket.delivery.confirm`, diagnostic and repair abilities, `categories.*`, `report.clients.browse`, `warranty.*` | Do not grant `ticket.delivery.admin_confirm`, `admin.*`, `admin.permissions.manage`, `roles_permissions.*`, `imports.csv.browse`, `client.import`, `client.export`, `emails.browse`, `settings.browse`, `ticket.reassign`, or `backup.*` by default. These allow elevated delivery confirmation, account/permission administration, data import/export, mail/settings access, reassignment, or backup operations. |
| Technicien | `ticket.browse`, `ticket.read`, `ticket.work`, `diagnostic.assigned.browse`, `diagnostic.edit`, `diagnostic.send_report`, `reparations.assigned.browse`, `reparations.edit`, `reparations.complete` | Diagnosis and repair checks retain assignment constraints. `ticket.work` makes the user eligible for assignment. `diagnostic.manage_assigned` and `reparations.manage_assigned` are for delegated supervision across assigned tickets. |
| Reception | `client.browse`, `ticket.browse`, `ticket.read`, `ticket.create`, `ticket.delivery.browse`, `ticket.delivery.confirm` | Do not grant `ticket.delivery.admin_confirm` by default. Client edit/delete and financial permissions are not included by default. |
| Developper | No default grant | The old sidebar linked to role management, while the route was SuperAdmin-only. Grant `roles_permissions.browse` or change permissions only when delegation is explicitly intended. |
| SuperTechnicien | Review whether this role exists, then consider the Technician set plus `diagnostic.manage_assigned` and `reparations.manage_assigned` | These permissions enable supervisory views and assignment management without role checks. This role is not created by the current `RoleSeeder`. |
| ASSISTANTE DIRECTEUR | Review whether this role exists, then grant only the diagnostic/report abilities required | This role is referenced in diagnostic code but is not created by the current `RoleSeeder`. |
| Other users | No access by default; grant only required browse/actions | The basic dashboard remains available to authenticated users; financial and analytics panels require `dashboard.analytics.browse`. |

`.*` above means the existing CRUD permissions for that module (`browse`, `read`, `create`, `edit`, `delete`) where defined. Delivery access is split between `ticket.delivery.browse`, `ticket.delivery.browse_all`, `ticket.delivery.confirm`, and `ticket.delivery.admin_confirm`.

## New permissions added by the code change

- `companies.browse`, `companies.create`, `companies.edit`, `companies.delete`
- `dashboard.analytics.browse`
- `backup.browse`, `backup.create`, `backup.delete`, `backup.download`, `client.export`
- `calendar.browse`, `contacts.browse`, `emails.browse`, `settings.browse`
- `ticket.delivery.browse`, `ticket.delivery.browse_all`, `ticket.delivery.confirm`, `ticket.delivery.admin_confirm`, `ticket.reassign`, `ticket.work`
- `diagnostic.browse`, `diagnostic.assigned.browse`, `diagnostic.manage_assigned`, `diagnostic.edit`, `diagnostic.send_report`, `diagnostic.confirm`
- `reparations.browse`, `reparations.assigned.browse`, `reparations.manage_assigned`, `reparations.edit`, `reparations.complete`
- `categories.browse`, `categories.create`, `categories.delete`
- `client.import`, `report.clients.browse`
- `warranty.browse`, `warranty.create`
- `roles_permissions.browse`, `roles_permissions.roles.create`, `roles_permissions.roles.delete`, `roles_permissions.permissions.create`, `roles_permissions.permissions.delete`
- `admin.permissions.manage`
- `imports.csv.browse`

## Items to resolve before permission-only deployment

1. Assign the approved abilities to individual accounts before deploying route gates. A user who previously relied only on a role will lose access until their permissions are granted.
2. Verify whether `SuperTechnicien` and `ASSISTANTE DIRECTEUR` exist and identify affected accounts. **REQUIRES READ-ONLY PRODUCTION DATA VERIFICATION.** Do not infer these account groups from `RoleSeeder`.
3. Permission checks now control the diagnosis, repair, ticket delivery, client actions, dashboard analytics, backup, and client export route/view surfaces. Technician assignment restrictions remain in `TicketPolicy`. Existing reports/dashboard data and other legacy business rules still need a broad staging review before the permission-only rollout.
4. The SuperAdmin Gate bypass remains the explicit emergency administrator exception. Other account roles do not independently grant feature access; grants must come from assigned permissions.
5. Apply the matrix to representative staging accounts and verify allowed actions, direct URLs, and denied cross-assignment access before production rollout.

## Production data and rollout

- No migrations or seeders were run for this change.
- Never run a seeder against production under the current production safety rule.
- First provision these permission definitions and account grants in staging. For production, use only an explicitly approved, reviewed deployment mechanism after confirming backups, rollback, and the exact account-to-permission list. Do not deploy permission-only route checks before grants are in place.
- Permission changes affect access to financial documents, client data, imports, and ticket operations. Keep the assignment list private and review grants with the responsible administrator before applying them.
