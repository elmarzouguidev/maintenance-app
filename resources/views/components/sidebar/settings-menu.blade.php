@canany(['companies.browse', 'admin.browse'])
    <li class="menu-title" key="t-components">{{ __('Paramètres') }}</li>

    @can('companies.browse')
    <li>
        <a href="{{ route('commercial:companies.index') }}" class="waves-effect">
            <i class="bx bx-building"></i>
            <span key="t-companies">{{ __('navbar.companies') }}</span>
        </a>
    </li>
    @endcan
    
    @can('admin.browse')
    <li>
        <a href="{{ route('admin:admins') }}" class="waves-effect">
            <i class="bx bx-user-circle"></i>
            <span key="t-authentication">Utilisateurs</span>
        </a>
    </li>
    @endcan
@endcanany
