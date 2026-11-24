{{--
    Row of x-admin.stats-card. Counts: up to 4 per row on desktop. Amounts (amounts):
    at most 3 per row on desktop, then 2, then 1, because amounts never wrap.
--}}
@props(['amounts' => false])

<div {{ $attributes->class([
    'grid gap-4 sm:grid-cols-2',
    'lg:grid-cols-3' => $amounts,
    'lg:grid-cols-4' => ! $amounts,
]) }}>
    {{ $slot }}
</div>
