<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow" />
    <title>@yield('title', 'Plateforme') - Plateforme {{ config('brand.name') }}</title>

    @include('layouts.partials.favicon')

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>

{{--
    Platform (super-admin) shell. Built on the same tokens and kit as the tenant shell, but
    distinct on purpose so nobody confuses the two contexts:
    - a dark brand context band across the top, labelled "Plateforme";
    - a light sidebar (the tenant sidebar is dark);
    - a "Plateforme" chip next to the menu button.

    View data (all optional): $navigation (same shape as layouts/admin.blade.php),
    $homeUrl, $accountMenu (list of ['href', 'label', 'icon']), $logoutAction (POST url).
    The signed-in user comes from auth($platformGuard ?? null).
--}}
@php
    $platformUser = auth($platformGuard ?? null)->user();
    $displayName = $platformUser?->name ?? 'Administrateur plateforme';
    $initial = strtoupper(substr($displayName, 0, 1));
    $navigation = $navigation ?? [];
    $homeUrl = $homeUrl ?? url('/');
    $accountMenu = $accountMenu ?? [];
    $logoutAction = $logoutAction ?? null;
@endphp

<body class="admin-body font-sans" x-data="{ profileOpen: false }">
    {{-- Context band: always visible, says where you are. --}}
    <div class="bg-brand-deep text-on-accent">
        <div class="flex min-h-9 items-center gap-2 px-4 text-caption lg:px-6">
            <x-admin.icon name="shield" class="size-4 shrink-0" />
            <p class="min-w-0 truncate"><span class="font-semibold">Plateforme {{ config('brand.name') }}</span><span class="opacity-80"> · administration de toutes les organisations, hors espace agence</span></p>
        </div>
    </div>

    <div class="flex min-h-screen">
        {{-- Desktop sidebar: light, to tell the platform apart from the dark tenant sidebar. --}}
        <aside class="sticky top-0 hidden h-screen w-64 shrink-0 flex-col border-e border-line bg-surface lg:flex">
            @include('layouts.partials.platform-brand')
            <div class="flex-1 overflow-y-auto overscroll-contain px-3 pb-6">
                @include('layouts.partials.platform-nav', ['navId' => 'platform-nav-desktop'])
            </div>
        </aside>

        {{-- Phone/tablet navigation drawer (native dialog: focus trap, Escape, focus return). --}}
        <dialog x-ref="navDrawer" class="admin-drawer bg-surface" aria-label="Menu de la plateforme"
            @click="if ($event.target === $el) $el.close()">
            <div class="flex h-full flex-col">
                <div class="flex items-center justify-between pe-3">
                    @include('layouts.partials.platform-brand')
                    <x-admin.icon-button icon="close" label="Fermer le menu" x-on:click="$refs.navDrawer.close()" />
                </div>
                <div class="flex-1 overflow-y-auto overscroll-contain px-3 pb-6">
                    @include('layouts.partials.platform-nav', ['navId' => 'platform-nav-drawer'])
                </div>
            </div>
        </dialog>

        <div class="flex min-w-0 flex-1 flex-col">
            <header class="admin-topbar">
                <div class="flex h-16 items-center gap-2 px-4 lg:px-6">
                    <x-admin.icon-button icon="menu" label="Ouvrir le menu" class="lg:hidden" x-on:click="$refs.navDrawer.showModal()" />
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-brand px-2.5 py-1 text-caption font-semibold text-on-accent">
                        <x-admin.icon name="shield" class="size-3.5" />
                        Plateforme
                    </span>

                    {{-- Account menu --}}
                    <div class="relative ms-auto" @keydown.escape="if (profileOpen) { profileOpen = false; $refs.profileButton.focus() }">
                        <button type="button" x-ref="profileButton" class="admin-button admin-button-ghost admin-button-md gap-2 px-1.5"
                            x-on:click="profileOpen = ! profileOpen" aria-haspopup="true" :aria-expanded="profileOpen.toString()">
                            <span class="flex size-8 items-center justify-center rounded-full bg-brand text-caption font-semibold text-on-accent" aria-hidden="true">{{ $initial }}</span>
                            <span class="hidden max-w-48 truncate text-small font-medium text-fg sm:inline">{{ $displayName }}</span>
                            <span class="sr-only sm:hidden">Menu du compte</span>
                            <x-admin.icon name="chevron-down" class="size-4 text-fg-muted" />
                        </button>

                        <div x-show="profileOpen" x-cloak @click.outside="profileOpen = false"
                            x-transition:enter="admin-popover-enter" x-transition:enter-start="opacity-0 motion-safe:scale-96" x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition-opacity duration-100 ease-out" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                            class="admin-popover absolute end-0 top-full z-40 mt-2 w-64 origin-top-right p-1">
                            <div class="border-b border-line px-3 py-2.5">
                                <p class="truncate text-small font-medium text-fg">{{ $displayName }}</p>
                                <p class="truncate text-caption text-fg-muted">{{ $platformUser?->email }}</p>
                            </div>
                            @if ($accountMenu !== [])
                                <div class="py-1">
                                    @foreach ($accountMenu as $menuItem)
                                        <a href="{{ $menuItem['href'] }}" class="admin-menu-item">
                                            <x-admin.icon :name="$menuItem['icon']" class="size-4 text-fg-muted" /> {{ $menuItem['label'] }}
                                        </a>
                                    @endforeach
                                </div>
                            @endif
                            @if ($logoutAction)
                                <div class="border-t border-line pt-1">
                                    <form method="POST" action="{{ $logoutAction }}">
                                        @csrf
                                        <button type="submit" class="admin-menu-item admin-menu-item-danger">
                                            <x-admin.icon name="logout" class="size-4" /> Se déconnecter
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </header>

            <x-messages />

            <main class="admin-main">
                @yield('content')
            </main>

            <footer class="border-t border-line bg-surface">
                <div class="px-4 py-4 text-caption text-fg-muted lg:px-8">
                    &copy; {{ date('Y') }} <a href="{{ url('/') }}" class="font-semibold text-brand underline-offset-2 hover:underline">{{ config('brand.name') }}</a> · Administration plateforme
                </div>
            </footer>
        </div>
    </div>
</body>
</html>
