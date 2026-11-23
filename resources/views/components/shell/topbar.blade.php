{{-- Topbar of the Tailwind layout. Mirrors theme/layouts/_parts/__header (Skote #page-topbar). --}}
@php
    $company = defaultCompany();
@endphp

<header class="fixed inset-x-0 top-0 z-[1002] bg-white shadow-card">
    <div class="flex h-topbar items-center justify-between pe-3">
        <div class="flex h-full items-center">
            <a href="{{ route('admin:home') }}" class="flex h-full shrink-0 items-center justify-center bg-sidebar px-3 lg:w-sidebar lg:px-6 lg:sidebar-collapsed:w-sidebar-collapsed lg:sidebar-collapsed:px-2">
                <img src="{{ asset('images/applogo.png') }}" alt="{{ config('app.name') }}" class="hidden h-[60px] lg:block lg:sidebar-collapsed:hidden">
                <img src="{{ asset('images/applogo.png') }}" alt="{{ config('app.name') }}" class="h-10 max-w-[54px] object-contain lg:hidden lg:sidebar-collapsed:block">
            </a>

            <button type="button" data-test="sidebar-toggle" class="h-full px-3 text-16 text-topbar-item" x-on:click="$store.sidebar.toggle()" aria-label="Afficher ou masquer le menu">
                <i class="fa fa-fw fa-bars" aria-hidden="true"></i>
            </button>
        </div>

        @if ($company)
            <h4 class="mb-0 min-w-0 truncate px-2">{{ $company->name }}</h4>
        @endif

        <div class="flex h-full items-center">
            <x-dropdown align="end" size="lg" class="hidden h-full lg:inline-block">
                <x-slot:trigger>
                    <button type="button" class="h-full px-3 text-[22px] text-topbar-item" aria-label="Applications">
                        <i class="bx bx-customize" aria-hidden="true"></i>
                    </button>
                </x-slot:trigger>

                <div class="px-2">
                    <a href="https://wedoapp.ma" target="_blank" rel="noopener" class="block rounded-[3px] border border-transparent px-3 py-3 text-center text-muted hover:border-line">
                        Support
                    </a>
                </div>
            </x-dropdown>

            <button
                type="button"
                class="hidden h-full px-3 text-[22px] text-topbar-item lg:inline-block"
                x-on:click="document.fullscreenElement ? document.exitFullscreen() : document.documentElement.requestFullscreen()"
                aria-label="Plein écran"
            >
                <i class="bx bx-fullscreen" aria-hidden="true"></i>
            </button>

            <x-dropdown align="end" class="h-full">
                <x-slot:trigger>
                    <button type="button" class="flex h-full items-center gap-1 px-3 text-topbar-item">
                        <img class="size-9 rounded-full bg-card-line p-[3px]" src="{{ asset('assets/images/logo.png') }}" alt="">
                        <span class="ms-1 hidden xl:inline">{{ auth()->user()->nom ?? '' }}</span>
                        <i class="mdi mdi-chevron-down hidden xl:inline" aria-hidden="true"></i>
                        <strong class="ms-1 hidden xl:inline">{{ auth()->user()->getRoleNames()->first() ?? 'User' }}</strong>
                    </button>
                </x-slot:trigger>

                <x-dropdown-item href="#" icon="bx bx-user">Profile</x-dropdown-item>
                <x-dropdown-item :href="route('admin:profile.settings')" icon="bx bx-wrench">Settings</x-dropdown-item>
                <x-dropdown-divider />
                <form method="POST" action="{{ route('admin:auth:logout') }}">
                    @csrf
                    <x-dropdown-item type="submit" icon="bx bx-power-off" danger>Logout</x-dropdown-item>
                </form>
            </x-dropdown>
        </div>
    </div>
</header>
