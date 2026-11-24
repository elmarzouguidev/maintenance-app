{{--
    Tenant / back-office shell: dark brand sidebar (desktop), navigation drawer (phone/tablet),
    white topbar with an optional context switcher, search, notifications and account menu,
    flash messages, footer and a phone bottom bar.

    Sections: title, content, hide-bottom-bar (screens with their own sticky action bar),
    legacy-page-title + page-title (pages without x-admin.page-header). Stacks: styles, scripts.

    View data (share it from a view composer; every key is optional). The layout makes no
    permission decision: pass only what the current user may see.
      $navigation      list of ['label' => ?string, 'items' => list of ['href', 'label', 'icon', 'active' => bool]]
      $bottomBar       list of ['href', 'label', 'icon', 'current' => bool] (phone shortcuts; the Menu button is added)
      $contextSwitcher null, or ['organisation' => string, 'agency' => ?string,
                         'organisations' => list of ['value', 'name', 'current' => bool], 'organisationAction' => url,
                         'agencies' => list of ['value', 'name', 'current' => bool], 'agencyAction' => url]
      $searchAction    url of a GET search form (name "q"), or null
      $headerNotifications list of ['title', 'body', 'time', 'unread' => bool, 'markReadUrl' => ?url]
      $headerUnreadNotificationsCount int
      $notificationsUrl    url of the full list, or null
      $accountMenu     list of ['href', 'label', 'icon']
      $logoutAction    url (POST), or null
      $organisationAccessNotice null, or ['type' => alert type, 'message' => string]
    Brand: config('brand.*') (ui-kit/brand/brand.php).
--}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Espace agence') - {{ config('brand.name') }}</title>
    <meta name="robots" content="noindex, nofollow" />

    @include('layouts.partials.favicon')

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>

@php
    $currentUser = auth()->user();
    $displayName = $currentUser->name ?? 'Compte';
    $initial = strtoupper(substr($displayName, 0, 1));

    $navigation = $navigation ?? [];
    $bottomBar = $bottomBar ?? [];
    $headerNotifications = collect($headerNotifications ?? []);
    $headerUnreadNotificationsCount = $headerUnreadNotificationsCount ?? $headerNotifications->where('unread', true)->count();
    $notificationsUrl = $notificationsUrl ?? null;
    $contextSwitcher = $contextSwitcher ?? null;
    $searchAction = $searchAction ?? null;
    $accountMenu = $accountMenu ?? [];
    $logoutAction = $logoutAction ?? (Route::has('logout') ? route('logout') : null);

    $showBottomBar = $bottomBar !== [] && ! View::hasSection('hide-bottom-bar');
    $showContextSwitcher = $contextSwitcher !== null;
@endphp

<body class="admin-body font-sans"
    x-data="{ notificationsOpen: false, profileOpen: false, contextOpen: false, isDesktop: () => window.matchMedia('(min-width: 48rem)').matches }">
    <div class="admin-shell">
        {{-- Desktop sidebar --}}
        <aside class="admin-sidebar sticky top-0 hidden h-screen w-64 shrink-0 lg:flex">
            @include('layouts.partials.admin-brand')
            <div class="flex-1 overflow-y-auto overscroll-contain px-3 pb-6">
                @include('layouts.partials.admin-nav', ['navId' => 'nav-desktop'])
            </div>
        </aside>

        {{-- Phone/tablet navigation drawer --}}
        <dialog x-ref="navDrawer" class="admin-sidebar admin-drawer" aria-label="Menu"
            @click="if ($event.target === $el) $el.close()">
            <div class="flex h-full flex-col">
                <div class="flex items-center justify-between pe-3">
                    @include('layouts.partials.admin-brand')
                    <button type="button" class="admin-icon-button text-sidebar-text hover:bg-sidebar-hover hover:text-sidebar-strong" x-on:click="$refs.navDrawer.close()" aria-label="Fermer le menu" title="Fermer le menu">
                        <x-admin.icon name="close" />
                    </button>
                </div>
                <div class="flex-1 overflow-y-auto overscroll-contain px-3 pb-6">
                    @include('layouts.partials.admin-nav', ['navId' => 'nav-drawer'])
                </div>
            </div>
        </dialog>

        <div @class(['flex min-w-0 flex-1 flex-col', 'admin-has-bottom-bar' => $showBottomBar])>
            <header class="admin-topbar">
                <div class="flex h-16 items-center gap-2 px-4 lg:px-6">
                    <x-admin.icon-button icon="menu" label="Ouvrir le menu" class="lg:hidden" x-on:click="$refs.navDrawer.showModal()" />

                    @if ($showContextSwitcher)
                        {{-- Organisation / agency switcher, every width: dropdown from md, bottom sheet below. --}}
                        <div class="relative min-w-0" @keydown.escape="if (contextOpen) { contextOpen = false; $refs.contextButton.focus() }">
                            <button type="button" x-ref="contextButton"
                                class="admin-button admin-button-ghost admin-button-md min-w-0 max-w-full gap-2 px-2 text-start"
                                aria-haspopup="true" :aria-expanded="contextOpen.toString()"
                                x-on:click="isDesktop() ? (contextOpen = ! contextOpen) : $refs.contextSheet.showModal()">
                                <x-admin.icon name="pin" class="size-5 text-accent" />
                                <span class="min-w-0 leading-tight">
                                    <span class="block truncate text-caption font-medium text-fg-muted">{{ $contextSwitcher['organisation'] }}</span>
                                    <span class="block truncate text-small font-semibold text-fg">{{ $contextSwitcher['agency'] ?? 'Aucune agence' }}</span>
                                </span>
                                <span class="sr-only">, changer d’organisation ou d’agence</span>
                                <x-admin.icon name="chevron-up-down" class="size-4 text-fg-muted" />
                            </button>
                            <div x-show="contextOpen" x-cloak @click.outside="contextOpen = false"
                                x-transition:enter="admin-popover-enter" x-transition:enter-start="opacity-0 motion-safe:scale-96" x-transition:enter-end="opacity-100 scale-100"
                                x-transition:leave="transition-opacity duration-100 ease-out" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                                class="admin-popover admin-popover-scroll absolute start-0 top-full z-40 mt-2 w-80 origin-top-left p-2">
                                @include('layouts.partials.admin-context-options', ['context' => 'menu'])
                            </div>
                        </div>
                    @endif

                    <div class="ms-auto flex items-center gap-1">
                        @if ($searchAction)
                            <form method="GET" action="{{ $searchAction }}" role="search" class="hidden md:block">
                                <label for="header-search" class="sr-only">Rechercher</label>
                                <div class="relative">
                                    <span class="pointer-events-none absolute inset-y-0 start-3 flex items-center text-fg-muted">
                                        <x-admin.icon name="search" class="size-4" />
                                    </span>
                                    <input id="header-search" type="search" name="q" value="{{ request('q') }}" placeholder="Client, plaque, réservation…" class="admin-form-input w-56 ps-9 lg:w-72">
                                </div>
                            </form>
                            <x-admin.icon-button icon="search" label="Rechercher" href="{{ $searchAction }}" class="md:hidden" />
                        @endif

                        {{-- Notifications --}}
                        <div class="relative" @keydown.escape="if (notificationsOpen) { notificationsOpen = false; $refs.notificationsButton.focus() }">
                            <button type="button" x-ref="notificationsButton" class="admin-icon-button relative"
                                x-on:click="notificationsOpen = ! notificationsOpen; profileOpen = false; contextOpen = false"
                                aria-haspopup="true" :aria-expanded="notificationsOpen.toString()"
                                aria-label="Notifications{{ $headerUnreadNotificationsCount > 0 ? ', '.$headerUnreadNotificationsCount.' non lue(s)' : '' }}" title="Notifications">
                                <x-admin.icon name="bell" />
                                @if ($headerUnreadNotificationsCount > 0)
                                    <span class="absolute end-1 top-1 inline-flex min-w-4.5 items-center justify-center rounded-full bg-status-danger px-1 text-caption font-semibold leading-4.5 text-on-accent tabular-nums" aria-hidden="true">
                                        {{ $headerUnreadNotificationsCount > 99 ? '99+' : $headerUnreadNotificationsCount }}
                                    </span>
                                @endif
                            </button>

                            <div x-show="notificationsOpen" x-cloak @click.outside="notificationsOpen = false"
                                x-transition:enter="admin-popover-enter" x-transition:enter-start="opacity-0 motion-safe:scale-96" x-transition:enter-end="opacity-100 scale-100"
                                x-transition:leave="transition-opacity duration-100 ease-out" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                                class="admin-popover fixed inset-x-4 top-16 z-40 mt-1 origin-top sm:absolute sm:inset-x-auto sm:end-0 sm:top-full sm:mt-2 sm:w-96 sm:origin-top-right">
                                <div class="flex items-center justify-between gap-3 border-b border-line px-4 py-3">
                                    <div>
                                        <p class="text-small font-semibold text-fg">Notifications</p>
                                        <p class="text-caption text-fg-muted">{{ $headerUnreadNotificationsCount }} non lue(s)</p>
                                    </div>
                                    @if ($notificationsUrl)
                                        <a href="{{ $notificationsUrl }}" class="rounded-control text-caption font-medium text-accent hover:underline focus-visible:outline-2 focus-visible:outline-focus">Tout voir</a>
                                    @endif
                                </div>

                                @if ($headerNotifications->isNotEmpty())
                                    <ul class="admin-popover-scroll divide-y divide-line">
                                        @foreach ($headerNotifications as $headerNotification)
                                            @php($isUnread = (bool) ($headerNotification['unread'] ?? false))
                                            <li class="flex items-start gap-3 px-4 py-3">
                                                <span class="mt-1.5 size-2 shrink-0 rounded-full {{ $isUnread ? 'bg-accent' : 'bg-transparent' }}" aria-hidden="true"></span>
                                                <div class="min-w-0 flex-1">
                                                    <p class="text-small {{ $isUnread ? 'font-semibold text-fg' : 'text-fg-muted' }}">
                                                        @if ($isUnread)<span class="sr-only">Non lue : </span>@endif{{ $headerNotification['title'] ?? 'Sans titre' }}
                                                    </p>
                                                    <p class="mt-0.5 line-clamp-2 text-small text-fg-muted">{{ $headerNotification['body'] ?? 'Aucun contenu' }}</p>
                                                    <p class="mt-1 text-caption text-fg-muted">{{ $headerNotification['time'] ?? '' }}</p>
                                                </div>
                                                @if ($isUnread && ! empty($headerNotification['markReadUrl']))
                                                    <form method="POST" action="{{ $headerNotification['markReadUrl'] }}">
                                                        @csrf
                                                        <x-admin.button type="submit" variant="ghost" size="sm">Lire</x-admin.button>
                                                    </form>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="px-4 py-10 text-center text-small text-fg-muted">Aucune notification disponible.</p>
                                @endif
                            </div>
                        </div>

                        {{-- Account menu --}}
                        <div class="relative" @keydown.escape="if (profileOpen) { profileOpen = false; $refs.profileButton.focus() }">
                            <button type="button" x-ref="profileButton" class="admin-button admin-button-ghost admin-button-md gap-2 px-1.5"
                                x-on:click="profileOpen = ! profileOpen; notificationsOpen = false; contextOpen = false"
                                aria-haspopup="true" :aria-expanded="profileOpen.toString()">
                                <span class="flex size-8 items-center justify-center rounded-full bg-accent-soft text-caption font-semibold text-accent-strong" aria-hidden="true">{{ $initial }}</span>
                                <span class="hidden max-w-40 truncate text-small font-medium text-fg xl:inline">{{ $displayName }}</span>
                                <span class="sr-only xl:hidden">Menu du compte</span>
                            </button>

                            <div x-show="profileOpen" x-cloak @click.outside="profileOpen = false"
                                x-transition:enter="admin-popover-enter" x-transition:enter-start="opacity-0 motion-safe:scale-96" x-transition:enter-end="opacity-100 scale-100"
                                x-transition:leave="transition-opacity duration-100 ease-out" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                                class="admin-popover absolute end-0 top-full z-40 mt-2 w-64 origin-top-right p-1">
                                <div class="border-b border-line px-3 py-2.5">
                                    <p class="truncate text-small font-medium text-fg">{{ $displayName }}</p>
                                    <p class="truncate text-caption text-fg-muted">{{ $currentUser?->email }}</p>
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
                </div>
            </header>

            @if ($organisationAccessNotice ?? null)
                <div class="px-4 pt-5 lg:px-8">
                    <x-admin.alert :type="$organisationAccessNotice['type']" :message="$organisationAccessNotice['message']" />
                </div>
            @endif

            <x-messages />

            <main class="admin-main">
                @hasSection('legacy-page-title')
                    {{-- Pages without x-admin.page-header keep a visible title. --}}
                    <h1 class="admin-page-title mb-6">@yield('page-title')</h1>
                @endif
                @yield('content')
            </main>

            <footer class="border-t border-line bg-surface">
                <div class="flex flex-col items-center justify-between gap-2 px-4 py-4 text-caption text-fg-muted sm:flex-row lg:px-8">
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
        </div>
    </div>

    @if ($showContextSwitcher)
        {{-- Phone bottom sheet for the organisation/agency switcher (native <dialog>: focus trap, Escape). --}}
        <dialog x-ref="contextSheet" class="admin-sheet" aria-labelledby="context-sheet-title"
            @click="if ($event.target === $el) $el.close()">
            <div class="flex items-center justify-between border-b border-line px-4 py-3">
                <p id="context-sheet-title" class="text-section-title font-semibold">Changer de contexte</p>
                <x-admin.icon-button icon="close" label="Fermer" x-on:click="$refs.contextSheet.close()" />
            </div>
            <div class="overflow-y-auto overscroll-contain p-2">
                @include('layouts.partials.admin-context-options', ['context' => 'sheet'])
            </div>
        </dialog>
    @endif

    @if ($showBottomBar)
        {{-- Phone bottom bar: hidden by @section('hide-bottom-bar') on screens with their own sticky actions. --}}
        <nav aria-label="Accès rapide" class="admin-bottom-bar">
            @foreach ($bottomBar as $item)
                <a href="{{ $item['href'] }}" class="admin-bottom-bar-item" @if ($item['current'] ?? false) aria-current="page" @endif>
                    <x-admin.icon :name="$item['icon']" />
                    <span>{{ $item['label'] }}</span>
                </a>
            @endforeach
            <button type="button" class="admin-bottom-bar-item" x-on:click="$refs.navDrawer.showModal()">
                <x-admin.icon name="menu" />
                <span>Menu</span>
            </button>
        </nav>
    @endif

    @include('layouts.date_picker_js')
    @stack('scripts')
</body>

</html>
