{{-- Alert (Bootstrap .alert). `dismissible` adds a close button (Alpine). --}}
@props([
    'variant' => 'primary',
    'dismissible' => false,
    'icon' => null,
])

@php
    $variants = [
        'primary' => 'border-primary/30 bg-primary/20 text-primary-emphasis',
        'secondary' => 'border-secondary/30 bg-secondary/20 text-secondary-emphasis',
        'success' => 'border-success/30 bg-success/20 text-success-emphasis',
        'danger' => 'border-danger/30 bg-danger/20 text-danger-emphasis',
        'warning' => 'border-warning/30 bg-warning/20 text-warning-emphasis',
        'info' => 'border-info/30 bg-info/20 text-info-emphasis',
    ];

    $classes = [
        'relative flex items-start gap-2 rounded-sm border px-5 py-3',
        $variants[$variant] ?? $variants['primary'],
        $dismissible ? 'pe-12' : '',
    ];
@endphp

<div
    role="alert"
    @if ($dismissible) x-data="{ show: true }" x-show="show" x-transition.opacity @endif
    {{ $attributes->class($classes) }}
>
    @if ($icon)
        <i class="{{ $icon }} mt-px text-16 leading-none" aria-hidden="true"></i>
    @endif

    <div class="min-w-0 flex-1">{{ $slot }}</div>

    @if ($dismissible)
        <button type="button" class="absolute end-0 top-0 px-4 py-3 opacity-50 transition-opacity hover:opacity-100" x-on:click="show = false" aria-label="Fermer">
            <i class="mdi mdi-close text-16" aria-hidden="true"></i>
        </button>
    @endif
</div>
