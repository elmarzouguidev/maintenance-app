{{-- Empty list or panel: a title, an optional description, icon and action (slot). --}}
@props(['title', 'description' => null, 'icon' => null])

<section {{ $attributes->class(['admin-empty-state']) }}>
    @if ($icon)
        <x-admin.icon :name="$icon" class="mx-auto mb-3 size-8 text-fg-muted" />
    @endif

    <h2 class="admin-empty-state-title">{{ $title }}</h2>

    @if ($description)
        <p class="admin-empty-state-description">{{ $description }}</p>
    @endif

    @if ($slot->isNotEmpty())
        <div class="mt-5 flex flex-wrap justify-center gap-2">{{ $slot }}</div>
    @endif
</section>
