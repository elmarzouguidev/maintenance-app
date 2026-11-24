@extends('kit-export::gallery')

@section('demo')
    <section aria-labelledby="sidebar-title" class="space-y-4">
        @include('kit-export::partials.demo-heading', ['id' => 'sidebar-title', 'title' => 'Sidebar links and groups', 'text' => 'x-admin.nav-link: the current page gets aria-current="page" and the rail. Groups collapse and are remembered per browser; the group holding the current page stays open.'])
{{-- snippet:sidebar --}}
<div class="admin-sidebar w-64 rounded-panel px-3 py-4">
    <nav aria-label="Navigation principale" class="space-y-4" x-data="adminNavGroups">
        <ul class="space-y-0.5">
            <li><x-admin.nav-link href="#" icon="home">Tableau de bord</x-admin.nav-link></li>
        </ul>
        <div>
            <button type="button" class="admin-nav-group" aria-controls="demo-nav-exploitation" aria-expanded="true" aria-disabled="true" title="Groupe de la page courante">
                <span>Exploitation</span>
                <x-admin.icon name="chevron-down" class="size-3.5" />
            </button>
            <ul id="demo-nav-exploitation" class="mt-1 space-y-0.5">
                <li><x-admin.nav-link href="#" icon="sliders">Pilotage opérationnel</x-admin.nav-link></li>
                <li><x-admin.nav-link href="#" icon="calendar" :active="true">Réservations</x-admin.nav-link></li>
                <li><x-admin.nav-link href="#" icon="users">Clients</x-admin.nav-link></li>
            </ul>
        </div>
        <div>
            <button type="button" class="admin-nav-group" aria-controls="demo-nav-flotte" :aria-expanded="isOpen('flotte', false).toString()" x-on:click="toggle('flotte')">
                <span>Flotte</span>
                <x-admin.icon name="chevron-down" class="size-3.5 transition-transform duration-150" ::class="isOpen('flotte', false) ? '' : '-rotate-90'" />
            </button>
            <ul id="demo-nav-flotte" class="mt-1 space-y-0.5" x-show="isOpen('flotte', false)">
                <li><x-admin.nav-link href="#" icon="truck">Véhicules</x-admin.nav-link></li>
                <li><x-admin.nav-link href="#" icon="wrench">Entretien de la flotte</x-admin.nav-link></li>
            </ul>
        </div>
    </nav>
</div>
{{-- /snippet --}}
        @include('kit-export::partials.snippet', ['name' => 'sidebar'])
    </section>

    <section aria-labelledby="light-title" class="space-y-4">
        @include('kit-export::partials.demo-heading', ['id' => 'light-title', 'title' => 'Light sidebar (platform shell)', 'text' => 'On a light surface the current page uses the accent-soft selection, not the rail.'])
{{-- snippet:light --}}
<div class="admin-card w-64 px-3 py-4">
    <nav aria-label="Navigation de la plateforme" class="space-y-5">
        <ul class="space-y-0.5">
            <li>
                <a href="#" class="flex min-h-10 items-center gap-3 rounded-control px-3 text-small font-medium transition-colors duration-150 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-focus text-fg hover:bg-surface-muted">
                    <x-admin.icon name="home" class="size-5 shrink-0 text-fg-muted" /> Vue d’ensemble
                </a>
            </li>
            <li>
                <a href="#" aria-current="page" class="flex min-h-10 items-center gap-3 rounded-control px-3 text-small font-medium transition-colors duration-150 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-focus bg-accent-soft text-accent-strong">
                    <x-admin.icon name="building" class="size-5 shrink-0 text-accent" /> Organisations
                </a>
            </li>
        </ul>
    </nav>
</div>
{{-- /snippet --}}
        @include('kit-export::partials.snippet', ['name' => 'light'])
    </section>

    <section aria-labelledby="tabs-title" class="space-y-4">
        @include('kit-export::partials.demo-heading', ['id' => 'tabs-title', 'title' => 'Section tabs', 'text' => 'Links between the pages of one area (settings). The row scrolls inside itself on a phone; the current page has aria-current and the selection colour.'])
{{-- snippet:tabs --}}
<nav class="admin-card overflow-x-auto" aria-label="Navigation des paramètres">
    <ul class="flex min-w-max gap-1 p-1.5" role="list">
        <li>
            <a href="#" aria-current="page" class="flex min-h-10 items-center gap-2 rounded-control px-3 text-small font-medium whitespace-nowrap transition-colors duration-150 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-focus bg-accent-soft text-accent-strong">
                <x-admin.icon name="building" class="size-4" /> Société
            </a>
        </li>
        <li>
            <a href="#" class="flex min-h-10 items-center gap-2 rounded-control px-3 text-small font-medium whitespace-nowrap transition-colors duration-150 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-focus text-fg-muted hover:bg-surface-muted hover:text-fg">
                <x-admin.icon name="calendar" class="size-4" /> Réservations
            </a>
        </li>
        <li>
            <a href="#" class="flex min-h-10 items-center gap-2 rounded-control px-3 text-small font-medium whitespace-nowrap transition-colors duration-150 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-focus text-fg-muted hover:bg-surface-muted hover:text-fg">
                <x-admin.icon name="document" class="size-4" /> Facturation
            </a>
        </li>
        <li>
            <a href="#" class="flex min-h-10 items-center gap-2 rounded-control px-3 text-small font-medium whitespace-nowrap transition-colors duration-150 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-focus text-fg-muted hover:bg-surface-muted hover:text-fg">
                <x-admin.icon name="printer" class="size-4" /> Documents PDF
            </a>
        </li>
    </ul>
</nav>
{{-- /snippet --}}
        @include('kit-export::partials.snippet', ['name' => 'tabs'])
    </section>

    <section aria-labelledby="buttons-title" class="space-y-4">
        @include('kit-export::partials.demo-heading', ['id' => 'buttons-title', 'title' => 'Tab buttons (catalog switcher)', 'text' => 'Small buttons between sibling lists: the current one is primary with aria-current.'])
{{-- snippet:buttons --}}
<nav class="flex flex-wrap gap-2" aria-label="Référentiels partagés">
    <x-admin.button href="#" size="sm" variant="primary" aria-current="page">Marques</x-admin.button>
    <x-admin.button href="#" size="sm" variant="secondary">Catégories de véhicules</x-admin.button>
    <x-admin.button href="#" size="sm" variant="secondary">Villes</x-admin.button>
</nav>
{{-- /snippet --}}
        @include('kit-export::partials.snippet', ['name' => 'buttons'])
    </section>

    <section aria-labelledby="breadcrumb-title" class="space-y-4">
        @include('kit-export::partials.demo-heading', ['id' => 'breadcrumb-title', 'title' => 'Breadcrumbs', 'text' => 'Rendered by x-admin.page-header from its breadcrumbs prop (see Page header).'])
        <div class="admin-card p-5">
            <x-admin.page-header title="Modifier le véhicule" :breadcrumbs="[['label' => 'Véhicules', 'href' => '#'], ['label' => 'Dacia Sandero', 'href' => '#'], ['label' => 'Modifier']]" />
        </div>
    </section>
@endsection
