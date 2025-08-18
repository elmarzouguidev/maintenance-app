@props(['tickets'])

@php
    $tabPanes = [
        [
            'id' => 'diagnistique-open-tech',
            'key' => 'ouvert',
            'active' => true,
            'showDiagnoseButton' => true,
            'buttonText' => 'Diagnostiquer',
            'buttonClass' => 'btn-warning',
            'diagnoseUrl' => null
        ],
        [
            'id' => 'diagnistique-wait-tech',
            'key' => 'en-attent-de-devis',
            'active' => false,
            'showDiagnoseButton' => false,
            'buttonText' => '',
            'buttonClass' => '',
            'diagnoseUrl' => null
        ],
        [
            'id' => 'diagnistique-attend-bc-tech',
            'key' => 'en-attent-de-bc',
            'active' => false,
            'showDiagnoseButton' => false,
            'buttonText' => '',
            'buttonClass' => '',
            'diagnoseUrl' => null
        ],
        [
            'id' => 'diagnistique-repare-tech',
            'key' => 'a-preparer',
            'active' => false,
            'showDiagnoseButton' => true,
            'buttonText' => 'commencer la réparation',
            'buttonClass' => 'btn-warning',
            'diagnoseUrl' => null
        ],
        [
            'id' => 'diagnistique-repare-encours-tech',
            'key' => 'encours-de-reparation',
            'active' => false,
            'showDiagnoseButton' => true,
            'buttonText' => 'continue la réparation',
            'buttonClass' => 'btn-warning',
            'diagnoseUrl' => null
        ],
        [
            'id' => 'diagnistique-repare-done-tech',
            'key' => 'pret-a-etre-livre',
            'active' => false,
            'showDiagnoseButton' => true,
            'buttonText' => 'réparation terminée',
            'buttonClass' => 'btn-warning',
            'diagnoseUrl' => '#'
        ],
        [
            'id' => 'diagnistique-cancled-tech',
            'key' => 'annuler',
            'active' => false,
            'showDiagnoseButton' => false,
            'buttonText' => '',
            'buttonClass' => '',
            'diagnoseUrl' => null
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
                :show-diagnose-button="$pane['showDiagnoseButton']"
                :button-text="$pane['buttonText']"
                :button-class="$pane['buttonClass']"
                :diagnose-url="$pane['diagnoseUrl']" />
        </div>
    @endforeach
</div>
