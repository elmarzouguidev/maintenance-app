@props(['tickets', 'tabs'])

<ul class="nav nav-tabs nav-tabs-custom flex-nowrap overflow-auto" role="tablist" aria-label="Files de diagnostic">
    @foreach ($tabs as $tab)
        <li class="nav-item flex-shrink-0" role="presentation">
            <a class="nav-link d-flex align-items-center gap-2 {{ $tab['active'] ? 'active' : '' }}"
                id="{{ $tab['id'] }}-tab" data-bs-toggle="tab" href="#{{ $tab['id'] }}"
                role="tab" aria-controls="{{ $tab['id'] }}"
                aria-selected="{{ $tab['active'] ? 'true' : 'false' }}">
                <i class="mdi {{ $tab['icon'] }} font-size-16" aria-hidden="true"></i>
                <span class="text-nowrap">{{ $tab['label'] }}</span>
                <span class="badge badge-soft-primary rounded-pill">{{ count(data_get($tickets, $tab['key'], [])) }}</span>
            </a>
        </li>
    @endforeach
</ul>
