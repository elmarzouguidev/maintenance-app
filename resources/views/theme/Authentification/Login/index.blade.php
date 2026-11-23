@extends('theme.layouts.auth')

@section('title', 'Login')

@section('card-title', 'Se connecter')

@section('content')
    <form action="{{ route('admin:auth:loginPost') }}" method="post" class="flex flex-col gap-4">
        @csrf

        <x-input name="email" type="email" label="Email" placeholder="Enter email" autocomplete="email" />

        <x-input name="password" type="password" label="Mot de password" placeholder="Enter password" autocomplete="current-password" revealable />

        <x-checkbox name="remember" id="remember-check" label="Se souvenir de moi" />

        <x-button block>Se connecter</x-button>

        @if (Route::has('forgotpassword'))
            <div class="mt-2 text-center">
                <a href="{{ route('forgotpassword') }}" class="text-muted hover:text-body">
                    <i class="mdi mdi-lock me-1" aria-hidden="true"></i>
                    Mot de passe oublié ?
                </a>
            </div>
        @endif
    </form>
@endsection
