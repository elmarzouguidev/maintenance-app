@props(['tickets'])

@php
    $tabs = [
        [
            'id' => 'diagnistique-attend',
            'key' => 'en-attent-de-devis',
            'label' => 'En attente de devis',
            'badge_class' => 'bg-info',
            'active' => true
        ],
        [
            'id' => 'diagnistique-attend-bc',
            'key' => 'en-attent-de-bc',
            'label' => 'En attente de BC',
            'badge_class' => 'bg-info',
            'active' => false
        ],
        [
            'id' => 'diagnistique-non',
            'key' => 'retour-non-reparable',
            'label' => 'Non reparable',
            'badge_class' => 'bg-warning',
            'active' => false
        ]
    ];
@endphp

<ul class="nav nav-tabs" role="tablist">
    @foreach($tabs as $tab)
        <li class="nav-item">
            <a class="nav-link {{ $tab['active'] ? 'active' : '' }}" 
               data-bs-toggle="tab" 
               href="#{{ $tab['id'] }}" 
               role="tab">
                <span class="badge rounded-pill {{ $tab['badge_class'] }} float-end" style="font-size: 1rem;">
                    @if (Arr::exists($tickets, $tab['key']))
                        {{ count($tickets[$tab['key']]) }}
                    @else
                        0
                    @endif
                </span>
                <span class="d-none d-sm-block">{{ $tab['label'] }}</span>
            </a>
        </li>
    @endforeach
</ul>
