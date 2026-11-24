@extends('kit-export::gallery')

@section('demo')
    <section aria-labelledby="drawer-title" class="space-y-4">
        @include('kit-export::partials.demo-heading', ['id' => 'drawer-title', 'title' => 'Navigation drawer', 'text' => 'admin-drawer on a native <dialog> (focus trap, Escape, focus return): slides from the start edge (the end edge in right-to-left), 240 ms; opacity only under reduced motion. A click on the backdrop closes it.'])
        <div class="admin-card p-5" x-data>
{{-- snippet:drawer --}}
<x-admin.button variant="secondary" icon="menu" x-on:click="$refs.demoDrawer.showModal()">Ouvrir le menu</x-admin.button>

<dialog x-ref="demoDrawer" class="admin-sidebar admin-drawer" aria-label="Menu" @click="if ($event.target === $el) $el.close()">
    <div class="flex h-full flex-col">
        <div class="flex h-16 items-center justify-between px-5">
            <span class="text-section-title font-semibold text-sidebar-strong">Menu</span>
            <button type="button" class="admin-icon-button text-sidebar-text hover:bg-sidebar-hover hover:text-sidebar-strong" x-on:click="$refs.demoDrawer.close()" aria-label="Fermer le menu" title="Fermer le menu">
                <x-admin.icon name="close" />
            </button>
        </div>
        <nav class="space-y-0.5 px-3" aria-label="Menu de démonstration">
            <x-admin.nav-link href="#" icon="home">Tableau de bord</x-admin.nav-link>
            <x-admin.nav-link href="#" icon="calendar" :active="true">Réservations</x-admin.nav-link>
        </nav>
    </div>
</dialog>
{{-- /snippet --}}
        </div>
        @include('kit-export::partials.snippet', ['name' => 'drawer'])
    </section>

    <section aria-labelledby="sheet-title" class="space-y-4">
        @include('kit-export::partials.demo-heading', ['id' => 'sheet-title', 'title' => 'Phone bottom sheet', 'text' => 'admin-sheet: choices on a phone (the tenant shell uses it for the organisation and agency switcher below 768 px). Slides up, rounded top corners, safe-area padding.'])
        <div class="admin-card p-5" x-data>
{{-- snippet:sheet --}}
<x-admin.button variant="secondary" icon="pin" x-on:click="$refs.demoSheet.showModal()">Changer d’agence</x-admin.button>

<dialog x-ref="demoSheet" class="admin-sheet" aria-labelledby="demo-sheet-title" @click="if ($event.target === $el) $el.close()">
    <div class="flex items-center justify-between border-b border-line px-4 py-3">
        <p id="demo-sheet-title" class="text-section-title font-semibold">Changer de contexte</p>
        <x-admin.icon-button icon="close" label="Fermer" x-on:click="$refs.demoSheet.close()" />
    </div>
    <div class="p-2">
        <button type="button" class="admin-menu-item min-h-11 justify-between" aria-current="true"><span class="truncate font-semibold">Rabat Centre</span><x-admin.icon name="check" class="size-4 text-accent" /></button>
        <button type="button" class="admin-menu-item min-h-11 justify-between"><span class="truncate">Casablanca Maarif</span></button>
    </div>
</dialog>
{{-- /snippet --}}
        </div>
        @include('kit-export::partials.snippet', ['name' => 'sheet'])
    </section>
@endsection
