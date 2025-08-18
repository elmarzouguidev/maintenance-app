@props(['new_tickets' => 0, 'tickets_livrable' => 0])

<li class="menu-title" key="t-apps">Gestion de ticket</li>

<li>
    <a href="javascript: void(0);" class="has-arrow waves-effect">
        <i class="bx bx-task"></i>
        <span class="badge rounded-pill bg-warning float-end">{{ $new_tickets }}</span>
        <span key="t-tasks">{{ __('navbar.tickets') }}</span>
    </a>
    <ul class="sub-menu" aria-expanded="false">

        @can('ticket.browse')
            <li>
                <a href="{{ route('admin:tickets.list.old') }}" key="t-task-list">
                    {{ __('navbar.tickets') }}
                    <span class="badge rounded-pill bg-warning float-end">{{ $new_tickets }}</span>
                </a>
            </li>
        @endcan

        @if (auth()->user()->hasAnyRole('SuperAdmin', 'Admin'))
            <li>
                <a href="{{ route('admin:tickets.livrable') }}" class="waves-effect">
                    @if ($tickets_livrable)
                        <span class="badge rounded-pill bg-warning float-end">.</span>
                    @endif
                    <span key="t-pret">Livraison</span>
                </a>
            </li>
        @endif
    </ul>
</li>
