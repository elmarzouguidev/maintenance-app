<!DOCTYPE html>
{{--
    Tailwind layout. Replaces theme/layouts/app.blade.php module by module (MIGRATION_AUDIT.md §11).
    A page that extends this layout must only include Tailwind views: never a Bootstrap partial.
    Same sections as the legacy layout: @section('css'), @section('content'), @push('scripts').
--}}
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <title>CASAMAINTENANCE - {{ auth()->user()->getRoleNames()->first() ?? 'APP' }}</title>
    <meta name="robots" content="noindex, nofollow">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="shortcut icon" href="{{ asset('images/fav-icon.png') }}">

    {{-- jQuery stays available for the plugins (DataTables, Select2, …) until they are replaced. No Bootstrap JS here. --}}
    <script src="{{ asset('assets/libs/jquery/jquery.min.js') }}"></script>

    @yield('css')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body x-data x-bind:data-sidebar-collapsed="$store.sidebar.collapsed">
    <script>
        if (localStorage.getItem('sidebar-collapsed') === 'true') {
            document.body.setAttribute('data-sidebar-collapsed', 'true');
        }
    </script>

    <x-shell.preloader />

    <x-shell.topbar />

    <x-shell.sidebar
        :new_tickets="$new_tickets ?? 0"
        :tickets_livrable="$tickets_livrable ?? 0"
        :new_tickets_diagnostic_tech="$new_tickets_diagnostic_tech ?? 0"
        :new_tickets_diagnostic="$new_tickets_diagnostic ?? 0"
        :estimates_not_send="$estimates_not_send ?? 0"
    />

    <div class="flex min-h-screen flex-col lg:ms-sidebar lg:sidebar-collapsed:ms-sidebar-collapsed">
        <main class="flex-1 px-3 pb-6 pt-[calc(var(--spacing-topbar)+24px)]">
            @yield('content')
        </main>

        <x-shell.footer />
    </div>

    @livewireScripts

    @stack('scripts')
</body>

</html>
