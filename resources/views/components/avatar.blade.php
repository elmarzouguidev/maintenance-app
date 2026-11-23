{{-- Avatar (Skote .avatar-* + .avatar-title). Shows `src` as an image, otherwise the slot (initials or icon). --}}
@props([
    'size' => 'sm',
    'variant' => 'primary',
    'soft' => false,
    'square' => false,
    'src' => null,
    'alt' => '',
])

@php
    $sizes = [
        'xs' => 'size-8 text-13',
        'sm' => 'size-12 text-16',
        'md' => 'size-18 text-20',
        'lg' => 'size-24 text-24',
        'xl' => 'size-30 text-24',
    ];

    $solid = [
        'primary' => 'bg-primary text-white',
        'secondary' => 'bg-secondary text-white',
        'success' => 'bg-success text-white',
        'danger' => 'bg-danger text-white',
        'warning' => 'bg-warning text-white',
        'info' => 'bg-info text-white',
        'light' => 'bg-light text-primary',
        'dark' => 'bg-dark text-white',
    ];

    $softened = [
        'primary' => 'bg-primary/25 text-primary',
        'secondary' => 'bg-secondary/25 text-secondary',
        'success' => 'bg-success/25 text-success',
        'danger' => 'bg-danger/25 text-danger',
        'warning' => 'bg-warning/25 text-warning',
        'info' => 'bg-info/25 text-info',
        'light' => 'bg-light/25 text-light',
        'dark' => 'bg-dark/25 text-dark',
    ];

    $shape = $square ? 'rounded-sm' : 'rounded-full';
@endphp

@if ($src)
    <img src="{{ $src }}" alt="{{ $alt }}" {{ $attributes->class([$sizes[$size] ?? $sizes['sm'], $shape, 'shrink-0 object-cover']) }}>
@else
    <span {{ $attributes->class([
        $sizes[$size] ?? $sizes['sm'],
        $shape,
        ($soft ? $softened : $solid)[$variant] ?? $solid['primary'],
        'inline-flex shrink-0 items-center justify-center font-medium',
    ]) }}>{{ $slot }}</span>
@endif
