{{--
    Inline message. Types: success, error (or danger), warning, info. Errors are announced
    immediately (role="alert"), the others politely (role="status"). Dismissible.
--}}
@props(['type' => 'info', 'message' => null, 'dismissible' => true])

@php
    $type = $type === 'danger' ? 'error' : $type;
    $icons = ['success' => 'check-circle', 'error' => 'x-circle', 'warning' => 'warning', 'info' => 'info'];
    $type = array_key_exists($type, $icons) ? $type : 'info';
@endphp

<div x-data="{ show: true }" x-show="show" x-transition.opacity.duration.150ms>
    <div role="{{ $type === 'error' ? 'alert' : 'status' }}" {{ $attributes->class(['admin-alert', 'admin-alert-'.$type]) }}>
        <x-admin.icon :name="$icons[$type]" class="mt-px size-5" />
        <div class="min-w-0 flex-1 font-medium">{{ $message ?? $slot }}</div>
        @if ($dismissible)
            <button type="button" x-on:click="show = false" aria-label="Fermer le message"
                class="-me-1.5 -mt-0.5 inline-flex size-7 shrink-0 cursor-pointer items-center justify-center rounded-control opacity-80 transition-opacity duration-150 hover:opacity-100 focus-visible:outline-2 focus-visible:outline-current">
                <x-admin.icon name="close" class="size-4" />
            </button>
        @endif
    </div>
</div>
