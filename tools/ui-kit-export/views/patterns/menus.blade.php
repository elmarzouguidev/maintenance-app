@extends('kit-export::gallery')

@section('demo')
    <section aria-labelledby="menu-title" class="space-y-4">
        @include('kit-export::partials.demo-heading', ['id' => 'menu-title', 'title' => 'Dropdown menu', 'text' => 'A button with aria-haspopup and aria-expanded, an admin-popover with admin-menu-item entries. Opens with an entrance transition (opacity and slight scale, opacity only under reduced motion); closes on a click outside and on Escape, which returns focus to the button.'])
        <div class="admin-card flex min-h-64 items-start justify-end p-5">
{{-- snippet:menu --}}
<div class="relative" x-data="{ open: false }" @keydown.escape="if (open) { open = false; $refs.button.focus() }">
    <button type="button" x-ref="button" class="admin-button admin-button-secondary admin-button-md"
        x-on:click="open = ! open" aria-haspopup="true" :aria-expanded="open.toString()">
        Actions <x-admin.icon name="chevron-down" class="size-4" />
    </button>
    <div x-show="open" x-cloak @click.outside="open = false"
        x-transition:enter="admin-popover-enter" x-transition:enter-start="opacity-0 motion-safe:scale-96" x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition-opacity duration-100 ease-out" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="admin-popover absolute end-0 top-full z-40 mt-2 w-64 origin-top-right p-1">
        <div class="py-1">
            <a href="#" class="admin-menu-item"><x-admin.icon name="printer" class="size-4 text-fg-muted" /> Imprimer le contrat</a>
            <a href="#" class="admin-menu-item"><x-admin.icon name="download" class="size-4 text-fg-muted" /> Télécharger la facture</a>
        </div>
        <div class="border-t border-line pt-1">
            <button type="button" class="admin-menu-item admin-menu-item-danger"><x-admin.icon name="x-circle" class="size-4" /> Annuler la réservation</button>
        </div>
    </div>
</div>
{{-- /snippet --}}
        </div>
        @include('kit-export::partials.snippet', ['name' => 'menu'])
    </section>

    <section aria-labelledby="choices-title" class="space-y-4">
        @include('kit-export::partials.demo-heading', ['id' => 'choices-title', 'title' => 'Choice list with the current item', 'text' => 'The context switcher pattern: each choice is a submit button; the current one has aria-current and a check.'])
        <div class="admin-popover max-w-80 p-2">
{{-- snippet:choices --}}
<p class="px-2 pb-1 pt-1 text-caption font-semibold text-fg-muted" id="demo-agencies">Agence</p>
<form method="POST" action="#">
    <ul aria-labelledby="demo-agencies">
        <li>
            <button type="submit" name="agency_id" value="1" class="admin-menu-item min-h-11 justify-between" aria-current="true">
                <span class="truncate font-semibold">Rabat Centre</span>
                <x-admin.icon name="check" class="size-4 text-accent" />
            </button>
        </li>
        <li>
            <button type="submit" name="agency_id" value="2" class="admin-menu-item min-h-11 justify-between">
                <span class="truncate">Casablanca Maarif</span>
            </button>
        </li>
    </ul>
</form>
{{-- /snippet --}}
        </div>
        @include('kit-export::partials.snippet', ['name' => 'choices'])
    </section>
@endsection
