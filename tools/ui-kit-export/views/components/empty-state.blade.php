@extends('kit-export::gallery')

@section('demo')
    <section aria-labelledby="full-title" class="space-y-4">
        @include('kit-export::partials.demo-heading', ['id' => 'full-title', 'title' => 'Empty list', 'text' => 'Say what is missing and offer the next action. Distinguish "nothing yet" from "no result for these filters".'])
{{-- snippet:full --}}
<x-admin.empty-state icon="inbox" title="Aucune réservation" description="Aucune réservation n’a encore été créée pour cette agence.">
    <x-admin.button href="#" icon="plus">Nouvelle réservation</x-admin.button>
</x-admin.empty-state>
{{-- /snippet --}}
        @include('kit-export::partials.snippet', ['name' => 'full'])
    </section>

    <section aria-labelledby="filters-title" class="space-y-4">
        @include('kit-export::partials.demo-heading', ['id' => 'filters-title', 'title' => 'No result for the filters'])
{{-- snippet:filters --}}
<x-admin.empty-state icon="filter" title="Aucun résultat" description="Aucun véhicule ne correspond à ces filtres.">
    <x-admin.button href="#" variant="secondary">Effacer les filtres</x-admin.button>
</x-admin.empty-state>
{{-- /snippet --}}
        @include('kit-export::partials.snippet', ['name' => 'filters'])
    </section>

    <section aria-labelledby="minimal-title" class="space-y-4">
        @include('kit-export::partials.demo-heading', ['id' => 'minimal-title', 'title' => 'Minimal, inside a panel'])
{{-- snippet:minimal --}}
<x-admin.empty-state title="Aucun document" description="Ajoutez la carte grise et l’assurance pour suivre leurs échéances." />
{{-- /snippet --}}
        @include('kit-export::partials.snippet', ['name' => 'minimal'])
    </section>
@endsection
