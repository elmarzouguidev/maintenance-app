{{--
    Modal (Bootstrap .modal .modal-dialog-centered), driven by Alpine.
    Open:  x-on:click="$dispatch('open-modal', 'name')"   (or from Livewire: $this->dispatch('open-modal', name: 'name'))
    Close: Esc, the backdrop, the × button, x-on:click="show = false" inside the modal,
           or $dispatch('close-modal', 'name').
    `show` opens it on page load, e.g. :show="$errors->any()" to reopen a form after a failed submit.
--}}
@props([
    'name',
    'title' => null,
    'show' => false,
    'maxWidth' => 'md',
    'closeable' => true,
])

@php
    $widths = [
        'sm' => 'sm:max-w-[300px]',
        'md' => 'sm:max-w-[500px]',
        'lg' => 'sm:max-w-[500px] lg:max-w-[800px]',
        'xl' => 'sm:max-w-[500px] lg:max-w-[800px] xl:max-w-[1140px]',
    ];
    $titleId = \Illuminate\Support\Str::slug($name).'-title';
@endphp

<div
    x-data="{ show: @js((bool) $show), name: @js($name) }"
    x-on:open-modal.window="if (($event.detail?.name ?? $event.detail) === name) show = true"
    x-on:close-modal.window="if (($event.detail?.name ?? $event.detail) === name) show = false"
    @if ($closeable) x-on:keydown.escape.window="show = false" @endif
    x-show="show"
    @unless ($show) style="display: none" @endunless
    class="fixed inset-0 z-1055 overflow-y-auto"
    role="dialog"
    aria-modal="true"
    @if (filled($title)) aria-labelledby="{{ $titleId }}" @endif
>
    <div x-show="show" x-transition.opacity class="fixed inset-0 bg-black/50" @if ($closeable) x-on:click="show = false" @endif></div>

    <div class="pointer-events-none relative flex min-h-full items-center justify-center p-2 sm:p-7">
        <div
            x-show="show"
            x-transition
            x-trap.inert.noscroll="show"
            {{ $attributes->class(['pointer-events-auto relative w-full rounded-modal border border-card-line bg-white', $widths[$maxWidth] ?? $widths['md']]) }}
        >
            @if (filled($title) || $closeable)
                <div class="flex items-center justify-between gap-4 border-b border-line p-4">
                    @if (filled($title))
                        <h5 id="{{ $titleId }}" class="mb-0">{{ $title }}</h5>
                    @endif

                    @if ($closeable)
                        <button type="button" class="ms-auto text-muted transition-colors hover:text-body" x-on:click="show = false" aria-label="Fermer">
                            <i class="mdi mdi-close text-18 leading-none" aria-hidden="true"></i>
                        </button>
                    @endif
                </div>
            @endif

            <div class="p-4">{{ $slot }}</div>

            @isset($footer)
                <div {{ $footer->attributes->class('flex flex-wrap items-center justify-end gap-2 border-t border-line p-3') }}>{{ $footer }}</div>
            @endisset
        </div>
    </div>
</div>
