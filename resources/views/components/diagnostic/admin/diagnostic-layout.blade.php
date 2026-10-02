@props(['tickets', 'clients', 'techniciens'])

@php
    $stages = [
        [
            'id' => 'diagnostic-admin-estimate',
            'key' => 'en-attent-de-devis',
            'label' => 'En attente de devis',
            'group' => 'Décision client',
            'icon' => 'mdi-file-document-outline',
            'actionText' => 'Traiter le ticket',
            'actionClass' => 'btn-primary',
            'showActionButton' => true,
            'active' => true,
        ],
        [
            'id' => 'diagnostic-admin-order',
            'key' => 'en-attent-de-bc',
            'label' => 'En attente du bon de commande',
            'group' => 'Approvisionnement',
            'icon' => 'mdi-clipboard-list-outline',
            'actionText' => 'Traiter le ticket',
            'actionClass' => 'btn-primary',
            'showActionButton' => true,
            'active' => false,
        ],
        [
            'id' => 'diagnostic-admin-return',
            'key' => 'retour-non-reparable',
            'label' => 'Retour non réparable',
            'group' => 'Décision finale',
            'icon' => 'mdi-alert-circle-outline',
            'actionText' => '',
            'actionClass' => '',
            'showActionButton' => false,
            'active' => false,
        ],
    ];

    $totalTickets = collect($stages)->sum(fn ($stage) => count(data_get($tickets, $stage['key'], [])));
@endphp

<section class="diagnostic-dashboard">
    <div class="diagnostic-dashboard-heading">
        <div>
            <span class="diagnostic-eyebrow">ESPACE GESTION</span>
            <h1>Pilotage des diagnostics</h1>
            <p>Retrouvez les dossiers à traiter et filtrez-les par client, technicien ou période.</p>
        </div>
        <div class="diagnostic-total-card">
            <span class="diagnostic-total-icon"><i class="mdi mdi-clipboard-list-outline" aria-hidden="true"></i></span>
            <span><strong>{{ $totalTickets }}</strong><small>dossiers à suivre</small></span>
        </div>
    </div>

    <x-diagnostic.admin.filters :clients="$clients" :techniciens="$techniciens" />

    <div class="card diagnostic-board">
        <div class="card-body p-3 p-xl-4">
            <div class="row g-4">
                <div class="col-12 col-xl-3">
                    <aside class="diagnostic-workflow-nav" aria-label="Étapes de traitement">
                        <div class="diagnostic-workflow-nav-heading">
                            <span>À SUIVRE</span>
                            <small>Choisissez une file</small>
                        </div>
                        <x-diagnostic.admin.tab-nav :tickets="$tickets" :tabs="$stages" />
                    </aside>
                </div>
                <div class="col-12 col-xl-9">
                    <x-diagnostic.admin.tab-content :tickets="$tickets" :tabs="$stages" />
                </div>
            </div>
        </div>
    </div>
</section>
