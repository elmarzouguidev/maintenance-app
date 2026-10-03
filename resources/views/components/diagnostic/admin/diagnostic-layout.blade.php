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

@include('theme.pages.Diagnostic.section_0_page_title')

<x-diagnostic.admin.filters :clients="$clients" :techniciens="$techniciens" />

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-wrap align-items-start justify-content-between gap-2 mb-3">
                    <div>
                        <h5 class="card-title">Dossiers de diagnostic</h5>
                        <p class="card-title-desc mb-0">Consultez les dossiers regroupés par étape de traitement.</p>
                    </div>
                    <span class="badge badge-soft-primary rounded-pill px-3 py-2">
                        <i class="mdi mdi-clipboard-list-outline me-1" aria-hidden="true"></i>
                        {{ $totalTickets }} dossier(s)
                    </span>
                </div>

                <x-diagnostic.admin.tab-nav :tickets="$tickets" :tabs="$stages" />
                <x-diagnostic.admin.tab-content :tickets="$tickets" :tabs="$stages" />
            </div>
        </div>
    </div>
</div>
