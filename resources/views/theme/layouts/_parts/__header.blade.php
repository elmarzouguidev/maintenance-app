<header id="page-topbar">
    <div class="navbar-header">
        <div class="d-flex align-items-center">
            <div class="navbar-brand-box">
                <a href="{{ route('admin:home') }}" class="logo logo-dark" aria-label="Accueil Casamaintenance">
                    <span class="logo-sm">
                        <img src="{{ asset('images/applogo.png') }}" alt="Casamaintenance" height="48">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ asset('images/applogo.png') }}" alt="Casamaintenance" height="48">
                    </span>
                </a>

                <a href="{{ route('admin:home') }}" class="logo logo-light" aria-label="Accueil Casamaintenance">
                    <span class="logo-sm">
                        <img src="{{ asset('images/applogo.png') }}" alt="Casamaintenance" height="48">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ asset('images/applogo.png') }}" alt="Casamaintenance" height="48">
                    </span>
                </a>
            </div>

            <button
                type="button"
                class="btn btn-sm px-3 font-size-16 header-item waves-effect"
                id="vertical-menu-btn"
                aria-label="Basculer la navigation"
                aria-controls="sidebar-menu"
                aria-expanded="false">
                <i class="fa fa-fw fa-bars" aria-hidden="true"></i>
            </button>
        </div>

        @if (defaultCompany())
            <div class="d-none d-md-flex flex-grow-1 justify-content-center px-3">
                <span class="badge bg-light text-dark border px-3 py-2">
                    <i class="bx bx-building me-1" aria-hidden="true"></i>
                    {{ defaultCompany()->name }}
                </span>
            </div>
        @else
            <div class="flex-grow-1"></div>
        @endif

        <div class="d-flex align-items-center">
            <a
                class="btn header-item d-none d-md-inline-flex align-items-center gap-2"
                href="https://wedoapp.ma"
                target="_blank"
                rel="noopener noreferrer"
                aria-label="Contacter le support">
                <i class="bx bx-support font-size-18" aria-hidden="true"></i>
                <span>Support</span>
            </a>

            <button
                type="button"
                class="btn header-item d-none d-lg-inline-flex align-items-center"
                data-app-fullscreen
                aria-label="Activer le plein écran"
                title="Plein écran">
                <i class="bx bx-fullscreen font-size-18" aria-hidden="true"></i>
            </button>

            <div class="dropdown d-inline-block">
                <button
                    type="button"
                    class="btn header-item d-inline-flex align-items-center gap-2"
                    id="page-header-user-dropdown"
                    data-bs-toggle="dropdown"
                    aria-haspopup="true"
                    aria-expanded="false">
                    <img
                        class="rounded-circle header-profile-user"
                        src="{{ asset('assets/images/logo.png') }}"
                        alt="">
                    <span class="d-none d-sm-inline-block text-start">
                        <span class="d-block">{{ auth()->user()->nom ?? '' }}</span>
                        <small class="text-muted">{{ auth()->user()->getRoleNames()->first() ?? 'User' }}</small>
                    </span>
                    <i class="mdi mdi-chevron-down d-none d-sm-inline-block" aria-hidden="true"></i>
                </button>

                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="page-header-user-dropdown">
                    <a class="dropdown-item" href="{{ route('admin:profile.index') }}">
                        <i class="bx bx-user font-size-16 align-middle me-1" aria-hidden="true"></i>
                        <span>Mon profil</span>
                    </a>
                    <a class="dropdown-item" href="{{ route('admin:profile.settings') }}">
                        <i class="bx bx-wrench font-size-16 align-middle me-1" aria-hidden="true"></i>
                        <span>Paramètres</span>
                    </a>

                    <div class="dropdown-divider"></div>

                    <form method="post" action="{{ route('admin:auth:logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger">
                            <i class="bx bx-power-off font-size-16 align-middle me-1" aria-hidden="true"></i>
                            <span>Déconnexion</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
