@props(['tickets', 'tabs'])

<div class="nav nav-pills flex-xl-column diagnostic-stage-nav" role="tablist" aria-orientation="vertical">
    @foreach ($tabs as $tab)
        <a class="nav-link diagnostic-stage-link {{ $tab['active'] ? 'active' : '' }}"
            id="{{ $tab['id'] }}-tab" data-bs-toggle="tab" href="#{{ $tab['id'] }}"
            role="tab" aria-controls="{{ $tab['id'] }}"
            aria-selected="{{ $tab['active'] ? 'true' : 'false' }}">
            <span class="diagnostic-stage-icon"><i class="mdi {{ $tab['icon'] }}" aria-hidden="true"></i></span>
            <span class="diagnostic-stage-copy">
                <span>{{ $tab['label'] }}</span>
                <small>{{ $tab['group'] }}</small>
            </span>
            <span class="diagnostic-stage-count">{{ count(data_get($tickets, $tab['key'], [])) }}</span>
        </a>
    @endforeach
</div>
