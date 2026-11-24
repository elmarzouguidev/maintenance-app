{{--
    Guest shell (sign-in, password reset, two-factor, onboarding): centred content, flash
    messages top-end, footer. Sections: title, content. Stacks: styles, scripts.
--}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Connexion') - {{ config('brand.name') }}</title>

    @include('layouts.partials.favicon')

    <meta name="robots" content="noindex, nofollow" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>

<body class="admin-body flex min-h-screen flex-col font-sans">

    @if (session()->has('success') || session()->has('error') || session()->has('warning') || session()->has('status'))
        <div class="fixed end-4 top-4 z-50 w-full max-w-sm space-y-2 ps-8">
            @if (session('status'))
                <x-admin.alert type="success" :message="session('status')" />
            @endif
            @if (session('success'))
                <x-admin.alert type="success" :message="session('success')" />
            @endif
            @if (session('error'))
                <x-admin.alert type="error" :message="session('error')" />
            @endif
            @if (session('warning'))
                <x-admin.alert type="warning" :message="session('warning')" />
            @endif
        </div>
    @endif

    <main class="flex flex-1 items-center justify-center px-4 py-10">
        @yield('content')
    </main>

    <footer class="border-t border-line bg-surface">
        <div class="flex flex-col items-center justify-between gap-2 px-6 py-4 text-caption text-fg-muted sm:flex-row">
            <p>
                &copy; {{ date('Y') }}
                <a href="{{ url('/') }}" class="font-semibold text-fg hover:underline">{{ config('brand.name') }}</a>
                <span class="tabular-nums">{{ config('brand.version') }}</span>
            </p>
            <p>
                Développé par
                <a href="{{ config('brand.vendor.url') }}" target="_blank" rel="noopener" class="font-semibold text-fg hover:underline">{{ config('brand.vendor.name') }}</a>
            </p>
        </div>
    </footer>

    @stack('scripts')
</body>

</html>
