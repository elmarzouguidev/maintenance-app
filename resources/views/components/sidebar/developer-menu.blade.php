@if (auth()->user()->hasRole('Developper'))
    <li>
        <a href="javascript: void(0);" class="waves-effect">
            <i class="bx bx-lock-alt"></i>
            <span key="t-authentication">{{ __('navbar.roles_permissions') }}</span>
        </a>
        <ul class="sub-menu" aria-expanded="false">
            <li>
                <a href="{{ route('admin:permissions-roles.index') }}" key="t-login">
                    {{ __('navbar.roles') }}
                </a>
            </li>
            {{--<li>
                <a href="{{ route('admin:permissions-roles.permissions') }}" key="t-login">
                    {{ __('navbar.permissions') }}
                </a>
            </li>--}}
        </ul>
    </li>
@endif
