@props(['tickets_livrable' => 0])

@if (auth()->user()->hasRole('Reception'))
    <li>
        <a href="{{ route('admin:clients.index') }}" key="t-clients">
            <i class="bx bx-user"></i>
            {{ __('navbar.clients') }}
        </a>
    </li>

    <li>
        <a href="{{ route('admin:tickets.livrable') }}" class="waves-effect">
            @if ($tickets_livrable)
                <span class="badge rounded-pill bg-warning float-end">.</span>
            @endif
            <span key="t-pret">Prét a la livraison</span>
        </a>
    </li>
@endif
