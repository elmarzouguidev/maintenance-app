{{-- Platform admin brand: the product name with an explicit "Plateforme" label. --}}
<a href="{{ $homeUrl ?? url('/') }}" class="flex h-16 items-center gap-3 rounded-control px-5 focus-visible:outline-2 focus-visible:outline-focus">
    <img src="{{ asset(config('brand.logo_mark')) }}" alt="" class="size-9 rounded-control border border-line bg-surface p-1">
    <span class="leading-tight">
        <span class="block text-section-title font-semibold text-brand">{{ config('brand.name') }}</span>
        <span class="block text-caption font-medium text-fg-muted">Plateforme</span>
    </span>
</a>
