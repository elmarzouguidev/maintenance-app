@extends('theme.layouts.auth')

@section('title', 'Reset Password')

@section('card-title', 'Reset Password')

@section('content')
    <form action="{{ route('password.update') }}" method="post" autocomplete="off" class="flex flex-col gap-4">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <x-input name="email" type="email" label="Email" :value="$email ?? null" required autocomplete="email" readonly />

        <x-input name="password" type="password" label="Password" required autocomplete="new-password" autofocus revealable />

        <x-input name="password_confirmation" type="password" id="password-confirm" label="Confirm Password" required autocomplete="new-password" revealable />

        <div class="text-end">
            <x-button class="min-w-27.5">Reset</x-button>
        </div>
    </form>
@endsection
