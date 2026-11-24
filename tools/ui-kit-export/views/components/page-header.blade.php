@extends('kit-export::gallery')

@section('demo')
    <section aria-labelledby="full-title" class="space-y-4">
        @include('kit-export::partials.demo-heading', ['id' => 'full-title', 'title' => 'Breadcrumbs, description and actions', 'text' => 'The one visible title of a page (the topbar carries none). The last breadcrumb is the current page. Actions: one primary at most.'])
        <div class="admin-card p-5 sm:p-6">
{{-- snippet:full --}}
<x-admin.page-header title="RES-2026-0148"
    description="Amal Benali · 03/11/2026 à 09:30 → 07/11/2026"
    :breadcrumbs="[['label' => 'Réservations', 'href' => '#'], ['label' => 'RES-2026-0148']]">
    <x-slot:actions>
        <x-admin.status-badge variant="info" label="En cours" />
        <x-admin.button href="#" variant="secondary" icon="document">Contrat</x-admin.button>
        <x-admin.button href="#" icon="return">Effectuer le retour</x-admin.button>
    </x-slot:actions>
</x-admin.page-header>
{{-- /snippet --}}
        </div>
        @include('kit-export::partials.snippet', ['name' => 'full'])
    </section>

    <section aria-labelledby="eyebrow-title" class="space-y-4">
        @include('kit-export::partials.demo-heading', ['id' => 'eyebrow-title', 'title' => 'Eyebrow and description', 'text' => 'An eyebrow names the area (settings, a module) above the title.'])
        <div class="admin-card p-5 sm:p-6">
{{-- snippet:eyebrow --}}
<x-admin.page-header eyebrow="Paramètres de l’organisation" title="Ajouter une agence"
    description="Le code de chaque agence doit être unique au sein de votre organisation." />
{{-- /snippet --}}
        </div>
        @include('kit-export::partials.snippet', ['name' => 'eyebrow'])
    </section>

    <section aria-labelledby="minimal-title" class="space-y-4">
        @include('kit-export::partials.demo-heading', ['id' => 'minimal-title', 'title' => 'List page', 'text' => 'A list page: title, short description and the create action.'])
        <div class="admin-card p-5 sm:p-6">
{{-- snippet:minimal --}}
<x-admin.page-header title="Véhicules" description="Flotte de l’agence, disponibilité et échéances.">
    <x-slot:actions>
        <x-admin.button href="#" icon="plus">Ajouter un véhicule</x-admin.button>
    </x-slot:actions>
</x-admin.page-header>
{{-- /snippet --}}
        </div>
        @include('kit-export::partials.snippet', ['name' => 'minimal'])
    </section>
@endsection
