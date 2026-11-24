{{--
    Key figure. Variants: primary (brand colour, the default), success, warning, danger, info,
    neutral. With href the whole card is a link; href stays in the attribute bag so a
    pre-escaped href="{{ … }}" is not escaped twice. icon is an x-admin.icon name.
--}}
@props([
    'title',
    'value',
    'variant' => 'primary',
    'icon' => null,
    'buttonText' => null,
    'selected' => false,
])

@php
    $variantClasses = [
        'primary' => 'admin-stat-primary',
        'success' => 'admin-stat-success',
        'warning' => 'admin-stat-warning',
        'danger' => 'admin-stat-danger',
        'info' => 'admin-stat-info',
        'neutral' => 'admin-stat-neutral',
    ];
    $cardClasses = 'admin-stat-card'.($selected ? ' is-selected' : '');
    $href = $attributes->get('href');
    $hasMarker = is_string($icon) && $icon !== '';
@endphp

@if (filled($href))
    <a {{ $attributes->class([$cardClasses]) }} @if ($selected) aria-current="page" @endif>
@else
    <div {{ $attributes->except('href')->class([$cardClasses]) }}>
@endif
    @if ($hasMarker)
        <div class="admin-stat-marker {{ $variantClasses[$variant] ?? $variantClasses['primary'] }}" aria-hidden="true">
            <x-admin.icon :name="$icon" />
        </div>
    @endif
    <dl class="min-w-0 flex-1">
        <dt class="admin-stat-title">{{ $title }}</dt>
        <dd class="admin-stat-value">{{ $value }}</dd>
    </dl>
    @if (filled($href) && $buttonText)
        <span class="sr-only">{{ $buttonText }}</span>
    @endif
@if (filled($href))
    </a>
@else
    </div>
@endif
