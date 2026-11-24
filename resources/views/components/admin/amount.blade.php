{{-- Amount in the shared format ("1 234,56 MAD", App\Support\UiKit\Format), tabular and never wrapped. Pass major units, or minor (cents). currency: null uses Format::$currency. --}}
@props(['value' => null, 'minor' => null, 'currency' => null])

<span {{ $attributes->class('tabular-nums whitespace-nowrap') }}>{{ $minor !== null ? \App\Support\UiKit\Format::moneyMinor((int) $minor, $currency) : \App\Support\UiKit\Format::money($value, $currency) }}</span>
