{{-- Date in the shared format (App\Support\UiKit\Format): d/m/Y, "d/m/Y à H:i" (time) or "3 novembre 2026" (long). --}}
@props(['value' => null, 'time' => false, 'long' => false])

@php
    $date = filled($value) ? ($value instanceof \DateTimeInterface ? \Illuminate\Support\Carbon::instance($value) : \Illuminate\Support\Carbon::parse($value)) : null;
    $text = match (true) {
        $long => \App\Support\UiKit\Format::longDate($date),
        $time => \App\Support\UiKit\Format::dateTime($date),
        default => \App\Support\UiKit\Format::date($date),
    };
@endphp

@if ($date)
    <time datetime="{{ $date->format($time ? 'Y-m-d\TH:i' : 'Y-m-d') }}" {{ $attributes->class('tabular-nums whitespace-nowrap') }}>{{ $text }}</time>
@else
    <span {{ $attributes->class('text-fg-muted') }}>{{ $text }}</span>
@endif
