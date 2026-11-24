@props(['icon', 'label', 'type' => 'button', 'size' => 'md'])

{{--
    Icon-only button. The label is required: it is the accessible name (aria-label) and the
    tooltip. href renders a link; it stays in the attribute bag so it is escaped once.
--}}
@php
    $classes = 'admin-icon-button'.($size === 'sm' ? ' admin-icon-button-sm' : '');
    $iconSize = $size === 'sm' ? 'size-4' : 'size-5';
@endphp

@if (filled($attributes->get('href')))
    <a aria-label="{{ $label }}" title="{{ $label }}" {{ $attributes->class([$classes]) }}>
        <x-admin.icon :name="$icon" :class="$iconSize" />
    </a>
@else
    <button type="{{ $type }}" aria-label="{{ $label }}" title="{{ $label }}" {{ $attributes->except('href')->class([$classes]) }}>
        <x-admin.icon :name="$icon" :class="$iconSize" />
    </button>
@endif
