@props(['value' => null, 'fallback' => 'Immatriculation non renseignée'])

{{-- Display only: keeps the typed order of plates mixing Latin and Arabic, such as "12345-أ-6". --}}
@if (filled($value))
    <bdo dir="ltr" {{ $attributes->class('admin-plate') }}>{{ $value }}</bdo>
@else
    <span {{ $attributes->class('text-fg-muted') }}>{{ $fallback }}</span>
@endif
