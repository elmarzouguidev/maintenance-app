{{--
    Button or link (with href). Variants: primary (the one accent-coloured action), secondary,
    ghost, danger. loading disables the button, sets aria-busy and shows a spinner.
    href stays in the attribute bag: Blade has already escaped href="{{ … }}" once, so
    echoing it again would break query strings.
--}}
@props(['type' => 'button', 'variant' => 'primary', 'size' => 'md', 'icon' => null, 'loading' => false])

@php
    $variants = [
        'primary' => 'admin-button-primary',
        'secondary' => 'admin-button-secondary',
        'ghost' => 'admin-button-ghost',
        'danger' => 'admin-button-danger',
    ];

    $sizes = [
        'sm' => 'admin-button-sm',
        'md' => 'admin-button-md',
        'lg' => 'admin-button-lg',
    ];

    $classes = implode(' ', [
        'admin-button',
        $variants[$variant] ?? $variants['primary'],
        $sizes[$size] ?? $sizes['md'],
    ]);
    $iconSize = $size === 'sm' ? 'size-4' : 'size-5';
@endphp

@if (filled($attributes->get('href')))
    <a {{ $attributes->merge(['class' => $classes]) }}>
        @if ($icon)
            <x-admin.icon :name="$icon" :class="$iconSize.' -ms-0.5'" />
        @endif
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" @if ($loading) disabled aria-busy="true" @endif {{ $attributes->except('href')->merge(['class' => $classes]) }}>
        @if ($loading)
            <span class="admin-spinner" aria-hidden="true"></span>
        @elseif ($icon)
            <x-admin.icon :name="$icon" :class="$iconSize.' -ms-0.5'" />
        @endif
        {{ $slot }}
    </button>
@endif
