{{-- Add a supplier (K2): the kit card form. The logo field is gone: store() never saved it (BL-71). --}}
@extends('layouts.admin')

@section('title', 'Ajouter un fournisseur')

@section('content')
    <div class="space-y-6">
        <x-admin.page-header title="Ajouter un fournisseur"
            :breadcrumbs="[['label' => 'Fournisseurs', 'href' => route('commercial:providers.index')], ['label' => 'Ajouter']]" />

        <form method="POST" action="{{ route('commercial:providers.createPost') }}" class="admin-card space-y-6 p-5 sm:p-6">
            @csrf

            <x-messages.errors />

            <div class="grid gap-4 md:grid-cols-2">
                <x-admin.form-input name="entreprise" label="Entreprise" required />
                <x-admin.form-input name="contact" label="Contact" required help="La personne qui représente la société." />
                <x-admin.form-input name="telephone" type="tel" label="Téléphone" required inputmode="tel" help="Numéro marocain, ex. 0522123456." />
                <x-admin.form-input name="email" type="email" label="E-mail" required inputmode="email" />
                <x-admin.form-input name="addresse" label="Adresse" required class="md:col-span-2" />
                <x-admin.form-input name="ice" label="ICE" required inputmode="numeric" help="15 ou 16 chiffres." />
                <x-admin.form-input name="rc" label="RC" inputmode="numeric" />
                <x-admin.textarea name="description" label="Description" rows="4" class="md:col-span-2" />
            </div>

            <div class="flex flex-col-reverse gap-3 border-t border-line pt-6 sm:flex-row sm:justify-end">
                <x-admin.button href="{{ route('commercial:providers.index') }}" variant="secondary">Annuler</x-admin.button>
                <x-admin.button type="submit">Enregistrer le fournisseur</x-admin.button>
            </div>
        </form>
    </div>
@endsection
