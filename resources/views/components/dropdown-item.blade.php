{{-- Dropdown item (Bootstrap .dropdown-item). A link when `href` is given, otherwise a button (type="button" by default). --}}
@props([
    'href' => null,
    'icon' => null,
    'active' => false,
    'danger' => false,
    'type' => 'button',
])

@php
    $classes = [
        'flex w-full items-center gap-1 whitespace-nowrap px-6 py-[0.35rem] text-start no-underline transition-colors focus:outline-none',
        $active
            ? 'bg-primary text-white'
            : ($danger ? 'text-danger hover:bg-hover focus:bg-hover' : 'text-[#212529] hover:bg-hover focus:bg-hover'),
    ];
@endphp

@if ($href !== null)
    <a href="{{ $href }}" role="menuitem" @if ($active) aria-current="true" @endif {{ $attributes->class($classes) }}>
        @if ($icon)<i class="{{ $icon }} me-1 align-middle text-16" aria-hidden="true"></i>@endif
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" role="menuitem" {{ $attributes->class($classes) }}>
        @if ($icon)<i class="{{ $icon }} me-1 align-middle text-16" aria-hidden="true"></i>@endif
        {{ $slot }}
    </button>
@endif
