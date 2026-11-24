# Changelog

## 1.0.0 (in progress)

- **Source:** commit `fb3e9ea` (`fb3e9eaeb6387fac52f2da163c82057c969ce356`, 2026-11-11) of the source app.
- **Export started:** 2026-11-24.
- **Pinned tools:** Tailwind CSS 4.2.4, Alpine.js 3.15.11.

### Phase 2: foundation (2026-11-24)

- Brand: `brand/brand.json` as the single source. `tools/build.mjs` generates:
  - `brand.css`, with the `@font-face` rules and the brand `@theme` tokens;
  - `brand.php`;
  - placeholder logos;
  - the gallery assets `brand.js`, `kit.js` and `kit.css`.
- Theme: `theme/theme.css`, copied from the source stylesheet. The deprecated colour aliases and button variants were removed; the overlay backdrop is now derived from `brand-deep`.
- Laravel:
  - 32 `x-admin.*` components and 2 `x-messages` components, decoupled from the source app (`App\Support\UiKit\Format`, `BadgeVariant`, `HasStatusBadge`, `config('brand.*')`);
  - deprecated props removed (`legacy-margin`, the stats-card `color` and icon slot, badge `primary`, button `outline`, `success` and `warning`);
  - tenant, platform and guest layouts driven by plain view data;
  - Alpine components `adminNavGroups` and `adminSelect2`;
  - `Format` tests;
  - `INSTALL.md`.
- Gallery: 30 pages.
  - 3 foundation pages, 17 component pages, 1 domain page and 3 pattern pages;
  - 5 layout pages (tenant owner, tenant restricted member, phone field screen, platform, guest);
  - the index.
- Checks: links, tokens ("kit only") and leaks.
- Docs: `README.md`, `REFERENCE.md` (first version), `BRANDING.md`.

### To come

- Phase 3: tenant screens.
- Phase 4: platform, guest/auth and error screens, e-mails, PDF documents.
- Phase 5: final `REFERENCE.md`, final verification.
- Excluded until the source app migrates them: 19 tenant screens and 11 platform screens (`REFERENCE.md`, "Screens not in this version"), to be added in v1.1.
