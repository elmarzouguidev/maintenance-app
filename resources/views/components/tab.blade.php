{{-- Tab button for <x-tabs> (Bootstrap .nav-link). The active state uses data-active so it renders correctly before Alpine starts. --}}
@aware([
    'active' => null,
    'variant' => 'tabs',
    'justified' => false,
])

@props([
    'name',
])

@php
    $classes = $variant === 'pills'
        ? 'rounded-sm px-4 py-2 text-primary data-active:bg-primary data-active:text-white'
        : '-mb-px rounded-t-sm border border-transparent px-4 py-2 text-primary hover:border-x-light hover:border-t-light data-active:border-input data-active:border-b-white data-active:bg-white data-active:text-body';
@endphp

<button
    type="button"
    role="tab"
    x-on:click="active = @js($name)"
    x-bind:data-active="active === @js($name)"
    x-bind:aria-selected="active === @js($name)"
    @if ($active === $name) data-active="true" aria-selected="true" @else aria-selected="false" @endif
    {{ $attributes->class([$classes, 'inline-flex items-center gap-2 transition-colors focus:outline-none focus-visible:ring-[0.15rem] focus-visible:ring-primary/25', 'flex-1 justify-center' => $justified]) }}
>
    {{ $slot }}
</button>
