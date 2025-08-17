@props(['tickets'])

@php
    $tabs = [
        [
            'id' => 'diagnistique-open-tech',
            'key' => 'ouvert',
            'label' => 'Diagnostique ouverts',
            'badge_class' => 'bg-info',
            'active' => true
        ],
        [
            'id' => 'diagnistique-wait-tech',
            'key' => 'en-attent-de-devis',
            'label' => 'En attente de devis',
            'badge_class' => 'bg-warning',
            'active' => false
        ],
        [
            'id' => 'diagnistique-attend-bc-tech',
            'key' => 'en-attent-de-bc',
            'label' => 'En attente de BC',
            'badge_class' => 'bg-info',
            'active' => false
        ],
        [
            'id' => 'diagnistique-repare-tech',
            'key' => 'a-preparer',
            'label' => 'Produits a réparer',
            'badge_class' => 'bg-info',
            'active' => false
        ],
        [
            'id' => 'diagnistique-repare-encours-tech',
            'key' => 'encours-de-reparation',
            'label' => 'Produits en cours de réparation',
            'badge_class' => 'bg-info',
            'active' => false
        ],
        [
            'id' => 'diagnistique-repare-done-tech',
            'key' => 'pret-a-etre-livre',
            'label' => 'Produits réparé',
            'badge_class' => 'bg-success',
            'active' => false
        ],
        [
            'id' => 'diagnistique-cancled-tech',
            'key' => 'annuler',
            'label' => 'Annuler par L\'administration',
            'badge_class' => 'bg-danger',
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
