@extends('kit-export::gallery')

@php
    $colourGroups = [
        'Brand (brand/brand.json)' => [
            ['bg-brand', 'brand', 'Brand colour: platform chip, avatars, primary stat marker'],
            ['bg-brand-deep', 'brand-deep', 'Darkest brand shade: platform context band, overlay backdrops'],
            ['bg-accent', 'accent', 'Primary action, focus ring, selection, current item rail. Nothing else'],
            ['bg-accent-hover', 'accent-hover', 'Primary action on hover'],
            ['bg-accent-soft', 'accent-soft', 'Selected card or option background'],
            ['bg-accent-strong', 'accent-strong', 'Text on accent-soft'],
            ['bg-on-accent', 'on-accent', 'Text and icons on accent, brand and status fills'],
            ['bg-focus', 'focus', 'Focus outline'],
            ['bg-sidebar', 'sidebar', 'Tenant sidebar and drawer'],
            ['bg-sidebar-text', 'sidebar-text', 'Sidebar links'],
            ['bg-sidebar-muted', 'sidebar-muted', 'Sidebar group labels'],
            ['bg-sidebar-rail', 'sidebar-rail', 'Current-page rail'],
            ['bg-sidebar-focus', 'sidebar-focus', 'Focus outline on the sidebar'],
            ['bg-sidebar-strong', 'sidebar-strong', 'Current and hovered sidebar text'],
            ['bg-sidebar-hover', 'sidebar-hover', 'Sidebar link hover (translucent)'],
            ['bg-sidebar-active', 'sidebar-active', 'Current sidebar link (translucent)'],
        ],
        'Surfaces and text' => [
            ['bg-canvas', 'canvas', 'Page background'],
            ['bg-surface', 'surface', 'Panels, cards, topbar, inputs'],
            ['bg-surface-muted', 'surface-muted', 'Table headers, hovers, insets, read-only inputs'],
            ['bg-line', 'line', 'Hairlines and panel borders'],
            ['bg-line-strong', 'line-strong', 'Control borders (at least 3:1 on surface and canvas)'],
            ['bg-fg', 'fg', 'Main text'],
            ['bg-fg-muted', 'fg-muted', 'Secondary text, labels, help'],
        ],
        'Status (meaning only)' => [
            ['bg-status-success', 'status-success', 'Done, paid, available'],
            ['bg-status-success-soft', 'status-success-soft', 'Success badge and alert background'],
            ['bg-status-success-strong', 'status-success-strong', 'Text on success-soft'],
            ['bg-status-warning', 'status-warning', 'Attention soon'],
            ['bg-status-warning-soft', 'status-warning-soft', 'Warning badge and alert background'],
            ['bg-status-warning-strong', 'status-warning-strong', 'Text on warning-soft'],
            ['bg-status-danger', 'status-danger', 'Wrong or act now; destructive actions'],
            ['bg-status-danger-soft', 'status-danger-soft', 'Danger badge and alert background'],
            ['bg-status-danger-strong', 'status-danger-strong', 'Text on danger-soft'],
            ['bg-status-info', 'status-info', 'Scheduled or informational'],
            ['bg-status-info-soft', 'status-info-soft', 'Info badge and alert background'],
            ['bg-status-info-strong', 'status-info-strong', 'Text on info-soft'],
            ['bg-status-neutral', 'status-neutral', 'Inactive, cancelled, draft'],
            ['bg-status-neutral-soft', 'status-neutral-soft', 'Neutral badge background'],
            ['bg-status-neutral-strong', 'status-neutral-strong', 'Text on neutral-soft'],
        ],
        'Charts (series identity only, fixed order)' => [
            ['bg-chart-1', 'chart-1', 'Series 1'],
            ['bg-chart-2', 'chart-2', 'Series 2'],
            ['bg-chart-3', 'chart-3', 'Series 3 (below 3:1: label it)'],
            ['bg-chart-4', 'chart-4', 'Series 4'],
            ['bg-chart-5', 'chart-5', 'Series 5 (below 3:1: label it)'],
            ['bg-chart-6', 'chart-6', 'Series 6 (below 3:1: label it)'],
        ],
    ];
@endphp

@section('demo')
    @foreach ($colourGroups as $group => $tokens)
        <section aria-labelledby="colours-{{ $loop->index }}" class="space-y-4">
            @include('kit-export::partials.demo-heading', ['id' => 'colours-'.$loop->index, 'title' => $group, 'text' => $loop->first ? 'Utilities: bg-*, text-*, border-*, outline-*, fill-* with the token name. Raw palette colours (slate, blue…) and arbitrary values are forbidden in views.' : null])
            <ul class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($tokens as [$class, $name, $use])
                    <li class="admin-card flex min-w-0 items-center gap-3 p-3">
                        <span class="block size-10 shrink-0 rounded-control border border-line {{ $class }}" aria-hidden="true"></span>
                        <span class="min-w-0">
                            <span class="block truncate font-mono text-small font-medium text-fg">{{ $name }}</span>
                            <span class="block text-caption text-fg-muted">{{ $use }}</span>
                        </span>
                    </li>
                @endforeach
            </ul>
        </section>
    @endforeach

    <section aria-labelledby="type-title" class="space-y-4">
        @include('kit-export::partials.demo-heading', ['id' => 'type-title', 'title' => 'Type scale', 'text' => 'One font family, weights 400, 500 and 600 only: nothing heavier than font-semibold. Tabular figures for amounts, dates, mileage and plates.'])
        <div class="admin-card p-5">
{{-- snippet:type --}}
<dl class="divide-y divide-line">
    <div class="grid gap-1 py-3 sm:grid-cols-3">
        <dt class="text-caption text-fg-muted">text-page-title font-semibold · 22/28</dt>
        <dd class="text-page-title font-semibold sm:col-span-2">Réservation RES-2026-0148</dd>
    </div>
    <div class="grid gap-1 py-3 sm:grid-cols-3">
        <dt class="text-caption text-fg-muted">text-section-title font-semibold · 16/24</dt>
        <dd class="text-section-title font-semibold sm:col-span-2">Période et agences</dd>
    </div>
    <div class="grid gap-1 py-3 sm:grid-cols-3">
        <dt class="text-caption text-fg-muted">text-body · 14/20</dt>
        <dd class="text-body sm:col-span-2">Départ de l’agence Rabat Centre le 03/11/2026 à 09:30.</dd>
    </div>
    <div class="grid gap-1 py-3 sm:grid-cols-3">
        <dt class="text-caption text-fg-muted">text-small · 13/18</dt>
        <dd class="text-small sm:col-span-2">Dernière mise à jour il y a 5 minutes</dd>
    </div>
    <div class="grid gap-1 py-3 sm:grid-cols-3">
        <dt class="text-caption text-fg-muted">text-caption · 12/16</dt>
        <dd class="text-caption text-fg-muted sm:col-span-2">Champ obligatoire</dd>
    </div>
    <div class="grid gap-1 py-3 sm:grid-cols-3">
        <dt class="text-caption text-fg-muted">font-medium / font-semibold</dt>
        <dd class="text-body sm:col-span-2"><span class="font-medium">Libellé 500</span> · <span class="font-semibold">Titre 600</span> · <span class="tabular-nums">2 450,00 MAD</span></dd>
    </div>
</dl>
{{-- /snippet --}}
        </div>
        @include('kit-export::partials.snippet', ['name' => 'type'])
    </section>

    <section aria-labelledby="shape-title" class="space-y-4">
        @include('kit-export::partials.demo-heading', ['id' => 'shape-title', 'title' => 'Radius, borders and elevation', 'text' => 'Panels have a hairline border and no shadow. The overlay shadow is for menus, popovers, dialogs and sheets only. 6 px controls, 10 px panels, full for badges and avatars.'])
{{-- snippet:shape --}}
<div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
    <div class="rounded-control border border-line-strong bg-surface p-4">
        <p class="text-small font-medium">rounded-control</p>
        <p class="text-caption text-fg-muted">Inputs, buttons, insets</p>
    </div>
    <div class="rounded-panel border border-line bg-surface p-4">
        <p class="text-small font-medium">rounded-panel</p>
        <p class="text-caption text-fg-muted">Cards, tables, alerts</p>
    </div>
    <div class="rounded-panel border border-line bg-surface p-4 shadow-overlay">
        <p class="text-small font-medium">shadow-overlay</p>
        <p class="text-caption text-fg-muted">Overlays only</p>
    </div>
    <div class="flex items-center gap-3 rounded-panel border border-line bg-surface p-4">
        <span class="admin-status-badge admin-status-badge-neutral">rounded-full</span>
        <span class="flex size-8 items-center justify-center rounded-full bg-accent-soft text-caption font-semibold text-accent-strong">AB</span>
    </div>
</div>
{{-- /snippet --}}
        @include('kit-export::partials.snippet', ['name' => 'shape'])
    </section>

    <section aria-labelledby="motion-title" class="space-y-4">
        @include('kit-export::partials.demo-heading', ['id' => 'motion-title', 'title' => 'Motion', 'text' => 'Minimal and functional. Every transition has a reduced-motion fallback (opacity only, or none).'])
        <x-admin.table :headers="['Use', 'Duration', 'Easing', 'Properties', 'Reduced motion']">
            <tr><td>Hover and colour changes</td><td class="tabular-nums">150 ms</td><td>ease-out</td><td>background-color, border-color, color</td><td>unchanged</td></tr>
            <tr><td>Popover and dropdown entrance</td><td class="tabular-nums">150 ms</td><td>ease-out-ui</td><td>opacity, scale (0.96 → 1)</td><td>opacity only</td></tr>
            <tr><td>Popover exit</td><td class="tabular-nums">100 ms</td><td>ease-out</td><td>opacity</td><td>unchanged</td></tr>
            <tr><td>Dialog</td><td class="tabular-nums">200 ms</td><td>ease-out-ui</td><td>opacity, scale (0.96 → 1)</td><td>opacity only</td></tr>
            <tr><td>Drawer and bottom sheet</td><td class="tabular-nums">240 ms</td><td>ease-drawer</td><td>translate</td><td>opacity, 200 ms</td></tr>
            <tr><td>Button and icon-button press</td><td class="tabular-nums">150 ms</td><td>ease-out</td><td>scale 0.96</td><td>none</td></tr>
            <tr><td>Spinner</td><td class="tabular-nums">700 ms / turn</td><td>linear</td><td>rotate</td><td>1600 ms / turn</td></tr>
        </x-admin.table>
    </section>

    <section aria-labelledby="spacing-title" class="space-y-4">
        @include('kit-export::partials.demo-heading', ['id' => 'spacing-title', 'title' => 'Spacing and widths', 'text' => 'Tailwind’s default 4 px scale. Fields are spaced with gap or space-y on the parent, never with margins on the field.'])
        <x-admin.table :headers="['Where', 'Classes']">
            <tr><td>Page padding</td><td class="font-mono text-small">px-4 py-6 lg:px-8 (admin-main)</td></tr>
            <tr><td>Between page blocks</td><td class="font-mono text-small">space-y-6</td></tr>
            <tr><td>Card body</td><td class="font-mono text-small">p-5 (sm:p-6 on forms)</td></tr>
            <tr><td>Form grid</td><td class="font-mono text-small">grid gap-4 md:grid-cols-2</td></tr>
            <tr><td>Detail section</td><td class="font-mono text-small">py-6, hairline between sections</td></tr>
            <tr><td>Reading widths</td><td class="font-mono text-small">max-w-3xl (descriptions), max-w-4xl / 5xl (forms), max-w-7xl (wide lists)</td></tr>
            <tr><td>Controls</td><td class="font-mono text-small">h-10 (40 px); h-11 (44 px) inside admin-field-screen</td></tr>
        </x-admin.table>
    </section>
@endsection
