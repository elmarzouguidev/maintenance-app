@extends('layouts.guest')

@section('title', 'Nouveau mot de passe')

@section('content')
    <div class="w-full max-w-md">
        <div class="admin-card p-6 sm:p-8">
            <x-admin.guest-heading title="Nouveau mot de passe" description="Choisissez le nouveau mot de passe de votre compte." />

            <form method="POST" action="{{ route('password.update') }}" autocomplete="off" class="space-y-4">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <x-admin.form-input name="email" type="email" label="E-mail" :value="$email ?? ''" autocomplete="email" required readonly />
                <x-admin.form-input name="password" type="password" label="Nouveau mot de passe" autocomplete="new-password" required autofocus />
                <x-admin.form-input name="password_confirmation" type="password" id="password-confirm" label="Confirmer le mot de passe" autocomplete="new-password" required />

                <x-admin.button type="submit" class="w-full">Enregistrer le mot de passe</x-admin.button>
            </form>
        </div>
    </div>
@endsection
