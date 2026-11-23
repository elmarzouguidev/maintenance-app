{{-- Badge (Bootstrap .badge, Skote .badge-soft-*). --}}
@props([
    'variant' => 'primary',
    'soft' => false,
    'pill' => false,
])

@php
    $solid = [
        'primary' => 'bg-primary text-white',
        'secondary' => 'bg-secondary text-white',
        'success' => 'bg-success text-white',
        'danger' => 'bg-danger text-white',
        'warning' => 'bg-warning text-white',
        'info' => 'bg-info text-white',
        'light' => 'bg-light text-dark',
        'dark' => 'bg-dark text-white',
        'pink' => 'bg-pink text-white',
    ];

    $softened = [
        'primary' => 'bg-primary/18 text-primary',
        'secondary' => 'bg-secondary/18 text-secondary',
        'success' => 'bg-success/18 text-success',
        'danger' => 'bg-danger/18 text-danger',
        'warning' => 'bg-warning/18 text-warning',
        'info' => 'bg-info/18 text-info',
        'light' => 'bg-light/18 text-light',
        'dark' => 'bg-dark/18 text-dark',
        'pink' => 'bg-pink/18 text-pink',
    ];

    $classes = [
        'inline-block py-[0.25em] text-center align-baseline text-[75%] font-medium leading-none whitespace-nowrap',
        $pill ? 'rounded-full px-[0.6em]' : 'rounded-sm px-[0.4em]',
        ($soft ? $softened : $solid)[$variant] ?? $solid['primary'],
    ];
@endphp

<span {{ $attributes->class($classes) }}>{{ $slot }}</span>
