@extends('kit-export::gallery')

@section('demo')
    <section aria-labelledby="states-title" class="space-y-4">
        @include('kit-export::partials.demo-heading', ['id' => 'states-title', 'title' => 'States', 'text' => 'Visible label always; required marked with *. Help and error are linked with aria-describedby; an invalid field gets aria-invalid. Space fields with gap on the parent. Inputs are 40 px.'])
        <div class="admin-card p-5 sm:p-6">
{{-- snippet:states --}}
<div class="grid gap-4 md:grid-cols-2">
    <x-admin.form-input name="customer_name" label="Nom du client" placeholder="Ex. Amal Benali" required autocomplete="name" />
    <x-admin.form-input name="customer_phone" label="Téléphone" inputmode="tel" autocomplete="tel" help="Format : 06 12 34 56 78." />
    <x-admin.form-input name="error_mileage" label="Kilométrage au retour" value="24390" inputmode="numeric" required />
    <x-admin.form-input name="error_email" type="email" label="E-mail" value="amal.benali@" inputmode="email" />
    <x-admin.form-input name="old_customer_name" label="Saisie précédente conservée (old)" />
    <x-admin.form-input name="readonly_code" label="Code" value="RES-2026-0148" readonly />
    <x-admin.form-input name="disabled_field" label="Champ désactivé" value="Non modifiable" disabled />
    <x-admin.form-input name="registration_number" label="Immatriculation" value="12345-أ-6" plate help="Saisie dans l’ordre de la plaque." />
</div>
{{-- /snippet --}}
        </div>
        @include('kit-export::partials.snippet', ['name' => 'states'])
    </section>

    <section aria-labelledby="textarea-title" class="space-y-4">
        @include('kit-export::partials.demo-heading', ['id' => 'textarea-title', 'title' => 'Textarea'])
        <div class="admin-card p-5 sm:p-6">
{{-- snippet:textarea --}}
<div class="grid gap-4 md:grid-cols-2">
    <x-admin.textarea name="notes" label="Notes" placeholder="Observations sur le véhicule" help="Visible sur le contrat." />
    <x-admin.textarea name="error_notes" label="Notes internes" value="Texte trop long…" rows="3" />
</div>
{{-- /snippet --}}
        </div>
        @include('kit-export::partials.snippet', ['name' => 'textarea'])
    </section>

    <section aria-labelledby="form-title" class="space-y-4">
        @include('kit-export::partials.demo-heading', ['id' => 'form-title', 'title' => 'Form layout', 'text' => 'A card form: fields in a grid, actions at the end (secondary first, primary last on desktop; reversed order on phones so the primary stays on top).'])
{{-- snippet:form --}}
<form method="POST" action="#" class="admin-card space-y-6 p-5 sm:p-6">
    <div class="grid gap-4 md:grid-cols-2">
        <x-admin.form-input label="Nom de l’agence" name="agency_name" value="Rabat Centre" required />
        <x-admin.form-input label="Code de l’agence" name="agency_code" value="RBT" required autocomplete="off" />
        <x-admin.textarea label="Adresse" name="agency_address" rows="3" class="md:col-span-2" />
    </div>
    <div class="flex flex-col-reverse gap-3 border-t border-line pt-6 sm:flex-row sm:justify-end">
        <x-admin.button href="#" variant="secondary">Annuler</x-admin.button>
        <x-admin.button type="submit">Enregistrer les modifications</x-admin.button>
    </div>
</form>
{{-- /snippet --}}
        @include('kit-export::partials.snippet', ['name' => 'form'])
    </section>
@endsection
