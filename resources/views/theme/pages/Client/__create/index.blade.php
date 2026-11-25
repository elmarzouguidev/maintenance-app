{{-- Add a client (K2): the kit card form. Same fields as before; the client is then edited. --}}
@extends('layouts.admin')

@section('title', 'Ajouter un client')

@section('content')
    <div class="mx-auto w-full max-w-3xl space-y-6">
        <x-admin.page-header title="Ajouter un client"
            :breadcrumbs="[['label' => 'Clients', 'href' => route('admin:clients.index')], ['label' => 'Ajouter']]" />

        <form method="POST" action="{{ route('admin:clients.createPost') }}" enctype="multipart/form-data" class="admin-card space-y-6 p-5 sm:p-6">
            @csrf

            <x-messages.errors />

            <div class="grid gap-4 md:grid-cols-2">
                <x-admin.form-input name="entreprise" label="Entreprise" required />
                <x-admin.form-input name="contact" label="Contact" required help="La personne qui représente la société." />
                <x-admin.form-input name="telephone" type="tel" label="Téléphone fixe" required inputmode="tel" help="Numéro marocain, ex. 0522123456." />
                <x-admin.form-input name="email" type="email" label="E-mail" required inputmode="email" />
                <x-admin.form-input name="addresse" label="Siège social" required class="md:col-span-2" />
                <x-admin.form-input name="ice" label="ICE" required inputmode="numeric" help="15 ou 16 chiffres." />
                <x-admin.form-input name="rc" label="RC" inputmode="numeric" />
                <x-admin.form-select name="category" label="Catégorie" :options="$categories->pluck('name', 'id')->all()" placeholder="Aucune catégorie" />
                <x-admin.file-upload name="logo" label="Logo" accept="image/png,image/jpeg" max-size="1 Mo" hint="PNG ou JPG, 1 Mo au plus." />
                <x-admin.textarea name="description" label="Description de la société" rows="4" class="md:col-span-2" />
            </div>

            <div class="flex flex-col-reverse gap-3 border-t border-line pt-6 sm:flex-row sm:justify-end">
                <x-admin.button href="{{ route('admin:clients.index') }}" variant="secondary">Annuler</x-admin.button>
                <x-admin.button type="submit">Enregistrer le client</x-admin.button>
            </div>
        </form>
    </div>
@endsection
