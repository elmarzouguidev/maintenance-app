# Branding the kit

Every brandable value lives in **one file: `brand/brand.json`**. Edit it, then rebuild:

```bash
cd ui-kit
npm install        # once: pinned @tailwindcss/cli, tailwindcss and alpinejs, inside ui-kit/ only
npm run build      # regenerates every brand output and the gallery CSS/JS
npm run check      # links, tokens and leaks
```

The build regenerates every output from `brand.json`. Never edit the generated files by hand.

## What `brand.json` holds

| Key | Used for | Generated into |
| --- | --- | --- |
| `name` | Product name: page titles, sidebar, footers, guest logo alt text, the platform band | `brand.php` (`config('brand.name')`), `static/assets/brand.js` (`{{ brand }}` in the gallery) |
| `shortName`, `initial` | Short name; the letter of the placeholder logo | `brand.php`, `brand.js`, `brand/*.svg` |
| `version` | Version shown in the footers | `brand.php` (`config('brand.version')`), `brand.js` (`{{ brand.version }}`) |
| `supportEmail` | Support address placeholder | `brand.php`, `brand.js` (`{{ brand.email }}`) |
| `vendor.name`, `vendor.url` | Footer credit ("Développé par …") | `brand.php`, `brand.js` (`{{ brand.vendor }}`) |
| `logo.generate` | `true`: the build draws neutral placeholder logos from `colors.brand` and `initial`. `false`: keep your own files. | `brand/logo.svg`, `logo-mark.svg`, `favicon.svg` |
| `logo.logo`, `logo.logoMark`, `logo.favicon` | File names of the wide logo, the square mark (sidebar, guest pages, apple-touch-icon) and the favicon, in `brand/` | `brand.php` paths, copies in `static/assets/brand/` |
| `colors.*` | The brand colour tokens (table below) | `brand/brand.css` (`@theme`), then `static/assets/kit.css` |
| `font.family`, `font.stack`, `font.faces` | Font family, fallback stack and `@font-face` files (relative to `brand/`) | `brand/brand.css` |

## Colour tokens

Only these tokens are brand tokens. The surfaces, text, status and chart tokens are part of the design system (`theme/theme.css`) and do not change with the brand.

| `brand.json` key | Token | Role | Default |
| --- | --- | --- | --- |
| `brand` | `--color-brand` | Shell colour: platform chip, avatars, primary stat marker, theme-color | dark violet-navy |
| `brandDeep` | `--color-brand-deep` | Darkest shade: platform context band, overlay backdrops (at 50 %) | near black |
| `accent` | `--color-accent` | **Primary action, focus, selection** | violet |
| `accentHover` | `--color-accent-hover` | Primary action on hover (about 10 % darker) | |
| `accentSoft` | `--color-accent-soft` | Selected background (very light tint) | |
| `accentStrong` | `--color-accent-strong` | Text on `accentSoft` (dark shade, at least 4.5:1 on it) | |
| `onAccent` | `--color-on-accent` | Text and icons on accent, brand and status fills | white |
| `focus` | `--color-focus` | Focus outline (usually = `accent`) | |
| `sidebar` | `--color-sidebar` | Tenant sidebar background (usually = `brandDeep`) | |
| `sidebarText` | `--color-sidebar-text` | Sidebar links | |
| `sidebarMuted` | `--color-sidebar-muted` | Sidebar group labels | |
| `sidebarRail` | `--color-sidebar-rail` | Current-page rail (a light accent tint) | |
| `sidebarFocus` | `--color-sidebar-focus` | Focus outline on the sidebar | |
| `sidebarStrong` | `--color-sidebar-strong` | Current and hovered sidebar text | white |
| `sidebarHover`, `sidebarActive` | `--color-sidebar-hover`, `--color-sidebar-active` | Translucent white layers on the sidebar | 5 % / 8 % white |

**Contrast checklist after a change** (WCAG 2.2 AA):
- `onAccent` on `accent`, and on `accentHover`: at least 4.5:1;
- `accentStrong` on `accentSoft`: at least 4.5:1;
- `sidebarText` and `sidebarMuted` on `sidebar`: at least 4.5:1;
- `focus` against `surface` (white) and `canvas`: at least 3:1.

The chart palette (`chart-1` … `chart-6`) is validated as a set for colour-vision deficiencies. `chart-1` equals the default accent; if you change it, re-validate the whole set.

## Fonts

1. Put the `woff2` files in `brand/fonts/<family>/`, with their licence file.
2. Set `font.family`, `font.stack` (keep a system fallback) and one `faces` entry per file: `weight` (400, 500 and 600 are used), `file`, and `unicodeRange` (`latin`, `latin-ext` or a raw range).
3. `npm run build`.

The default font is Figtree, under the SIL Open Font License 1.1 (`brand/fonts/figtree/OFL.txt`), so it may be bundled and redistributed.

## Logos

- **Placeholders** (`logo.generate: true`): the square mark is the `initial` on the `brand` colour; the wide logo adds the `name`. They follow every rebuild.
- **Your own logo:** set `logo.generate` to `false`, then replace `brand/logo.svg`, `brand/logo-mark.svg` and `brand/favicon.svg` (keep the names, or change `logo.*`). The mark is shown at 36 px in the sidebar and 64 px on guest pages, on a white tile: keep it legible small.

## Where each output is used

| Output | Used by |
| --- | --- |
| `brand/brand.css` | `theme/theme.css` imports it, so both your app CSS and `static/assets/kit.css` get the brand tokens |
| `brand/brand.php` | Copied to `config/brand.php`; the kit layouts and components read `config('brand.*')` |
| `brand/*.svg` | Copied to `public/vendor/ui-kit/brand/` in a project; `static/assets/brand/` in the gallery |
| `static/assets/brand.js` | The gallery: `kit.js` fills `{{ brand }}`, `{{ brand.short }}`, `{{ brand.initial }}`, `{{ brand.version }}`, `{{ brand.vendor }}` and `{{ brand.email }}`, and sets the theme-color meta |

## Language and formats are not brand values

- **Copy language.** The UI copy of the kit is French, the source app's language. Translating it is a content task (REFERENCE.md, section 10), not a brand setting.
- **Formats.** Currency, locale and date formats are set once in PHP: `App\Support\UiKit\Format::$currency`, `::$locale`, `::$dateFormat`… (`laravel/INSTALL.md`).
