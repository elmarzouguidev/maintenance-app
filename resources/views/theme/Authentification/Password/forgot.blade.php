@extends('layouts.guest')

@section('title', 'Mot de passe oublié')

@section('content')
    <div class="w-full max-w-md">
        <div class="admin-card p-6 sm:p-8">
            <x-admin.guest-heading title="Mot de passe oublié" description="Saisissez votre adresse e-mail : vous recevrez un lien pour choisir un nouveau mot de passe." />

            <form method="POST" action="{{ route('forgotpasswordPost') }}" autocomplete="off" class="space-y-4">
                @csrf

                <x-admin.form-input name="email" type="email" id="useremail" label="E-mail" placeholder="vous@exemple.com" autocomplete="email" inputmode="email" required autofocus />

                <x-admin.button type="submit" class="w-full">Envoyer le lien</x-admin.button>
            </form>

            <p class="mt-6 text-center">
                <a href="{{ route('admin:auth:login') }}" class="rounded-control text-small font-medium text-accent hover:underline focus-visible:outline-2 focus-visible:outline-focus">Retour à la connexion</a>
            </p>
        </div>
    </div>
@endsection
