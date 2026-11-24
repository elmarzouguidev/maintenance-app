# Installing the kit in a Laravel project

For Laravel 11 or later with Vite, Tailwind CSS v4 (`@tailwindcss/vite`) and Alpine.js 3. Paths below assume `ui-kit/` sits at the project root.

## 1. Brand

1. Edit `ui-kit/brand/brand.json` (`../BRANDING.md`), then build: `cd ui-kit && npm install && npm run build`.
2. Copy the config: `cp ui-kit/brand/brand.php config/brand.php`.
3. Publish the brand files and the kit images:

   ```bash
   mkdir -p public/vendor/ui-kit/brand
   cp ui-kit/brand/*.svg public/vendor/ui-kit/brand/
   cp -r ui-kit/theme/images public/vendor/ui-kit/images   # only for the vehicle domain components
   ```

Repeat steps 2 and 3 after every rebrand, or symlink them.

## 2. CSS

In `resources/css/app.css`:

```css
@import "tailwindcss";
@import "../../ui-kit/theme/theme.css";
```

- `theme.css` brings the full token set, the brand tokens (`brand/brand.css`, with the `@font-face` rules) and the `admin-*` component classes.
- Tailwind finds the classes in `resources/views` automatically.
- Vite resolves the font URLs relative to `ui-kit/brand/`; nothing to copy.
- If you keep the Blade components inside `ui-kit/` instead of copying them, add `@source "../../ui-kit/laravel";`.

## 3. JavaScript (Alpine)

In `resources/js/app.js`:

```js
import Alpine from 'alpinejs';
import { registerUiKit } from '../../ui-kit/laravel/js/index.js';

registerUiKit(Alpine);   // adminNavGroups, adminSelect2
window.Alpine = Alpine;
Alpine.start();
```

The date picker, the file upload and the vehicle condition map carry their own scripts:
- the date picker's script is `layouts/date_picker_js.blade.php`, included by the tenant layout. Include it yourself if another layout uses `x-admin.date-picker`;
- the other two ship their scripts inside the component.

`vite.config.js` keeps its usual inputs: `resources/css/app.css` and `resources/js/app.js` (the kit layouts call `@vite` with these two).

## 4. Blade components, layouts and support classes

```bash
mkdir -p resources/views/components resources/views/layouts app/Support/UiKit
cp -r ui-kit/laravel/components/admin ui-kit/laravel/components/messages resources/views/components/
cp -r ui-kit/laravel/layouts/. resources/views/layouts/
cp ui-kit/laravel/support/*.php app/Support/UiKit/
```

- The components are anonymous: no registration. Use them as `<x-admin.button>`, `<x-messages />` and so on.
- Skip `vehicle-condition-map.blade.php` and `vehicle-top-view.blade.php` unless the project needs them (optional domain components).
- Support classes (namespace `App\Support\UiKit`):
  - `Format`: amounts, dates, mileage and durations; used by `x-admin.amount` and `x-admin.date`;
  - `BadgeVariant` and `HasStatusBadge`: used by `x-admin.status-badge`. Implement `HasStatusBadge` on your status enums.

## 5. Feed the layouts

The layouts make no permission or tenancy decision. They read plain view data; share it from a view composer (for example in `AppServiceProvider::boot()`):

```php
use Illuminate\Support\Facades\View;

View::composer('layouts.admin', function ($view): void {
    $view->with([
        'navigation' => [
            ['label' => null, 'items' => [
                ['href' => route('dashboard'), 'label' => 'Tableau de bord', 'icon' => 'home', 'active' => request()->routeIs('dashboard')],
            ]],
            ['label' => 'Exploitation', 'items' => array_values(array_filter([
                auth()->user()->can('view-orders')
                    ? ['href' => route('orders.index'), 'label' => 'Commandes', 'icon' => 'document', 'active' => request()->routeIs('orders.*')]
                    : null,
            ]))],
        ],
        'bottomBar' => [],          // phone shortcuts: ['href', 'label', 'icon', 'current']
        'contextSwitcher' => null,  // or the organisation / agency choices (see the layout header)
        'searchAction' => null,     // url of a GET search, or null
        'headerNotifications' => [],// ['title', 'body', 'time', 'unread', 'markReadUrl']
        'accountMenu' => [['href' => route('profile.edit'), 'label' => 'Profil', 'icon' => 'user']],
        'logoutAction' => route('logout'),
    ]);
});
```

Every key is optional. The full shapes are documented at the top of `layouts/admin.blade.php` and `layouts/platform.blade.php`.

A page then reads:

```blade
@extends('layouts.admin')

@section('title', 'Commandes')

@section('content')
    <x-admin.page-header title="Commandes" description="Toutes les commandes de l’agence.">
        <x-slot:actions><x-admin.button href="#" icon="plus">Nouvelle commande</x-admin.button></x-slot:actions>
    </x-admin.page-header>
    …
@endsection
```

## 6. Formats

`App\Support\UiKit\Format` defaults to French formats and the MAD currency. Change them once, in `AppServiceProvider::boot()`:

```php
use App\Support\UiKit\Format;

Format::$currency = 'EUR';
Format::$locale = 'en';
Format::$decimalSeparator = '.';
Format::$thousandsSeparator = ',';
Format::$dateFormat = 'm/d/Y';
Format::$dateTimeFormat = 'm/d/Y \a\t H:i';
Format::$longDateFormat = 'F j, Y';
```

`Format::duration()` returns French wording; adapt it if the project is in another language.

## 7. Tests

Copy `ui-kit/laravel/support/tests/FormatTest.php` to `tests/Unit/UiKit/FormatTest.php` (plain Pest, no database). It checks:
- amounts (major, minor, other currency, no currency, the configured default, the dash for missing values);
- dates (short, with time, long, missing, custom fallback);
- mileage and durations;
- the badge variants.

To keep the "kit only" rule in your own views, port `ui-kit/tools/check-tokens.mjs` (or the rules in `REFERENCE.md`, section 3) to your test suite.

## 8. Check

- Open a page with each layout at 360, 768 and 1440 px.
- Tab through the topbar menus (Escape returns focus), the drawer and a form with errors.
- Compare with the gallery (`ui-kit/static/index.html`).
