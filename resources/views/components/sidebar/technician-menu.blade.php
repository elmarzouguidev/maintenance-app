@props(['new_tickets_diagnostic_tech' => 0])

@role('Technicien')
    <li>
        <a href="javascript: void(0);" class="has-arrow waves-effect">
            <i class="bx bx-task"></i>

            @if ($new_tickets_diagnostic_tech)
                <span class="badge rounded-pill bg-warning float-end">.</span>
            @endif
            <span key="t-diagnostic">{{ __('navbar.diagnostic_tech') }}</span>
        </a>
        <ul class="sub-menu" aria-expanded="false">
            <li>
                <a href="{{ route('admin:diagnostic.index') }}" key="t-diagnostic-list">
                    {{ __('navbar.diagnostic_tech') }}
                </a>
            </li>

            <li>
                <a href="{{ route('admin:rapports.index') }}" key="t-rapports-list">
                    <i class="bx bx-task"></i>
                    Rapports techniques
                </a>
            </li>
        </ul>
    </li>
@endrole
