{{--
    Card (Bootstrap .card + Skote card-title / card-title-desc).
    Slots: `actions` (next to the title), `header` (grey header bar), `footer`.
    `flush` removes Skote's 24px bottom margin; `:padding="false"` removes the body padding.
--}}
@props([
    'title' => null,
    'description' => null,
    'icon' => null,
    'flush' => false,
    'padding' => true,
])

<div {{ $attributes->class(['flex min-w-0 flex-col break-words rounded-sm bg-white shadow-card', 'mb-6' => ! $flush]) }}>
    @isset($header)
        <div {{ $header->attributes->class('rounded-t-sm bg-black/3 px-5 py-2.5') }}>{{ $header }}</div>
    @endisset

    <div @class(['flex-1', 'p-5' => $padding])>
        @if (filled($title) || isset($actions))
            <div class="flex flex-wrap items-start justify-between gap-2">
                @if (filled($title))
                    <h4 class="mb-[7px] text-15 font-semibold">
                        @if ($icon)
                            <i class="{{ $icon }} me-2" aria-hidden="true"></i>
                        @endif
                        {{ $title }}
                    </h4>
                @endif

                @isset($actions)
                    <div class="ms-auto flex flex-wrap items-center gap-2">{{ $actions }}</div>
                @endisset
            </div>
        @endif

        @if (filled($description))
            <p class="mb-6 text-muted">{{ $description }}</p>
        @endif

        {{ $slot }}
    </div>

    @isset($footer)
        <div {{ $footer->attributes->class('rounded-b-sm bg-black/3 px-5 py-2.5') }}>{{ $footer }}</div>
    @endisset
</div>
