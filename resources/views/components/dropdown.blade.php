{{--
    Dropdown (Bootstrap .dropdown), driven by Alpine and positioned with x-anchor.
    <x-dropdown align="end">
        <x-slot:trigger><x-button type="button">Actions</x-button></x-slot:trigger>
        <x-dropdown-item href="…">Edit</x-dropdown-item>
    </x-dropdown>
    `size="lg"` reproduces .dropdown-menu-lg. `keep-open` stops a click inside the menu from closing it (forms, filters).
--}}
@props([
    'align' => 'start',
    'size' => 'md',
    'keepOpen' => false,
])

@php
    $sizes = [
        'md' => 'min-w-40',
        'lg' => 'w-80',
    ];
@endphp

<div
    x-data="{ open: false }"
    x-on:keydown.escape.window="open = false"
    x-on:click.outside="open = false"
    {{ $attributes->class('relative inline-block') }}
>
    <div x-ref="trigger" x-on:click="open = ! open" class="h-full">
        {{ $trigger }}
    </div>

    <div
        x-show="open"
        x-cloak
        x-transition.opacity.duration.150ms
        @if ($align === 'end')
            x-anchor.bottom-end.offset.2="$refs.trigger"
        @else
            x-anchor.bottom-start.offset.2="$refs.trigger"
        @endif
        @unless ($keepOpen) x-on:click="open = false" @endunless
        class="{{ $sizes[$size] ?? $sizes['md'] }} z-[1000] rounded-sm bg-white py-2 text-start text-13 text-body shadow-dropdown"
        role="menu"
    >
        {{ $slot }}
    </div>
</div>
