@extends('theme.layouts.auth')

@section('title', 'Mot de passe oublié')

@section('card-title', 'Reset Password')

@section('content')
    <form action="{{ route('forgotpasswordPost') }}" method="post" autocomplete="off" class="flex flex-col gap-4">
        @csrf

        <x-input name="email" type="email" id="useremail" label="Email" placeholder="enter email" required autocomplete="email" autofocus />

        <div class="text-end">
            <x-button class="min-w-27.5">Reset</x-button>
        </div>
    </form>
@endsection
