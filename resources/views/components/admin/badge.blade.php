{{--
    Generic label badge, on the status badge styles. For a status, use x-admin.status-badge
    with the enum instead. Variants: neutral, success, warning, danger, info.
--}}
@props(['variant' => 'neutral'])

@php
    $variants = [
        'neutral' => 'admin-status-badge-neutral',
        'success' => 'admin-status-badge-success',
        'warning' => 'admin-status-badge-warning',
        'danger' => 'admin-status-badge-danger',
        'info' => 'admin-status-badge-info',
    ];
@endphp

<span {{ $attributes->class(['admin-status-badge', $variants[$variant] ?? $variants['neutral']]) }}>{{ $slot }}</span>
