@props(['tickets_livrable' => 0])

@can('client.browse')
    <li>
        <a href="{{ route('admin:clients.index') }}" key="t-clients">
            <i class="bx bx-user"></i>
            {{ __('navbar.clients') }}
        </a>
    </li>
@endcan

@can('ticket.delivery.browse')
    <li>
        <a href="{{ route('admin:tickets.livrable') }}" class="waves-effect">
            @if ($tickets_livrable)
                <span class="badge rounded-pill bg-warning float-end">.</span>
            @endif
            <span key="t-pret">Prét a la livraison</span>
        </a>
    </li>
@endcan
