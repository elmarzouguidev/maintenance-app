{{--
    Tabs (Bootstrap .nav-tabs / .nav-pills + .tab-content), driven by Alpine.
    <x-tabs active="open">
        <x-slot:nav>
            <x-tab name="open">Ouverts</x-tab>
            <x-tab name="closed">Fermés</x-tab>
        </x-slot:nav>
        <x-tab-panel name="open">…</x-tab-panel>
        <x-tab-panel name="closed">…</x-tab-panel>
    </x-tabs>
    Dispatches a `tab-shown` event (detail = tab name) when the active tab changes,
    replacing Bootstrap's `shown.bs.tab` (used to recalculate DataTables columns).
--}}
@props([
    'active' => null,
    'variant' => 'tabs',
    'justified' => false,
])

<div
    x-data="{ active: @js($active) }"
    x-init="$watch('active', value => $dispatch('tab-shown', value))"
    {{ $attributes }}
>
    <div role="tablist" @class(['flex flex-wrap', 'border-b border-input' => $variant === 'tabs', 'gap-1' => $variant === 'pills'])>
        {{ $nav }}
    </div>

    {{ $slot }}
</div>
