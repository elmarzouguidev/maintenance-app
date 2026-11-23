{{-- Tab panel for <x-tabs> (Bootstrap .tab-pane). Hidden server-side when not active, so there is no flash before Alpine starts. --}}
@aware([
    'active' => null,
])

@props([
    'name',
])

<div
    role="tabpanel"
    x-show="active === @js($name)"
    @if ($active !== $name) style="display: none" @endif
    {{ $attributes }}
>
    {{ $slot }}
</div>
