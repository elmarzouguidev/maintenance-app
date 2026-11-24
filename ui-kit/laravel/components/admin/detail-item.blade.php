@props(['label'])

{{-- One label/value pair in a detail section. Wrap several in a <dl> grid. --}}
<div {{ $attributes->class('min-w-0') }}>
    <dt class="text-caption text-fg-muted">{{ $label }}</dt>
    <dd class="mt-0.5 text-body text-fg">{{ $slot }}</dd>
</div>
