# MaintenanceApp Permission Access Matrix

Status: source defines a proposed `Technicien` baseline through `TechnicienRolePermissionsSeeder`. The seeder has not been run. No production users, roles, or permission assignments were inspected or changed.

The profile permission screen assigns direct permissions to users. Role permissions are inherited separately by Spatie and are now displayed as read-only in that screen. `RoleSeeder` creates role names; `FeaturePermissionSeeder` creates permission records; neither grants permissions to roles. `TechnicienRolePermissionsSeeder` is a separate, additive mapping for the proposed technician baseline. It is intentionally not called by `DatabaseSeeder`.

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

1. Review the technician baseline against actual access needs before applying it. Every current and future user with the `Technicien` role inherits these nine permissions.
2. Verify whether `SuperTechnicien` and `ASSISTANTE DIRECTEUR` exist and identify affected accounts. **REQUIRES READ-ONLY PRODUCTION DATA VERIFICATION.** Do not infer these account groups from `RoleSeeder`.
3. Permission checks now control the diagnosis, repair, ticket delivery, client actions, dashboard analytics, backup, and client export route/view surfaces. Technician assignment restrictions remain in `TicketPolicy`. Existing reports/dashboard data and other legacy business rules still need a broad staging review before the permission-only rollout.
4. The SuperAdmin Gate bypass remains the explicit emergency administrator exception. Other account roles do not independently grant feature access; grants must come from assigned permissions.
5. Apply the matrix to representative staging accounts and verify allowed actions, direct URLs, and denied cross-assignment access before production rollout.

## Production data and rollout

- No migrations or seeders were run for this change.
- Never run a seeder against production under the current production safety rule.
- For isolated local or staging validation, verify `APP_ENV` and `DB_DATABASE` first, then run `FeaturePermissionSeeder` and `TechnicienRolePermissionsSeeder` individually as needed. The technician seeder adds the nine proposed permissions to the `Technicien` role without removing any existing grants. Do not use `DatabaseSeeder`; it creates sample users, clients, providers, and tickets.
- **Production Data Risk: High.** Assigning permissions to `Technicien` changes the effective access of every user with that role. **Existing Data Impact:** existing role grants are preserved by the additive seeder, but current grants and role membership were not inspected. **REQUIRES READ-ONLY PRODUCTION DATA VERIFICATION** before deciding whether the proposed bundle matches production intent.
- The current production-data constraint prohibits running seeders against production and prohibits modifying production records. Therefore this change does not apply the role grants to production. Do not treat deploying this seeder as having activated the role baseline there.
- **Rollback considerations:** compare a read-only pre-change role-permission snapshot with the new mapping. If the seeder is later run in a permitted non-production environment, rollback should revoke only permissions newly added by that run; do not `syncPermissions([])` or remove pre-existing grants.
- Permission changes affect access to financial documents, client data, imports, and ticket operations. Keep the assignment list private and review grants with the responsible administrator before applying them.
