@props(['title', 'description' => null, 'headingLevel' => 2])

{{--
    Entity detail pattern (from direction C): sections on the white page, separated by
    hairlines, instead of stacked bordered cards. Use inside x-admin.detail-page.
--}}
@php($headingId = 'section-'.\Illuminate\Support\Str::slug($title))
<section aria-labelledby="{{ $headingId }}" {{ $attributes->class('border-t border-line py-6 first:border-t-0 first:pt-0') }}>
    <div class="mb-4 flex flex-wrap items-start justify-between gap-3">
        <div class="min-w-0">
            <h{{ $headingLevel }} id="{{ $headingId }}" class="text-section-title font-semibold text-fg">{{ $title }}</h{{ $headingLevel }}>
            @if ($description)
                <p class="mt-1 text-body text-fg-muted">{{ $description }}</p>
            @endif
        </div>
        @isset($actions)
            <div class="flex shrink-0 flex-wrap items-center gap-2">{{ $actions }}</div>
        @endisset
    </div>
    {{ $slot }}
</section>
