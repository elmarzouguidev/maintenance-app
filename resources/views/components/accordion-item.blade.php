{{-- Accordion item (Bootstrap .accordion-item), driven by Alpine x-collapse. Stack several inside a wrapper for an accordion. --}}
@props([
    'title',
    'open' => false,
])

<div x-data="{ open: @js((bool) $open) }" {{ $attributes->class('overflow-hidden border border-black/12 bg-white first:rounded-t-sm last:rounded-b-sm [&+&]:-mt-px') }}>
    <h2 class="mb-0">
        <button
            type="button"
            x-on:click="open = ! open"
            x-bind:aria-expanded="open"
            x-bind:data-active="open"
            @if ($open) data-active="true" aria-expanded="true" @else aria-expanded="false" @endif
            class="flex w-full items-center justify-between gap-3 px-5 py-4 text-start text-13 text-body transition-colors data-active:bg-[#eef1fd] data-active:text-[#4d63cf] data-active:shadow-[inset_0_-1px_0_rgba(0,0,0,0.125)]"
        >
            <span>{{ $title }}</span>
            <i class="mdi mdi-chevron-down text-16 leading-none transition-transform" x-bind:class="open && 'rotate-180'" aria-hidden="true"></i>
        </button>
    </h2>

    <div x-show="open" x-collapse @unless ($open) style="display: none" @endunless>
        <div class="px-5 py-4">{{ $slot }}</div>
    </div>
</div>
