@props(['new_tickets_diagnostic' => 0])

@hasanyrole('Admin|SuperAdmin')
    <li>
        <a href="{{ route('admin:diagnostic.index') }}" class="waves-effect" key="t-diagnostic-list">
            <i class="bx bx-task"></i>
            @if ($new_tickets_diagnostic)
                <span class="badge rounded-pill bg-warning float-end">.</span>
            @endif
            <span key="t-diagnostic">{{ __('navbar.diagnostic') }}</span>
        </a>
    </li>
@endhasanyrole
