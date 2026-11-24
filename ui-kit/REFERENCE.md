# UI kit reference (for agents)

Version 1.0.0, Phase 2 draft: foundations, components, layouts and rules. Phase 3 and 4 add the screens, e-mails and documents; Phase 5 completes the page patterns and the snippet in section 13.

Read this file before any UI work in a project that contains `ui-kit/`. Follow it literally. When something is not covered, copy the closest existing pattern of the gallery (`ui-kit/static/`) and do not invent new styles.

## 1. Purpose and how to use the kit

The kit is a back-office design system for Laravel + Blade + Alpine.js + Tailwind CSS v4, exported from a production app with fictional data.

| What | Where |
| --- | --- |
| Browsable gallery, every component and layout | `static/index.html` (open in a browser, no build) |
| Blade components to copy into a project | `laravel/components/` (`x-admin.*`, `x-messages`) |
| Blade layouts | `laravel/layouts/` (tenant, platform, guest) |
| Alpine components | `laravel/js/` |
| Formatting helper, status contract | `laravel/support/` (`App\Support\UiKit\…`) |
| Tokens and component classes | `theme/theme.css` + `brand/brand.css` |
| Brand values (name, logo, colours, font) | `brand/brand.json` only (see `BRANDING.md`) |
| Install in a project | `laravel/INSTALL.md` |

To build a new screen:

1. Find the closest page in the gallery. Each page states its source, pattern and states in the bar at the top (also in `<meta name="kit:…">`).
2. Copy its structure: layout, `x-admin.page-header`, then the body pattern (list, detail, form, dashboard).
3. Use only kit components and token utilities. Open the component's gallery page for its props and the exact Blade usage (the "Blade" block under each demo is the code that rendered it).
4. Check the rules below (sections 5 to 12) before finishing.

## 2. Design principles

- **Hybrid direction.**
  - "Ardoise" (direction A) is the whole system: tokens, colours, 6/10 px radius, 40 px controls, compact hairline tables. It is used for navigation, lists, forms and dashboards.
  - From "Encre" (direction C), only the detail-page structure is used: a white page with sections separated by hairlines (`x-admin.detail-section`), instead of stacked bordered cards. Only key summaries (amounts, a deposit) keep a bordered panel, in the aside.
- **Density.** An operational tool used all day: compact rows (`py-2.5`), 14 px body text, no decorative blocks, no hero sections.
- **Hierarchy.** One visible page title (`x-admin.page-header`; the topbar has no title). Then sections with `admin-section-title`. Secondary information is `text-fg-muted`, never smaller than `text-caption`.
- **Colour has a job.**
  - The brand colour is the shell (sidebar, platform band).
  - The accent colour (default violet) is **only** for the primary action, focus and selection.
  - Status colours carry meaning only (section 5).
  - Everything else is neutral.
- **Borders, not shadows.** Panels have a hairline border and no shadow. `shadow-overlay` is for overlays only: menus, popovers, dialogs, sheets.
- **Explicit states.** Every list has an empty state; every form shows errors next to the field; refused actions stay visible and explain why (`x-admin.delete-action`).

## 3. Tokens

Use the token utilities (`bg-*`, `text-*`, `border-*`, `outline-*`, `fill-*`). Gallery: `static/foundations/tokens.html`.

**Forbidden in views:**
- Tailwind palette colours (`slate`, `gray`, `blue`, `emerald`…, `white`, `black`);
- arbitrary values (`[13px]`, `[#…]`), except `[var(--…)]` inside kit components;
- hex, `rgb()`, `hsl()` or `oklch()` colours, and inline colour styles;
- `font-bold`, `font-extrabold`, `font-black`.

`tools/check-tokens.mjs` enforces these rules on the kit.

### Colours

| Group | Tokens | Use |
| --- | --- | --- |
| Brand (from `brand.json`) | `brand`, `brand-deep` | Shell: platform chip and band, avatars, primary stat marker, overlay backdrop (`brand-deep` at 50 %). |
| | `accent`, `accent-hover`, `accent-soft`, `accent-strong`, `on-accent`, `focus` | Primary button, focus outline, selected option or card (`accent-soft` + `accent-strong`), current item. |
| | `sidebar`, `sidebar-text`, `sidebar-muted`, `sidebar-rail`, `sidebar-focus`, `sidebar-strong`, `sidebar-hover`, `sidebar-active` | Tenant sidebar and drawer only. |
| Surfaces and text | `canvas` | Page background. |
| | `surface` | Panels, cards, topbar, inputs. |
| | `surface-muted` | Table headers, hover, insets, read-only inputs. |
| | `line` | Hairlines and panel borders. |
| | `line-strong` | Control borders (at least 3:1). |
| | `fg` | Main text. |
| | `fg-muted` | Secondary text, labels, help. |
| Status | `status-{success,warning,danger,info,neutral}` + `-soft`, `-strong` | Meaning only: badges, alerts, stat markers, danger buttons. Text on a soft background uses `-strong`. |
| Charts | `chart-1` … `chart-6` | Series identity only, fixed order, never cycled, never a status. Slots 3, 5 and 6 are below 3:1 on the surface: label them directly or add a table view. |

### Typography

One family (`font-sans`, from the brand). Weights 400, 500 (`font-medium`) and 600 (`font-semibold`) only.

| Token | Size / line height | Use |
| --- | --- | --- |
| `text-page-title` | 22/28 | Page title (with `font-semibold tracking-tight`) |
| `text-section-title` | 16/24 | Section and card titles |
| `text-body` | 14/20 | Default text, inputs, table cells |
| `text-small` | 13/18 | Labels, menu items, secondary lines |
| `text-caption` | 12/16 | Help, metadata, table headers, badges |

Amounts, dates, mileage, codes and plates use `tabular-nums`.

### Radius, elevation, spacing

- **Radius.** `rounded-control` (6 px) for inputs, buttons and insets. `rounded-panel` (10 px) for cards, tables, alerts and popovers. `rounded-full` for badges and avatars.
- **Elevation.** `shadow-overlay` is for overlays only.
- **Spacing.** Tailwind's 4 px scale.
  - Page: `admin-main` (`px-4 py-6 lg:px-8`).
  - Between blocks: `space-y-6`.
  - Card body: `p-5` (`sm:p-6` on forms).
  - Form grid: `grid gap-4 md:grid-cols-2`.
  - Fields never carry their own margins.
- **Controls.** 40 px high (`h-10`, `min-h-10`). 44 px inside `.admin-field-screen`.

### Motion

| Use | Duration | Easing | Reduced motion |
| --- | --- | --- | --- |
| Hover, colour change | 150 ms | ease-out | unchanged |
| Popover / dropdown in | 150 ms | `ease-out-ui`, opacity + scale 0.96 → 1 (`admin-popover-enter`) | opacity only |
| Popover out | 100 ms | ease-out, opacity | unchanged |
| Dialog | 200 ms | `ease-out-ui`, opacity + scale | opacity only |
| Drawer, bottom sheet | 240 ms | `ease-drawer`, translate | opacity, 200 ms |
| Button press | 150 ms | scale 0.96 | none |

## 4. Component catalogue

All are anonymous Blade components (`laravel/components/admin/*.blade.php`), used as `<x-admin.name>`. Every page of the gallery shows the exact Blade code under each demo.

### Actions

**`x-admin.button`**: a button, or a link when `href` is set. Gallery: `static/components/buttons.html`.
- Props:
  - `variant`: `primary` (default) | `secondary` | `ghost` | `danger`;
  - `size`: `sm` (32 px) | `md` (40 px, default) | `lg` (44 px);
  - `icon`: an icon name;
  - `type`: `button` (default) | `submit`;
  - `loading`: disables the button, sets `aria-busy` and shows a spinner.
- When to use which variant:
  - One `primary` per area: the main action of a page header, a form or a dialog.
  - `secondary` for the others.
  - `ghost` for low-emphasis inline actions (a section's "Voir la fiche").
  - `danger` for destructive actions only.
- Do not use green or amber buttons.
- Accessibility: visible text is the accessible name. A `disabled` button needs a visible reason nearby, or use `x-admin.delete-action`.

**`x-admin.icon-button`**: a square icon-only button or link. Gallery: `static/components/buttons.html`.
- Props: `icon` and `label` (required: it is the `aria-label` and the tooltip), `size` (`md` 40 px | `sm` 32 px), `type`, `href`.
- Use for repeated row actions and toolbar actions. Never use it without a label.

**`x-admin.delete-action`**: deletes through a DELETE form after a browser confirmation, or shows why deletion is refused. Gallery: `static/components/delete-action.html`.
- Props: `action` (url), `confirm` (question), `refusal` (the reason it is impossible; renders a disabled button described by the reason), `label`.

### Feedback

**`x-admin.alert`**: an inline message. Gallery: `static/components/alerts.html`.
- Props:
  - `type`: `success` | `error` (alias `danger`) | `warning` | `info`;
  - `message` (or the slot, which accepts markup);
  - `dismissible`: `true` by default.
- `role="alert"` for errors, `role="status"` otherwise. Use `:dismissible="false"` for states the user must keep seeing (locked record, access notice).

**`x-messages`**: flash messages from the session keys `success`, `status`, `error` and `warning`. The layouts already render it. Gallery: `static/components/alerts.html`.

**`x-messages.errors`**: the validation summary above a long form. Fields still show their own errors.

**`x-admin.empty-state`**: an empty list or panel. Gallery: `static/components/empty-state.html`.
- Props: `title` (required), `description`, `icon`; the slot holds the actions.
- Say what is missing and offer the next action. Distinguish "nothing yet" from "no result for these filters".

### Status

**`x-admin.status-badge`**: a status pill. Gallery: `static/components/badges.html`.
- Props: `status` (an enum implementing `App\Support\UiKit\HasStatusBadge`), or `variant` (`neutral` | `success` | `warning` | `danger` | `info`) with `label`.
- Always pass the enum when one exists, so the mapping lives in one place (section 5).

**`x-admin.badge`**: a generic label on the same styles, for things that are not statuses (a type, a tag, a count). Prop: `variant`.

### Data display

**`x-admin.stats-card`** and **`x-admin.stats-grid`**: key figures. Gallery: `static/components/stats.html`.
- Card props:
  - `title`, `value`;
  - `variant`: `primary` (brand, default) | `success` | `warning` | `danger` | `info` | `neutral`;
  - `icon`: an icon name;
  - `href`: the whole card becomes a link;
  - `selected`: the active filter, with `aria-current`;
  - `button-text`: a screen-reader description of the link.
- Grid: counts fit up to 4 per row; with `amounts`, at most 3 per row (amounts never wrap).

**`x-admin.table`**: a data table in a bordered panel. Gallery: `static/components/table.html`.
- Prop: `headers` (an array), or `<th>` cells in the slot with an empty array.
- Cells never wrap; the table scrolls inside its panel.
- Use `x-admin.status-badge`, `x-admin.amount`, `x-admin.date` and `x-admin.plate` in cells.
- Row actions are small icon buttons at the end (`text-end`).
- An empty table shows one full-width row; an empty list shows `x-admin.empty-state`.

**Display formats** (`x-admin.amount`, `x-admin.date`, `x-admin.plate`). Gallery: `static/foundations/formatting.html`.
- `x-admin.amount`: `value` (major units) or `minor` (cents), and `currency` (default `Format::$currency`). Prints `1 234,56 MAD` with no-break spaces, tabular figures and no wrapping; a missing amount prints `—`.
- `x-admin.date`: `value`; `time` for "03/11/2026 à 09:30"; `long` for "3 novembre 2026". It is a `<time>` element.
- `x-admin.plate`: `value`, `fallback`. Plates keep their typed order (`bdo dir="ltr"`), even in RTL.
- `App\Support\UiKit\Format`: the same rules in PHP (`money`, `moneyMinor`, `date`, `dateTime`, `longDate`, `mileage`, `duration`).

### Page structure

**`x-admin.page-header`**: the page title. Gallery: `static/components/page-header.html`.
- Props: `title`, `description`, `eyebrow`, `breadcrumbs` (a list of `['label', 'href']`; the last one is the current page); `actions` slot.
- Once per page, first in the content.

**`x-admin.detail-page`**, **`x-admin.detail-section`**, **`x-admin.detail-item`**: the entity detail ("Encre") structure. Gallery: `static/components/detail.html`.
- `detail-page` has an optional `aside` slot for summary panels.
- `detail-section` has `title`, `description`, `headingLevel` and an `actions` slot.
- `detail-item` has `label`; place several in `<dl class="grid gap-4 sm:grid-cols-2">`.

**`x-admin.modal`**: a native `<dialog>`. Gallery: `static/components/modal.html`.
- Props: `id`, `title`, `description`; `footer` slot.
- Open with `document.getElementById(id).showModal()`. The browser traps focus, closes on Escape and returns focus to the trigger.
- Footer order: the safe choice first, then the primary or danger action.
- Short content only: a confirmation or a form of one to three fields.

### Forms

All fields share `x-admin.form.field`: a visible label, a red `*` when required, help text and the field's error, linked by `aria-describedby`. An invalid field gets `aria-invalid`. Errors come from `$errors`; previous input from `old()`.

**`x-admin.form-input`**. Gallery: `static/components/form-inputs.html`.
- Props: `name`, `label`, `type`, `value`, `required`, `placeholder`, `help`, `inputmode`, `autocomplete`, `plate` (plate typing order).
- `readonly` and `disabled` pass through as attributes.

**`x-admin.textarea`**: `name`, `label`, `value`, `rows`, `required`, `placeholder`, `help`.

**`x-admin.form-select`**: a native select. `options` (`value => label`), `value`, `placeholder` (an empty first option), `required`, `help`. Gallery: `static/components/form-choices.html`.

**`x-admin.form-select2`**: a searchable select (Alpine `adminSelect2`); the hidden input keeps the field name.
- Props: `options`, `value`, `placeholder`, `searchPlaceholder`, `emptyText`, `required`.
- Window events: `admin-select2:options`, `admin-select2:value`, `admin-select2:change`.

**`x-admin.segmented-radio`**: two to five exclusive choices as native radios in a fieldset; arrow keys move the choice. Props: `options`, `value`, `required`, `help`.

**Checkbox and switch**: `admin-checkbox` on a native checkbox, inside its label. The switch is a `peer sr-only` checkbox followed by `<span class="admin-switch">`, all inside a `<label>`.

**`x-admin.date-picker`**: an Alpine date, date-time or time picker. Gallery: `static/components/date-picker.html`.
- Props: `name`, `label`, `value`, `withTime`, `timeOnly`, `minDate`, `maxDate`, `minYear`, `placeholder`, `required`, `native`.
- `native` uses the device input below 768 px, for phone screens.
- The hidden input posts `Y-m-d`, `Y-m-d H:i` or `H:i`.
- Needs `layouts/date_picker_js.blade.php` (included by the tenant layout).

**`x-admin.file-upload`**: a drop zone. Gallery: `static/components/file-upload.html`.
- Props: `name`, `label`, `accept`, `multiple`, `maxSize`, `hint`, `help`, `required`, `preview` + `previewUrl`, `error`.
- One per page: its preview script uses the first preview area.

**`x-admin.setting-field`**: one settings field. Gallery: `static/components/setting-field.html`.
- `type`: `text`, `email`, `password`, `url`, `number`, `time`, `textarea`, `select`, `file` (current image and a live preview), or `toggle`.
- Other props: `name`, `label`, `value`, `help`, `required`, `options`, `previewUrl`, `accept`.

### Navigation and shell

**`x-admin.nav-link`**: a sidebar link. Gallery: `static/components/navigation.html`.
- Props: `active` (sets `aria-current="page"` and the rail), `icon`.

The sidebar groups (`adminNavGroups`), the light platform navigation, the section tabs and the tab buttons are documented on the same page.

**`x-admin.icon`**: one of the 51 inline Heroicons (`static/foundations/icons.html`). Decorative by default; pass `label` only when the icon alone carries meaning.

**`x-admin.guest-heading`**: the logo, eyebrow, title and description of guest pages. Props: `title`, `eyebrow`, `description`, `logo`.

### Domain components (optional, vehicle rental)

Keep these only in projects that need them. Gallery: `static/domain/vehicle-condition.html`.

- **`x-admin.vehicle-condition-map`**: damage zones at check-out and check-in.
  - Props: `name` (posts `name[]`), `label`, `value` (preselected zones), `help`, `variant` (`checkout` | `checkin`).
  - Pointer users tap the top view; everyone has labelled toggle buttons (`aria-pressed`).
- **`x-admin.vehicle-top-view`**: the clickable top view, inside an Alpine scope that provides `toggle(key)` and `isSelected(key)`. Needs `theme/images/vehicle-condition-top-view.png`.
- **Plate input**: `x-admin.form-input` with `plate`.

### CSS-only patterns (no Blade component)

| Class | Use | Gallery |
| --- | --- | --- |
| `admin-card`, `admin-card-header`, `admin-card-body`, `admin-card-soft`, `admin-inset` | Panels and insets | `static/patterns/surfaces.html` |
| `admin-filter-card` (+ `is-selected`) | A card that is a link or a choice | `static/patterns/surfaces.html` |
| `admin-popover`, `admin-popover-scroll`, `admin-popover-enter`, `admin-menu-item`, `admin-menu-item-danger` | Dropdown menus and popovers | `static/patterns/menus.html` |
| `admin-dialog`, `admin-drawer`, `admin-sheet` | Native-dialog overlays | `static/components/modal.html`, `static/patterns/overlays.html` |
| `admin-switch`, `admin-checkbox`, `admin-segmented` | Choices | `static/components/form-choices.html` |
| `admin-spinner` | Busy indicator (inside a loading button) | `static/components/buttons.html` |
| `admin-field-screen`, `admin-action-bar` | Phone-first screens | `static/layouts/tenant-field.html` |
| `admin-bottom-bar`, `admin-bottom-bar-item` | Phone shortcuts | `static/layouts/tenant.html` |

### Layouts

| Layout | Blade | Gallery |
| --- | --- | --- |
| Tenant / back office | `laravel/layouts/admin.blade.php` | `static/layouts/tenant.html` (owner), `tenant-restricted.html` (permission-hidden), `tenant-field.html` (phone field screen) |
| Platform (super admin) | `laravel/layouts/platform.blade.php` | `static/layouts/platform.html` |
| Guest (sign-in, onboarding) | `laravel/layouts/guest.blade.php` | `static/layouts/guest.html` |

Layouts make no permission decision. Pass only what the user may see:
- `$navigation`, `$bottomBar`, `$contextSwitcher`;
- `$headerNotifications`, `$accountMenu`, `$logoutAction`.

The data shapes are documented at the top of each layout file.

## 5. Status badges: the single mapping

| Variant | Meaning | Examples (labels from the source app) |
| --- | --- | --- |
| `danger` | Wrong, failed, or act now | Paiement échoué, abonnement impayé (past due), dommage majeur, retour en retard |
| `warning` | Needs attention soon; incomplete | Réservation en attente, facture partiellement réglée, paiement en attente, dommage ou incident ouvert, dommage modéré, caution partiellement utilisée ou libérée, clôture à préparer |
| `success` | Done, paid, available | Réservation terminée, facture réglée, paiement encaissé, devis accepté, véhicule disponible, abonnement actif, dommage résolu, incident clôturé, clôture finalisée |
| `info` | Scheduled, in progress or informational | Réservation confirmée ou en cours, facture émise, devis envoyé, entretien planifié, caution sécurisée, période d'essai |
| `neutral` | Inactive, cancelled, draft, closed without action | Brouillon, annulé, refusé, expiré, remboursé, véhicule non disponible, caution libérée, dommage mineur |

Rules:
- Never use the accent colour for a status.
- A status enum implements `HasStatusBadge` (`label()`, `badgeVariant()`).
- Views always pass the enum, never a variant chosen in the view.

## 6. Page patterns (Phase 5)

The list, detail, form, wizard, dashboard, field, settings, empty, error, destructive-confirmation, modal and dropdown patterns will be documented here with their screen paths after the screen export (Phases 3 and 4). Until then, use:
- **List:** a page header with the create action, a stats grid or filter cards, `x-admin.table`, and `x-admin.empty-state` (`static/layouts/tenant.html`, `static/components/table.html`).
- **Detail:** a page header with breadcrumbs, status and actions, then `x-admin.detail-page` (`static/components/detail.html`).
- **Form:** the card form of `static/components/form-inputs.html` ("Form layout"): a fields grid, a hairline, then the actions (secondary first, primary last; reversed on phones).
- **Field (phone) screen:** `static/layouts/tenant-field.html`.
- **Settings:** section tabs (`static/components/navigation.html`), then setting fields.

## 7. Responsive rules

- Check every page at 360, 768 and 1440 px.
- No horizontal page scroll. Tables scroll inside their panel; tab rows scroll inside their row.
- Give grid and flex children `min-w-0`.
- **Sidebar.**
  - From 1024 px (`lg`): visible.
  - Below 1024 px: a drawer, opened from the topbar.
  - Below 768 px: the context switcher becomes a bottom sheet.
- **Phone bottom bar** (below 768 px): at most four shortcuts plus Menu. Screens with their own sticky actions hide it with `@section('hide-bottom-bar', '1')`.
- **Field screens.** Check-out, check-in, damage capture, today's departures and returns, and vehicle lookup are phone-first.
  - Wrap them in `.admin-field-screen` for 44 px controls and targets.
  - Put the actions in `.admin-action-bar` (sticky, above the home indicator).
  - Use `native` date pickers.
- Amount grids never wrap a figure: at most 3 per row.

## 8. Accessibility rules (WCAG 2.2 AA)

- **Contrast.** Text uses `fg` or `fg-muted` on `surface` or `canvas`; text on soft status backgrounds uses `-strong`. Control borders use `line-strong` (at least 3:1).
- **Focus.** Visible focus everywhere: the kit classes carry `focus-visible:outline-2 outline-focus`. Never remove an outline.
- **Keyboard.** Everything works with the keyboard:
  - menus close on Escape and return focus;
  - the searchable select and the date picker document their keys;
  - segmented radios use arrow keys.
- **Overlays.** Use native `<dialog>` with `showModal()`: the browser traps focus, closes on Escape and returns focus. Popovers set `aria-haspopup` and `aria-expanded`.
- **Labels.**
  - Every field has a visible label.
  - Icon-only buttons have a label (`x-admin.icon-button`).
  - Errors use `role="alert"` and are linked by `aria-describedby`.
- **Current state.** Use `aria-current="page"` for the current link or tab, and `aria-current="true"` for the current choice.
- **Never colour alone.** Statuses have a text label. Selected zones are pressed buttons with labels. Charts use direct labels or a table view.
- **Reduced motion.** Every transition has a fallback (section 3, Motion).

## 9. Motion rules

Motion is minimal and functional: feedback, state changes, opening and closing panels, dropdowns and dialogs.
- No decorative animation, no animated page sections, no delays on repeated actions.
- Use Alpine `x-transition` with the kit classes, or CSS transitions on the listed properties. No animation library.
- Respect `prefers-reduced-motion` (`motion-safe:` utilities, or the kit's media queries).

## 10. Content rules

- **Language.** The copy language is project-specific. The source app is French; keep one language per interface and translate every visible string (labels, buttons, placeholders, empty states, errors, confirmations). Code, comments and docs are English.
- **Tone.** Short, factual, operational:
  - buttons are verbs ("Enregistrer", "Confirmer le départ");
  - an error says what is wrong and how to fix it;
  - an empty state says what is missing and the next step.
- **Formats.** Amounts, dates and plates always go through `x-admin.amount`, `x-admin.date`, `x-admin.plate` or `App\Support\UiKit\Format`. Input values keep their submitted format.
- **Identifiers.** Codes (RES-2026-0148), phone numbers, mileage and plates use `tabular-nums`. Plates are never reordered.
- **Placeholders.** In the static gallery, `{{ brand }}` is the product name (filled from `brand.json`). In Blade, use `config('brand.name')`.

## 11. Logical properties (RTL-ready)

Use logical utilities only:
- `ms-`, `me-`, `ps-`, `pe-` for margin and padding;
- `start-`, `end-` for position;
- `text-start`, `text-end` for alignment;
- `border-s`, `border-e`, `rounded-s` and the like for borders and radius.

Directional icons in breadcrumbs flip with `rtl:rotate-180`. The drawer slides from the end edge in RTL (built in). Plates stay LTR through `x-admin.plate`.

## 12. Do / Don't

| Do | Don't |
| --- | --- |
| `<x-admin.button>Enregistrer</x-admin.button>` | `<button class="bg-violet-600 text-white">` |
| `text-fg-muted`, `bg-surface-muted` | `text-gray-500`, `bg-slate-50` |
| `<x-admin.status-badge :status="$invoice->status" />` | A badge whose colour is chosen in the view |
| One primary action per area | Several violet buttons side by side |
| `<x-admin.amount :value="$total" />` | `{{ number_format($total, 2) }} MAD` |
| `gap-4` on the form grid | `mb-4` on each field |
| `ms-2`, `text-start` | `ml-2`, `text-left` |
| A hairline-sectioned detail page | Stacked shadowed cards |
| `<x-admin.icon-button icon="trash" label="Supprimer" />` | An icon button without a label |
| A disabled delete with its reason (`refusal`) | Hiding an action the user expects |

### Do not use (removed from the kit)

| Removed | Replacement |
| --- | --- |
| Button variants `outline`, `success`, `warning` (`admin-button-outline`, `-success`, `-warning`) | `secondary` for outline. Never green or amber buttons: use `primary`, `secondary` or `danger`, and show the status with a badge. |
| `x-admin.badge variant="primary"` | `neutral` (badges are never in the accent colour) |
| `x-admin.stats-card color="…"` (palette names such as blue, green, red) | `variant`: `primary`, `success`, `warning`, `danger`, `info`, `neutral` |
| `x-admin.stats-card` raw markup in the `icon` slot or the default slot | `icon="name"` (an `x-admin.icon` name) |
| `legacy-margin` on form components (an `mb-4` on the field) | Spacing on the parent: `grid gap-4` or `space-y-4` |
| Colour aliases `nidam-navy-deep`, `nidam-navy`, `nidam-navy-light`, `nidam-emerald`, `nidam-emerald-light`, `nidam-mint` | The closest semantic token, chosen by role: `brand-deep` (same value), `brand` (same value), `accent-strong`, `accent`, `sidebar-rail` (same value), `accent-soft` |
| The source app's global script (table-row hover adding `bg-gray-50`, English "Saving..." button text, slug auto-fill) | Not part of the kit: hovers come from the kit CSS; loading uses `x-admin.button loading` |

## 13. Snippet for a project's CLAUDE.md / AGENTS.md (draft, final in Phase 5)

```markdown
## UI
All UI work follows `ui-kit/REFERENCE.md`. Use only the kit's Blade components (`x-admin.*`),
layouts and token utilities; copy the closest page of `ui-kit/static/` (open
`ui-kit/static/index.html`). No raw Tailwind palette colours, arbitrary values, hex colours or
font-bold. Brand values live in `ui-kit/brand/brand.json` only.
```

## Screens not in this version

These screens of the source app were not yet migrated to the kit when the kit was exported, so they are excluded: legacy styling never enters the kit. They will be added in a v1.1 export after the app migrates them. Until then, build these needs from the closest kit pattern listed.

Tenant (19):

| Source view | Closest kit pattern |
| --- | --- |
| `resources/views/admin/invitations/show.blade.php` | Guest shell with a card form (`static/layouts/guest.html`) |
| `resources/views/admin/modules/rental-deposits/index.blade.php` (+ `partials/reservation-deposit`) | List: stats grid + table + status badges (`static/layouts/tenant.html`) |
| `resources/views/admin/modules/rental-settlement/show.blade.php` | Detail page with an aside of amounts (`static/components/detail.html`) |
| `resources/views/admin/modules/rental-settlement/charges/edit.blade.php` | Card form (`static/components/form-inputs.html`, "Form layout") |
| `resources/views/admin/modules/reporting/index.blade.php` | Dashboard: stats grids (counts and amounts) + tables (`static/components/stats.html`) |
| `resources/views/admin/modules/vehicle-acquisition/show.blade.php` | Detail page (`static/components/detail.html`) |
| `resources/views/admin/modules/vehicle-disposal/create.blade.php` (+ `_form`) | Card form |
| `resources/views/admin/modules/vehicle-disposal/edit.blade.php` | Card form |
| `resources/views/admin/modules/vehicle-documents/index.blade.php` | List with status badges and empty state |
| `resources/views/admin/modules/vehicle-documents/form.blade.php` | Card form + file upload (`static/components/file-upload.html`) |
| `resources/views/admin/modules/vehicle-economics/show.blade.php` | Detail page + amounts stats grid |
| `resources/views/admin/modules/vehicle-expenses/index.blade.php` | List (table + amounts) |
| `resources/views/admin/modules/vehicle-expenses/create.blade.php` | Card form |
| `resources/views/admin/modules/vehicle-expenses/edit.blade.php` | Card form |
| `resources/views/admin/modules/vehicle-financing/section.blade.php` | Detail section with label/value grid |
| `resources/views/admin/reservations/extension.blade.php` | Card form + confirmation dialog (`static/components/modal.html`) |
| `resources/views/admin/reservations/replacement.blade.php` | Phone field screen (`static/layouts/tenant-field.html`) |
| `resources/views/admin/search/index.blade.php` | List results + empty state "no result" (`static/components/empty-state.html`) |
| `resources/views/admin/subscription/show.blade.php` | Detail page + stats cards |

Platform (11):

| Source view | Closest kit pattern |
| --- | --- |
| `resources/views/platform/access-control/permissions/index.blade.php` | List (table) in the platform shell |
| `resources/views/platform/access-control/permissions/matrix.blade.php` | Table with checkboxes (`admin-checkbox`) |
| `resources/views/platform/access-control/permissions/show.blade.php` | Detail page |
| `resources/views/platform/access-control/roles/index.blade.php` | List (table) |
| `resources/views/platform/access-control/roles/show.blade.php` | Detail page |
| `resources/views/platform/catalogs/brands/index.blade.php` | List + tab buttons (`static/components/navigation.html`) |
| `resources/views/platform/catalogs/cities/index.blade.php` | List + tab buttons |
| `resources/views/platform/catalogs/countries/index.blade.php` | List + tab buttons |
| `resources/views/platform/catalogs/vehicle-categories/index.blade.php` | List + tab buttons |
| `resources/views/platform/organisations/show.blade.php` | Detail page with aside |
| `resources/views/platform/plans/index.blade.php` | List with status badges |

Not exported (dead code in the source app, never rendered): `admin/notifications/{inbox,send}`, `admin/profile/{business-info,edit-business-info}`, `admin/users/partials/business-info`, `auth/passwords/{app-reset,app-reset-success}`.

**Error pages:** only the 403 page has a custom design in the source app. The 404, 419, 500 and 503 pages have no custom design yet; they will be added after the app gets them.
