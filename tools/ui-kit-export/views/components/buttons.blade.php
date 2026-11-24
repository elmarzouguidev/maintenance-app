@extends('kit-export::gallery')

@section('demo')
    <section aria-labelledby="variants-title" class="space-y-4">
        @include('kit-export::partials.demo-heading', ['id' => 'variants-title', 'title' => 'Variants', 'text' => 'One primary (accent) action per area. secondary for the others, ghost for low-emphasis inline actions, danger for destructive ones. Green and amber are status colours, never action colours.'])
        <div class="admin-card flex flex-wrap items-center gap-3 p-5">
{{-- snippet:variants --}}
<x-admin.button icon="plus">Nouvelle réservation</x-admin.button>
<x-admin.button variant="secondary">Annuler</x-admin.button>
<x-admin.button variant="ghost">Plus tard</x-admin.button>
<x-admin.button variant="danger" icon="trash">Supprimer</x-admin.button>
{{-- /snippet --}}
        </div>
        @include('kit-export::partials.snippet', ['name' => 'variants'])
    </section>

    <section aria-labelledby="states-title" class="space-y-4">
        @include('kit-export::partials.demo-heading', ['id' => 'states-title', 'title' => 'States and links', 'text' => 'loading disables the button, sets aria-busy and shows a spinner. With href the component renders a link with the same look.'])
        <div class="admin-card flex flex-wrap items-center gap-3 p-5">
{{-- snippet:states --}}
<x-admin.button loading>Enregistrement</x-admin.button>
<x-admin.button disabled>Désactivé</x-admin.button>
<x-admin.button variant="secondary" disabled>Secondaire désactivé</x-admin.button>
<x-admin.button href="#" variant="secondary" icon="printer">Imprimer le contrat</x-admin.button>
<x-admin.button href="#" icon="departure">Effectuer le départ</x-admin.button>
<x-admin.button type="submit">Enregistrer</x-admin.button>
{{-- /snippet --}}
        </div>
        @include('kit-export::partials.snippet', ['name' => 'states'])
    </section>

    <section aria-labelledby="sizes-title" class="space-y-4">
        @include('kit-export::partials.demo-heading', ['id' => 'sizes-title', 'title' => 'Sizes', 'text' => 'sm (32 px) in tables and section actions, md (40 px) by default, lg (44 px) for a lone call to action.'])
        <div class="admin-card flex flex-wrap items-center gap-3 p-5">
{{-- snippet:sizes --}}
<x-admin.button size="sm" variant="secondary" icon="pencil">Petit</x-admin.button>
<x-admin.button>Moyen</x-admin.button>
<x-admin.button size="lg">Grand</x-admin.button>
{{-- /snippet --}}
        </div>
        @include('kit-export::partials.snippet', ['name' => 'sizes'])
    </section>

    <section aria-labelledby="icon-title" class="space-y-4">
        @include('kit-export::partials.demo-heading', ['id' => 'icon-title', 'title' => 'Icon buttons', 'text' => 'Square, 40 px (md) or 32 px (sm). The label is required: it is the accessible name and the tooltip.'])
        <div class="admin-card flex flex-wrap items-center gap-3 p-5">
{{-- snippet:icon --}}
<x-admin.icon-button icon="pencil" label="Modifier" />
<x-admin.icon-button icon="trash" label="Supprimer" size="sm" />
<x-admin.icon-button icon="eye" label="Voir la fiche" href="#" />
<x-admin.icon-button icon="ellipsis" label="Plus d’actions" disabled />
{{-- /snippet --}}
        </div>
        @include('kit-export::partials.snippet', ['name' => 'icon'])
    </section>

    <section aria-labelledby="field-title" class="space-y-4">
        @include('kit-export::partials.demo-heading', ['id' => 'field-title', 'title' => 'Inside a field screen (44 px)', 'text' => 'In .admin-field-screen (phone-first flows such as check-out and check-in) buttons, icon buttons and inputs grow to 44 px.'])
        <div class="admin-card p-5">
{{-- snippet:field --}}
<div class="admin-field-screen flex flex-wrap items-center gap-3">
    <x-admin.button variant="secondary">Annuler</x-admin.button>
    <x-admin.button icon="check">Confirmer le départ</x-admin.button>
    <x-admin.icon-button icon="photo" label="Ajouter une photo" />
</div>
{{-- /snippet --}}
        </div>
        @include('kit-export::partials.snippet', ['name' => 'field'])
    </section>
@endsection
