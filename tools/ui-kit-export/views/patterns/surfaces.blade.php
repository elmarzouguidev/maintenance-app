@extends('kit-export::gallery')

@section('demo')
    <section aria-labelledby="cards-title" class="space-y-4">
        @include('kit-export::partials.demo-heading', ['id' => 'cards-title', 'title' => 'Cards', 'text' => 'Panels have a hairline border and no shadow. admin-card-header and admin-card-body split a titled card; admin-card-soft is a muted panel inside a page or a form.'])
{{-- snippet:cards --}}
<div class="grid gap-4 lg:grid-cols-2">
    <div class="admin-card">
        <div class="admin-card-header"><h2 class="admin-section-title">Mentions visibles dans les PDF</h2></div>
        <div class="admin-card-body text-body text-fg-muted">Choisissez les informations imprimées sur les factures et les contrats.</div>
    </div>
    <div class="admin-card-soft p-5">
        <h3 class="text-sm font-semibold text-fg">Règle d’affichage</h3>
        <p class="mt-2 text-sm text-fg-muted">Une donnée vide n’est jamais imprimée, même si l’option est cochée.</p>
    </div>
</div>
{{-- /snippet --}}
        @include('kit-export::partials.snippet', ['name' => 'cards'])
    </section>

    <section aria-labelledby="inset-title" class="space-y-4">
        @include('kit-export::partials.demo-heading', ['id' => 'inset-title', 'title' => 'Inset and popover', 'text' => 'admin-inset groups content inside a panel (control radius). admin-popover is the only elevated surface (shadow-overlay).'])
{{-- snippet:inset --}}
<div class="grid gap-4 sm:grid-cols-2">
    <div class="admin-card p-5">
        <div class="admin-inset p-4">
            <p class="text-small font-medium text-fg">Kilométrage au départ</p>
            <p class="mt-1 text-section-title font-semibold tabular-nums">24 500 km</p>
        </div>
    </div>
    <div class="admin-popover p-4">
        <p class="text-small font-medium text-fg">admin-popover</p>
        <p class="text-caption text-fg-muted">Menus, listes déroulantes, popovers.</p>
    </div>
</div>
{{-- /snippet --}}
        @include('kit-export::partials.snippet', ['name' => 'inset'])
    </section>

    <section aria-labelledby="filter-title" class="space-y-4">
        @include('kit-export::partials.demo-heading', ['id' => 'filter-title', 'title' => 'Filter cards', 'text' => 'admin-filter-card: a whole card as a link or a choice; is-selected with aria-current for the active one.'])
{{-- snippet:filter --}}
<div class="grid gap-4 sm:grid-cols-3">
    <a href="#" class="admin-filter-card is-selected" aria-current="page">
        <span class="block text-small font-semibold text-fg">Départs du jour</span>
        <span class="mt-1 block text-caption text-fg-muted">6 réservations</span>
    </a>
    <a href="#" class="admin-filter-card">
        <span class="block text-small font-semibold text-fg">Retours du jour</span>
        <span class="mt-1 block text-caption text-fg-muted">4 réservations</span>
    </a>
    <a href="#" class="admin-filter-card">
        <span class="block text-small font-semibold text-fg">Retards</span>
        <span class="mt-1 block text-caption text-fg-muted">1 réservation</span>
    </a>
</div>
{{-- /snippet --}}
        @include('kit-export::partials.snippet', ['name' => 'filter'])
    </section>
@endsection
