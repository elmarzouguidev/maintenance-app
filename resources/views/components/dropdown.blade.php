{{--
    Dropdown (Bootstrap .dropdown), driven by Alpine.
    <x-dropdown align="end">
        <x-slot:trigger><x-button type="button">Actions</x-button></x-slot:trigger>
        <x-dropdown-item href="…">Edit</x-dropdown-item>
    </x-dropdown>

    The menu is teleported to <body> and positioned with x-anchor, so overflow containers
    (e.g. a scrolling table) can't clip it. Inside an <x-modal>, use :teleport="false":
    the modal's focus trap makes everything outside it inert.
    `size="lg"` reproduces .dropdown-menu-lg. `keep-open` stops a click inside the menu from closing it.
--}}
@props([
    'align' => 'start',
    'size' => 'md',
    'keepOpen' => false,
    'teleport' => true,
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
    @unless ($teleport) x-on:click.outside="open = false" @endunless
    {{ $attributes->class('relative inline-block') }}
>
    <div x-ref="trigger" x-on:click="open = ! open" x-bind:aria-expanded="open" class="h-full">
        {{ $trigger }}
    </div>

    @if ($teleport)
        <template x-teleport="body">
    @endif

    <div
        x-show="open"
        x-cloak
        x-transition.opacity.duration.150ms
        @if ($align === 'end')
            x-anchor.bottom-end.offset.2="$refs.trigger"
        @else
            x-anchor.bottom-start.offset.2="$refs.trigger"
        @endif
        @if ($teleport) x-on:click.outside="if (! $refs.trigger.contains($event.target)) open = false" @endif
        @unless ($keepOpen) x-on:click="open = false" @endunless
        class="{{ $sizes[$size] ?? $sizes['md'] }} z-1060 rounded-sm bg-white py-2 text-start text-13 text-body shadow-dropdown"
        role="menu"
    >
        {{ $slot }}
    </div>

    @if ($teleport)
        </template>
    @endif
</div>
