@extends('kit-export::gallery')

@section('demo')
    <section aria-labelledby="allowed-title" class="space-y-4">
        @include('kit-export::partials.demo-heading', ['id' => 'allowed-title', 'title' => 'Allowed and refused', 'text' => 'Allowed: a DELETE form with a browser confirmation. Refused: the button stays visible but disabled, and the reason is its tooltip and accessible description, so nobody wonders why it is missing.'])
        <div class="admin-card flex flex-wrap items-center gap-6 p-5">
{{-- snippet:allowed --}}
<x-admin.delete-action action="#" confirm="Supprimer le client Amal Benali ? Cette action est définitive." />

<x-admin.delete-action action="#customers/2" confirm="Supprimer ce client ?" refusal="Impossible : ce client a des réservations enregistrées." />
{{-- /snippet --}}
        </div>
        @include('kit-export::partials.snippet', ['name' => 'allowed'])
    </section>
@endsection
