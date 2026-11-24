@extends('layouts.guest')

@section('title', 'Connexion')

@section('content')
    <div class="w-full max-w-md">
        <div class="admin-card p-6 sm:p-8">
            <x-admin.guest-heading eyebrow="Espace agence" title="Connexion" description="Saisissez vos identifiants pour accéder à votre espace." />

            <form method="POST" action="#" class="space-y-4">
                <x-admin.form-input name="email" type="email" label="E-mail" placeholder="vous@exemple.com" autocomplete="email" inputmode="email" required />
                <x-admin.form-input name="password" type="password" label="Mot de passe" autocomplete="current-password" required />
                <div class="flex items-center justify-between gap-3">
                    <label class="flex items-center gap-2 text-small text-fg"><input type="checkbox" name="remember" class="admin-checkbox"> Rester connecté</label>
                    <a href="#" class="rounded-control text-small font-medium text-accent hover:underline focus-visible:outline-2 focus-visible:outline-focus">Mot de passe oublié ?</a>
                </div>
                <x-admin.button type="submit" class="w-full">Se connecter</x-admin.button>
            </form>
        </div>
    </div>
@endsection
