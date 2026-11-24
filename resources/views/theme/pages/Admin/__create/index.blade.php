{{-- Add a user (K2, SuperAdmin): the kit card form. Same fields as before; one role at creation. --}}
@extends('layouts.admin')

@section('title', 'Ajouter un utilisateur')

@section('content')
    <div class="space-y-6">
        <x-admin.page-header title="Ajouter un utilisateur"
            :breadcrumbs="[['label' => 'Utilisateurs', 'href' => route('admin:admins')], ['label' => 'Ajouter']]" />

        <form method="POST" action="{{ route('admin:admins.createPost') }}" class="admin-card max-w-3xl space-y-6 p-5 sm:p-6">
            @csrf

            <div class="grid gap-4 md:grid-cols-2">
                <x-admin.form-input name="prenom" label="Prénom" required autocomplete="off" />
                <x-admin.form-input name="nom" label="Nom" required autocomplete="off" />
                <x-admin.form-input name="email" type="email" label="E-mail" required inputmode="email" autocomplete="off" />
                <x-admin.form-input name="telephone" type="tel" label="Téléphone" inputmode="tel" autocomplete="off" help="Numéro marocain, ex. 0612345678." />
                <x-admin.form-input name="password" type="password" label="Mot de passe" required autocomplete="new-password" />
                <x-admin.form-select name="role" label="Rôle" :options="$roles->pluck('name', 'name')->all()" placeholder="Choisir un rôle" required />
            </div>

            <div class="flex flex-col-reverse gap-3 border-t border-line pt-6 sm:flex-row sm:justify-end">
                <x-admin.button href="{{ route('admin:admins') }}" variant="secondary">Annuler</x-admin.button>
                <x-admin.button type="submit">Ajouter l’utilisateur</x-admin.button>
            </div>
        </form>
    </div>
@endsection
