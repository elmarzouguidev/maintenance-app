{{-- Guest page heading: logo, optional eyebrow, title and description (guest layout only). --}}
@props(['title', 'eyebrow' => null, 'description' => null, 'logo' => true])

<div {{ $attributes->class('mb-7 text-center') }}>
    @if ($logo)
        <img class="mx-auto h-16 w-auto" src="{{ asset(config('brand.logo_mark')) }}" alt="{{ config('brand.name') }}">
    @endif
    @if ($eyebrow)
        <p class="mt-5 text-small font-medium text-fg-muted">{{ $eyebrow }}</p>
    @endif
    <h1 @class(['text-page-title font-semibold tracking-tight text-fg', 'mt-2' => $eyebrow, 'mt-5' => ! $eyebrow && $logo])>{{ $title }}</h1>
    @if ($description)
        <p class="mt-2 text-body text-fg-muted">{{ $description }}</p>
    @endif
</div>
