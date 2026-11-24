@extends('kit-export::gallery')

@section('demo')
    <section aria-labelledby="counts-title" class="space-y-4">
        @include('kit-export::partials.demo-heading', ['id' => 'counts-title', 'title' => 'Counts grid', 'text' => 'Up to 4 cards per row on desktop. The marker carries the variant; the card stays neutral. With href the whole card is a link; selected marks the active filter.'])
{{-- snippet:counts --}}
<x-admin.stats-grid>
    <x-admin.stats-card title="Départs du jour" value="12" icon="departure" />
    <x-admin.stats-card title="Retours en retard" value="3" icon="warning" variant="danger" href="#" button-text="Voir les retours en retard" />
    <x-admin.stats-card title="Réservations confirmées" value="7" icon="calendar" variant="info" href="#" />
    <x-admin.stats-card title="Filtre actif" value="5" icon="check" href="#" selected />
</x-admin.stats-grid>
{{-- /snippet --}}
        @include('kit-export::partials.snippet', ['name' => 'counts'])
    </section>

    <section aria-labelledby="amounts-title" class="space-y-4">
        @include('kit-export::partials.demo-heading', ['id' => 'amounts-title', 'title' => 'Amounts grid', 'text' => 'At most 3 per row on desktop, then 2, then 1: amounts never wrap.'])
{{-- snippet:amounts --}}
<x-admin.stats-grid amounts>
    <x-admin.stats-card title="Encaissé ce mois" :value="\App\Support\UiKit\Format::money(184250.5)" icon="banknotes" variant="success" />
    <x-admin.stats-card title="Reste à encaisser" :value="\App\Support\UiKit\Format::money(12400)" icon="card" variant="warning" />
    <x-admin.stats-card title="Cautions détenues" :value="\App\Support\UiKit\Format::money(36000)" icon="shield" />
</x-admin.stats-grid>
{{-- /snippet --}}
        @include('kit-export::partials.snippet', ['name' => 'amounts'])
    </section>

    <section aria-labelledby="variants-title" class="space-y-4">
        @include('kit-export::partials.demo-heading', ['id' => 'variants-title', 'title' => 'Every variant, and without icon', 'text' => 'primary (brand, default), success, warning, danger, info, neutral.'])
{{-- snippet:variants --}}
<div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
    <x-admin.stats-card title="Primary" value="24" icon="truck" />
    <x-admin.stats-card title="Success" value="18" icon="check-circle" variant="success" />
    <x-admin.stats-card title="Warning" value="4" icon="clock" variant="warning" />
    <x-admin.stats-card title="Danger" value="2" icon="x-circle" variant="danger" />
    <x-admin.stats-card title="Info" value="9" icon="info" variant="info" />
    <x-admin.stats-card title="Neutral" value="1" icon="minus" variant="neutral" />
    <x-admin.stats-card title="Sans icône" value="0" />
</div>
{{-- /snippet --}}
        @include('kit-export::partials.snippet', ['name' => 'variants'])
    </section>
@endsection
