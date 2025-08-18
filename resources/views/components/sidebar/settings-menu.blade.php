@hasanyrole('Admin|SuperAdmin')
    <li class="menu-title" key="t-components">{{ __('Paramètres') }}</li>

    <li>
        <a href="{{ route('commercial:companies.index') }}" class="waves-effect">
            <i class="bx bx-building"></i>
            <span key="t-companies">{{ __('navbar.companies') }}</span>
        </a>
    </li>
    
    <li>
        <a href="{{ route('admin:admins') }}" class="waves-effect">
            <i class="bx bx-user-circle"></i>
            <span key="t-authentication">Utilisateurs</span>
        </a>
    </li>
@endhasanyrole
