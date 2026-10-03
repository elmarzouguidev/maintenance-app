@props([
    'new_tickets_diagnostic_tech' => 0,
    'new_tickets_diagnostic' => 0,
])

@php
    $user = auth()->user();
    $diagnosticCount = $user->can('diagnostic.browse') || $user->can('diagnostic.manage_assigned')
        ? $new_tickets_diagnostic
        : $new_tickets_diagnostic_tech;
@endphp

@canany(['diagnostic.assigned.browse', 'diagnostic.browse', 'diagnostic.manage_assigned'])
    <li>
        <a href="{{ route('admin:diagnostic.index') }}" class="waves-effect" key="t-diagnostic-list">
            <i class="bx bx-task"></i>
            @if ($diagnosticCount)
                <span class="badge rounded-pill bg-warning float-end" aria-label="Diagnostics en attente">.</span>
            @endif
            <span>{{ __('navbar.diagnostic') }}</span>
        </a>
    </li>
@endcanany
