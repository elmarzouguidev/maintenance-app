@extends('kit-export::gallery')

@section('demo')
    <section aria-labelledby="select-title" class="space-y-4">
        @include('kit-export::partials.demo-heading', ['id' => 'select-title', 'title' => 'Select and searchable select', 'text' => 'x-admin.form-select is a native select. x-admin.form-select2 adds a search field (combobox, arrows, Enter, Escape); the submitted value is a hidden input with the same name.'])
        <div class="admin-card p-5 sm:p-6">
{{-- snippet:select --}}
<div class="grid gap-4 md:grid-cols-2">
    <x-admin.form-select name="city_id" label="Ville" :options="['1' => 'Rabat', '2' => 'Casablanca', '3' => 'Marrakech']" value="2" placeholder="Aucune ville sélectionnée" />
    <x-admin.form-select name="error_agency" label="Agence de départ" :options="['1' => 'Rabat Centre', '2' => 'Casablanca Maarif']" required />
    <x-admin.form-select2 name="customer_id" label="Client" :options="['1' => 'Amal Benali', '2' => 'Youssef El Idrissi', '3' => 'Atlas Voyages SARL']" value="2" />
    <x-admin.form-select2 name="error_customer" label="Client (obligatoire)" :options="['1' => 'Amal Benali', '2' => 'Youssef El Idrissi']" required />
</div>
{{-- /snippet --}}
        </div>
        @include('kit-export::partials.snippet', ['name' => 'select'])
    </section>

    <section aria-labelledby="segmented-title" class="space-y-4">
        @include('kit-export::partials.demo-heading', ['id' => 'segmented-title', 'title' => 'Segmented radio', 'text' => 'Native radios in a fieldset: arrow keys move the choice, and the form posts name=value like a select. Nothing is checked unless the value matches.'])
        <div class="admin-card p-5 sm:p-6">
{{-- snippet:segmented --}}
<div class="grid gap-4 md:grid-cols-2">
    <x-admin.segmented-radio name="fuel_level" label="Niveau de carburant" :options="['EMPTY' => 'Vide', 'QUARTER' => '1/4', 'HALF' => '1/2', 'THREE_QUARTERS' => '3/4', 'FULL' => 'Plein']" value="HALF" required />
    <x-admin.segmented-radio name="error_fuel" label="Carburant au retour" :options="['EMPTY' => 'Vide', 'HALF' => '1/2', 'FULL' => 'Plein']" required help="Tel qu’indiqué par la jauge." />
</div>
{{-- /snippet --}}
        </div>
        @include('kit-export::partials.snippet', ['name' => 'segmented'])
    </section>

    <section aria-labelledby="checks-title" class="space-y-4">
        @include('kit-export::partials.demo-heading', ['id' => 'checks-title', 'title' => 'Checkboxes and switch', 'text' => 'admin-checkbox on a native checkbox, always inside its label. The switch is a visually hidden checkbox (peer) followed by admin-switch.'])
        <div class="admin-card p-5 sm:p-6">
{{-- snippet:checks --}}
<div class="grid gap-4 md:grid-cols-2">
    <fieldset class="space-y-2">
        <legend class="admin-form-label">Options du contrat</legend>
        <label class="flex items-center gap-2 text-body"><input type="checkbox" name="options[]" value="gps" class="admin-checkbox" checked> GPS</label>
        <label class="flex items-center gap-2 text-body"><input type="checkbox" name="options[]" value="child_seat" class="admin-checkbox"> Siège enfant</label>
        <label class="flex items-center gap-2 text-body text-fg-muted"><input type="checkbox" name="options[]" value="wifi" class="admin-checkbox" disabled> Wi-Fi (indisponible)</label>
    </fieldset>
    <label class="flex min-h-10 cursor-pointer items-center gap-3 self-start rounded-control border border-line px-4 py-2.5 text-body font-medium text-fg">
        <input type="hidden" name="is_active" value="0">
        <input type="checkbox" name="is_active" value="1" class="admin-checkbox" checked>
        Agence active
    </label>
    <label class="relative inline-flex cursor-pointer items-center gap-3 text-body">
        <input type="checkbox" name="map_enabled" value="1" class="peer sr-only" checked>
        <span class="admin-switch" aria-hidden="true"></span>
        Afficher la carte d’état sur le contrat
    </label>
</div>
{{-- /snippet --}}
        </div>
        @include('kit-export::partials.snippet', ['name' => 'checks'])
    </section>
@endsection
