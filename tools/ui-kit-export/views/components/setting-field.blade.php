@extends('kit-export::gallery')

@section('demo')
    <section aria-labelledby="types-title" class="space-y-4">
        @include('kit-export::partials.demo-heading', ['id' => 'types-title', 'title' => 'Every type', 'text' => 'One settings field: text, email, password, url, number, time, textarea, select, file (current image and a live preview of the chosen one) or toggle.'])
        <div class="admin-card p-5 sm:p-6">
{{-- snippet:types --}}
<div class="grid gap-4 md:grid-cols-2">
    <x-admin.setting-field name="company_name" label="Raison sociale" value="Atlas Location SARL" required help="Imprimée sur les factures et les contrats." />
    <x-admin.setting-field type="number" name="default_deposit" label="Caution par défaut (MAD)" value="3000" />
    <x-admin.setting-field type="select" name="currency" label="Devise" value="MAD" :options="['MAD' => 'Dirham marocain (MAD)', 'EUR' => 'Euro (EUR)']" />
    <x-admin.setting-field type="time" name="opening_time" label="Heure d’ouverture" value="08:30" />
    <x-admin.setting-field type="textarea" name="contract_terms" label="Conditions du contrat" value="Le véhicule est rendu avec le même niveau de carburant." rows="3" class="md:col-span-2" />
    <x-admin.setting-field type="file" name="stamp" label="Cachet de la société" preview-url="../assets/brand/logo-mark.svg" help="PNG transparent recommandé." />
    <x-admin.setting-field type="toggle" name="show_condition_map" label="Carte d’état sur le contrat" value="1" help="Affiche le schéma du véhicule au départ et au retour." />
</div>
{{-- /snippet --}}
        </div>
        @include('kit-export::partials.snippet', ['name' => 'types'])
    </section>

    <section aria-labelledby="error-title" class="space-y-4">
        @include('kit-export::partials.demo-heading', ['id' => 'error-title', 'title' => 'Validation error'])
        <div class="admin-card p-5 sm:p-6">
{{-- snippet:error --}}
<x-admin.setting-field name="error_setting" label="Préfixe des factures" value="fac-" required help="Lettres majuscules, ex. FAC." />
{{-- /snippet --}}
        </div>
        @include('kit-export::partials.snippet', ['name' => 'error'])
    </section>
@endsection
