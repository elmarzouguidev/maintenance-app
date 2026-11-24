{{--
    Organisation and agency choices of the context switcher. Each choice is a submit button of
    a POST form: organisation_id / agency_id with the CSRF token. Expects $contextSwitcher
    (see layouts/admin.blade.php) and $context ("menu" or "sheet").
--}}
@php
    $organisations = $contextSwitcher['organisations'] ?? [];
    $agencies = $contextSwitcher['agencies'] ?? [];
@endphp

@if (count($organisations) > 1)
    <p class="px-2 pb-1 pt-1 text-caption font-semibold text-fg-muted" id="{{ $context }}-organisations">Organisation</p>
    <form method="POST" action="{{ $contextSwitcher['organisationAction'] ?? '#' }}">
        @csrf
        <ul aria-labelledby="{{ $context }}-organisations">
            @foreach ($organisations as $organisationChoice)
                @php($isCurrent = (bool) ($organisationChoice['current'] ?? false))
                <li>
                    <button type="submit" name="organisation_id" value="{{ $organisationChoice['value'] }}"
                        class="admin-menu-item min-h-11 justify-between" @if ($isCurrent) aria-current="true" @endif>
                        <span @class(['truncate', 'font-semibold' => $isCurrent])>{{ $organisationChoice['name'] }}</span>
                        @if ($isCurrent)
                            <x-admin.icon name="check" class="size-4 text-accent" />
                        @endif
                    </button>
                </li>
            @endforeach
        </ul>
    </form>
@else
    <p class="px-2 pb-1 pt-1 text-caption font-semibold text-fg-muted">Organisation</p>
    <p class="px-3 pb-1 text-small font-semibold text-fg">{{ $contextSwitcher['organisation'] }}</p>
@endif

<p class="px-2 pb-1 pt-3 text-caption font-semibold text-fg-muted" id="{{ $context }}-agencies">Agence</p>
@if ($agencies !== [])
    <form method="POST" action="{{ $contextSwitcher['agencyAction'] ?? '#' }}">
        @csrf
        <ul aria-labelledby="{{ $context }}-agencies">
            @foreach ($agencies as $agencyChoice)
                @php($isCurrent = (bool) ($agencyChoice['current'] ?? false))
                <li>
                    <button type="submit" name="agency_id" value="{{ $agencyChoice['value'] }}"
                        class="admin-menu-item min-h-11 justify-between" @if ($isCurrent) aria-current="true" @endif>
                        <span @class(['truncate', 'font-semibold' => $isCurrent])>{{ $agencyChoice['name'] }}</span>
                        @if ($isCurrent)
                            <x-admin.icon name="check" class="size-4 text-accent" />
                        @endif
                    </button>
                </li>
            @endforeach
        </ul>
    </form>
@else
    <p class="px-3 pb-1 text-small text-fg-muted">Aucune agence accessible.</p>
@endif
