@extends('kit-export::gallery')

@section('demo')
    <section aria-labelledby="amount-title" class="space-y-4">
        @include('kit-export::partials.demo-heading', ['id' => 'amount-title', 'title' => 'Amounts (x-admin.amount)', 'text' => 'Two decimals, comma, no-break space between thousands and before the currency; tabular and never wrapped. Pass major units, or minor units (cents). A missing amount shows a dash. Currencies are never combined or converted.'])
        <div class="admin-card p-5">
{{-- snippet:amount --}}
<dl class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
    <x-admin.detail-item label="Major units"><x-admin.amount :value="2450" /></x-admin.detail-item>
    <x-admin.detail-item label="Minor units (cents)"><x-admin.amount :minor="1842505" /></x-admin.detail-item>
    <x-admin.detail-item label="Other currency"><x-admin.amount :value="980.4" currency="EUR" /></x-admin.detail-item>
    <x-admin.detail-item label="Missing"><x-admin.amount :value="null" /></x-admin.detail-item>
</dl>
{{-- /snippet --}}
        </div>
        @include('kit-export::partials.snippet', ['name' => 'amount'])
    </section>

    <section aria-labelledby="date-title" class="space-y-4">
        @include('kit-export::partials.demo-heading', ['id' => 'date-title', 'title' => 'Dates (x-admin.date)', 'text' => 'd/m/Y, "d/m/Y à H:i" with time, or the long form. A <time> element with a machine-readable datetime.'])
        <div class="admin-card p-5">
{{-- snippet:date --}}
<dl class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
    <x-admin.detail-item label="Date"><x-admin.date value="2026-11-03" /></x-admin.detail-item>
    <x-admin.detail-item label="Date and time"><x-admin.date value="2026-11-03 09:30" time /></x-admin.detail-item>
    <x-admin.detail-item label="Long"><x-admin.date value="2026-11-03" long /></x-admin.detail-item>
    <x-admin.detail-item label="Missing"><x-admin.date :value="null" /></x-admin.detail-item>
</dl>
{{-- /snippet --}}
        </div>
        @include('kit-export::partials.snippet', ['name' => 'date'])
    </section>

    <section aria-labelledby="plate-title" class="space-y-4">
        @include('kit-export::partials.demo-heading', ['id' => 'plate-title', 'title' => 'Plates and identifiers (x-admin.plate)', 'text' => 'Plates mixing Latin digits and Arabic letters keep their typed order (bdo dir="ltr"), even in a right-to-left page. Codes and phone numbers use tabular-nums.'])
        <div class="admin-card p-5">
{{-- snippet:plate --}}
<dl class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
    <x-admin.detail-item label="Plate"><x-admin.plate value="12345-أ-6" /></x-admin.detail-item>
    <x-admin.detail-item label="Another plate"><x-admin.plate value="48213-ب-26" /></x-admin.detail-item>
    <x-admin.detail-item label="Missing plate"><x-admin.plate :value="null" /></x-admin.detail-item>
    <x-admin.detail-item label="Code"><span class="tabular-nums">RES-2026-0148</span></x-admin.detail-item>
</dl>
{{-- /snippet --}}
        </div>
        @include('kit-export::partials.snippet', ['name' => 'plate'])
    </section>

    <section aria-labelledby="other-title" class="space-y-4">
        @include('kit-export::partials.demo-heading', ['id' => 'other-title', 'title' => 'Mileage and durations (Format helper)', 'text' => 'App\Support\UiKit\Format holds the same rules for values printed without a component.'])
        <div class="admin-card p-5">
{{-- snippet:other --}}
<dl class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
    <x-admin.detail-item label="Mileage"><span class="tabular-nums">{{ \App\Support\UiKit\Format::mileage(24500) }}</span></x-admin.detail-item>
    <x-admin.detail-item label="Short duration">{{ \App\Support\UiKit\Format::duration(45) }}</x-admin.detail-item>
    <x-admin.detail-item label="Hours">{{ \App\Support\UiKit\Format::duration(200) }}</x-admin.detail-item>
    <x-admin.detail-item label="Days">{{ \App\Support\UiKit\Format::duration(4380) }}</x-admin.detail-item>
</dl>
{{-- /snippet --}}
        </div>
        @include('kit-export::partials.snippet', ['name' => 'other'])
    </section>
@endsection
