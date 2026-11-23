<!DOCTYPE html>
{{--
    Tailwind layout for the guest pages (login, forgot / reset password). Replaces the standalone
    Bootstrap documents in theme/Authentification (MIGRATION_AUDIT.md §11, B2).
    No jQuery or Bootstrap JS; Alpine comes from @livewireScripts.
    Sections: title, card-title (text in the soft header), content (card body), after-card.
--}}
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <title>@yield('title') - CASAMAINTENANCE APP</title>
    <meta name="robots" content="noindex, nofollow">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body>
    <main class="px-3 py-12 sm:pt-[4.5rem]">
        <div class="mx-auto w-full max-w-[480px] 2xl:max-w-[550px]">
            <div class="overflow-hidden rounded-sm bg-white shadow-card">
                <div class="grid grid-cols-12 bg-primary/25">
                    <div class="col-span-7 p-6">
                        <h5 class="mb-2 text-primary">@yield('card-title')</h5>
                    </div>
                    <div class="col-span-5 self-end">
                        <img src="{{ asset('assets/images/profile-img.png') }}" alt="" class="h-auto max-w-full">
                    </div>
                </div>

                <div class="px-5 pb-5">
                    <a href="{{ route('home') }}" class="-mt-[26px] mb-6 flex size-18 items-center justify-center rounded-full bg-light" aria-label="{{ config('app.name') }}">
                        <img src="{{ asset('assets/images/logo.svg') }}" alt="" class="h-[34px] rounded-full">
                    </a>

                    <div class="p-2">
                        <x-flash-messages />

                        @yield('content')
                    </div>
                </div>
            </div>

            @yield('after-card')

            @include('theme.Authentification.auth_footer')
        </div>
    </main>

    @livewireScripts
</body>

</html>
