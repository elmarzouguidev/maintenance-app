@props(['tickets'])

@php
    $tabPanes = [
        [
            'id' => 'diagnistique-open-tech',
            'key' => 'ouvert',
            'active' => true,
            'showDiagnoseButton' => true
        ],
        [
            'id' => 'diagnistique-wait-tech',
            'key' => 'en-attent-de-devis',
            'active' => false,
            'showDiagnoseButton' => false
        ],
        [
            'id' => 'diagnistique-attend-bc-tech',
            'key' => 'en-attent-de-bc',
            'active' => false,
            'showDiagnoseButton' => false
        ],
        [
            'id' => 'diagnistique-repare-tech',
            'key' => 'a-preparer',
            'active' => false,
            'showDiagnoseButton' => true
        ],
        [
            'id' => 'diagnistique-repare-encours-tech',
            'key' => 'encours-de-reparation',
            'active' => false,
            'showDiagnoseButton' => true
        ],
        [
            'id' => 'diagnistique-repare-done-tech',
            'key' => 'pret-a-etre-livre',
            'active' => false,
            'showDiagnoseButton' => false
        ],
        [
            'id' => 'diagnistique-cancled-tech',
            'key' => 'annuler',
            'active' => false,
            'showDiagnoseButton' => false
        ]
    ];
@endphp

<div class="tab-content p-3 text-muted">
    @foreach($tabPanes as $pane)
        <div class="tab-pane {{ $pane['active'] ? 'active' : '' }}" 
             id="{{ $pane['id'] }}" 
             role="tabpanel">
            <x-diagnostic.ticket-table 
                :tickets="$tickets" 
                :ticket-key="$pane['key']"
                :show-diagnose-button="$pane['showDiagnoseButton']" />
        </div>
    @endforeach
</div>
