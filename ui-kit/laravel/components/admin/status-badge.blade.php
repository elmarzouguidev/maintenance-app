@props(['status' => null, 'variant' => null, 'label' => null])

@php
    /** @var \App\Support\UiKit\HasStatusBadge|null $status */
    $variant = $status?->badgeVariant() ?? \App\Support\UiKit\BadgeVariant::tryFrom((string) $variant) ?? \App\Support\UiKit\BadgeVariant::NEUTRAL;

    // Full class names only: Tailwind cannot see string-built utilities.
    $classes = match ($variant) {
        \App\Support\UiKit\BadgeVariant::SUCCESS => 'admin-status-badge-success',
        \App\Support\UiKit\BadgeVariant::WARNING => 'admin-status-badge-warning',
        \App\Support\UiKit\BadgeVariant::DANGER => 'admin-status-badge-danger',
        \App\Support\UiKit\BadgeVariant::INFO => 'admin-status-badge-info',
        default => 'admin-status-badge-neutral',
    };
@endphp

<span {{ $attributes->class(['admin-status-badge', $classes]) }}>{{ $label ?? $status?->label() ?? $slot }}</span>
