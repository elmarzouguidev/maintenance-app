@props(['tickets'])

@php
    $tabPanes = [
        [
            'id' => 'diagnistique-attend',
            'key' => 'en-attent-de-devis',
            'active' => true,
            'showActionButton' => true,
            'actionText' => 'Traiter le ticket',
            'actionClass' => 'btn-primary'
        ],
        [
            'id' => 'diagnistique-attend-bc',
            'key' => 'en-attent-de-bc',
            'active' => false,
            'showActionButton' => true,
            'actionText' => 'Traiter le ticket',
            'actionClass' => 'btn-primary'
        ],
        [
            'id' => 'diagnistique-non',
            'key' => 'retour-non-reparable',
            'active' => false,
            'showActionButton' => false,
            'actionText' => '',
            'actionClass' => ''
        ]
    ];
@endphp

<div class="tab-content p-3 text-muted">
    @foreach($tabPanes as $pane)
        <div class="tab-pane {{ $pane['active'] ? 'active' : '' }}" 
             id="{{ $pane['id'] }}" 
             role="tabpanel">
            <x-diagnostic.admin.ticket-table 
                :tickets="$tickets" 
                :ticket-key="$pane['key']"
                :show-action-button="$pane['showActionButton']"
                :action-text="$pane['actionText']"
                :action-class="$pane['actionClass']" />
        </div>
    @endforeach
</div>
