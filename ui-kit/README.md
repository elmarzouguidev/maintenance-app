# UI kit

A portable, brandable reference kit for back-office interfaces built with **Laravel + Blade + Alpine.js + Tailwind CSS v4**:
- a static gallery of every layout, component and pattern;
- the matching Blade components, layouts, Alpine components and formatting helpers;
- written rules for people and coding agents.

> Version 1.0.0 is in progress: foundations, components, patterns and layouts are done. Screens, e-mails and documents come next (see `CHANGELOG.md`).

## Open the gallery

Open `static/index.html` in a browser. No server, database or build step is needed. Every page links to every other page through the "All pages" menu of the bar at the top. That bar shows each page's source, pattern and states; it is gallery chrome, not part of the design system.

## Contents

| Path | What |
| --- | --- |
| `REFERENCE.md` | **The guide for agents and developers**: principles, tokens, components, status mapping, responsive, accessibility, motion and content rules, do/don't |
| `BRANDING.md` | Every brandable value, and how to rebrand |
| `brand/brand.json` | The single brand source (name, logos, brand colours, font) |
| `brand/` | Generated `brand.css` and `brand.php`, logos, fonts |
| `theme/theme.css` | The Tailwind v4 theme: tokens and `admin-*` component classes |
| `static/` | The gallery (HTML pages and `assets/kit.css`, `kit.js`, `brand.js`) |
| `laravel/` | Blade components and layouts, Alpine components, support classes, `INSTALL.md` |
| `tools/` | `build.mjs` and the checks (`check-links.mjs`, `check-tokens.mjs`, `check-leaks.sh`) |

## Use it in a new project

1. Copy `ui-kit/` to the project root.
2. Follow `laravel/INSTALL.md`.
3. Add the snippet of `REFERENCE.md` (section 13) to the project's `CLAUDE.md` or `AGENTS.md`, so agents follow the kit for all UI work.

## Rebrand and rebuild

```bash
cd ui-kit
npm install        # once; installs pinned @tailwindcss/cli, tailwindcss and alpinejs in ui-kit/node_modules
# edit brand/brand.json
npm run build      # brand.css, brand.php, logos, static/assets/{brand.js,kit.js,kit.css}
npm run check      # every page reachable, no broken link, no raw styling, no leak
```

`npm run build` compiles `static/assets/kit.css` with the Tailwind CSS v4 CLI from `tools/kit.entry.css`: `theme/theme.css` over the classes found in `static/` and `laravel/`. It touches nothing outside `ui-kit/`. Fonts and scripts are local files: the gallery makes no network request.

## Checks

| Command | Checks |
| --- | --- |
| `node tools/check-links.mjs` | Every page of `static/` is reachable from `index.html`; no broken relative link or asset |
| `node tools/check-tokens.mjs` | No raw styling in the static pages and the Blade files: palette colours, arbitrary values, colour codes, inline colours, heavy weights, external assets |
| `sh tools/check-leaks.sh` | No reference to the source app, no secret, e-mail addresses only on `example.com` |

## Re-exporting from the source app

The gallery pages are not written by hand. They are rendered through the source app's real layouts and components with fictional data, then cleaned:
- no CSRF token, honeypot or session data;
- brand placeholders instead of the product name and logos;
- `#` instead of app URLs.

The exporter lives in the source repository, outside the kit, at `tools/ui-kit-export/`. It may reference the app's classes; the leak check only scans `ui-kit/`.

From the source repository root:

```bash
vendor/bin/pest --configuration=tools/ui-kit-export/phpunit.xml --test-directory=tools/ui-kit-export
(cd ui-kit && npm run build && npm run check)
```

- The first command writes `ui-kit/static/**/*.html`. It also checks that the kit's Blade components render exactly like the app's for every gallery page, and that the kit layouts render from plain view data.
- `KIT_PAGE=buttons vendor/bin/pest …` exports one page.
- The page list is `tools/ui-kit-export/Support/Manifest.php`. The demo views are in `tools/ui-kit-export/views/`.
- The exporter runs on an in-memory SQLite database with the app's test fixtures. It never touches the development database.

## Licences

- **Figtree** (the default font): SIL Open Font License 1.1, `brand/fonts/figtree/OFL.txt`.
- **Icons:** Heroicons v2 outline, MIT (inlined in `x-admin.icon`).
- **Alpine.js** 3.15.11: MIT (bundled in `static/assets/kit.js`).
- **Tailwind CSS** 4.2.4: MIT (build tool only).
- **Vehicle top view** (`theme/images/vehicle-condition-top-view.png`): taken from the source app, licence not recorded. Check it before reusing the optional vehicle components in another product.
