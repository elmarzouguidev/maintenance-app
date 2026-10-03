@props(['tickets'])

@php
    $stages = [
        [
            'id' => 'diagnostic-stage-open',
            'key' => 'ouvert',
            'label' => 'À diagnostiquer',
            'group' => 'Diagnostic',
            'icon' => 'mdi-stethoscope',
            'view' => 'theme.pages.Diagnostic.__tap_view.tables.diagnistique-open',
            'active' => true,
        ],
        [
            'id' => 'diagnostic-stage-estimate',
            'key' => 'en-attent-de-devis',
            'label' => 'En attente de devis',
            'group' => 'Après le diagnostic',
            'icon' => 'mdi-file-document-outline',
            'view' => 'theme.pages.Diagnostic.__tap_view.tables.diagnistique-wait',
            'active' => false,
        ],
        [
            'id' => 'diagnostic-stage-purchase-order',
            'key' => 'en-attent-de-bc',
            'label' => 'En attente du bon de commande',
            'group' => 'Après le diagnostic',
            'icon' => 'mdi-clipboard-list-outline',
            'view' => 'theme.pages.Diagnostic.__tap_view.tables.diagnistique-attend-bc',
            'active' => false,
        ],
        [
            'id' => 'diagnostic-stage-to-repair',
            'key' => 'a-preparer',
            'label' => 'À réparer',
            'group' => 'Réparation',
            'icon' => 'mdi-tools',
            'view' => 'theme.pages.Diagnostic.__tap_view.tables.diagnistique-a-reparer',
            'active' => false,
        ],
        [
            'id' => 'diagnostic-stage-in-repair',
            'key' => 'encours-de-reparation',
            'label' => 'En cours de réparation',
            'group' => 'Réparation',
            'icon' => 'mdi-wrench',
            'view' => 'theme.pages.Diagnostic.__tap_view.tables.diagnistique-encours-de-reparation',
            'active' => false,
        ],
        [
            'id' => 'diagnostic-stage-ready',
            'key' => 'pret-a-etre-livre',
            'label' => 'Réparation terminée',
            'group' => 'Réparation',
            'icon' => 'mdi-check-circle-outline',
            'view' => 'theme.pages.Diagnostic.__tap_view.tables.diagnistique-pret-a-livre',
            'active' => false,
        ],
        [
            'id' => 'diagnostic-stage-cancelled',
            'key' => 'annuler',
            'label' => 'Annulés',
            'group' => 'Historique',
            'icon' => 'mdi-close-circle-outline',
            'view' => 'theme.pages.Diagnostic.__tap_view.tables.diagnistique-cancled',
            'active' => false,
        ],
    ];

    $totalTickets = collect($stages)->sum(fn ($stage) => count(data_get($tickets, $stage['key'], [])));
@endphp

@include('theme.pages.Diagnostic.section_0_page_title')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-wrap align-items-start justify-content-between gap-2 mb-3">
                    <div>
                        <h5 class="card-title">Mon flux de travail</h5>
                        <p class="card-title-desc mb-0">Sélectionnez une étape pour consulter les tickets correspondants.</p>
                    </div>
                    <span class="badge badge-soft-primary rounded-pill px-3 py-2">
                        <i class="mdi mdi-clipboard-list-outline me-1" aria-hidden="true"></i>
                        {{ $totalTickets }} ticket(s)
                    </span>
                </div>

                <ul class="nav nav-tabs nav-tabs-custom flex-nowrap overflow-auto" role="tablist" aria-label="Étapes du flux de travail">
                    @foreach ($stages as $stage)
                        @php
                            $stageCount = count(data_get($tickets, $stage['key'], []));
                        @endphp
                        <li class="nav-item flex-shrink-0" role="presentation">
                            <a class="nav-link d-flex align-items-center gap-2 {{ $stage['active'] ? 'active' : '' }}"
                                id="{{ $stage['id'] }}-tab" data-bs-toggle="tab"
                                href="#{{ $stage['id'] }}" role="tab"
                                aria-controls="{{ $stage['id'] }}" aria-selected="{{ $stage['active'] ? 'true' : 'false' }}">
                                <i class="mdi {{ $stage['icon'] }} font-size-16" aria-hidden="true"></i>
                                <span class="text-nowrap">{{ $stage['label'] }}</span>
                                <span class="badge badge-soft-primary rounded-pill">{{ $stageCount }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>

                <div class="tab-content pt-4">
                    @foreach ($stages as $stage)
                        @php
                            $stageCount = count(data_get($tickets, $stage['key'], []));
                        @endphp
                        <section class="tab-pane fade {{ $stage['active'] ? 'show active' : '' }}"
                            id="{{ $stage['id'] }}" role="tabpanel"
                            aria-labelledby="{{ $stage['id'] }}-tab" tabindex="0">
                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                                <div>
                                    <span class="text-muted font-size-12 text-uppercase">{{ $stage['group'] }}</span>
                                    <h5 class="mb-0">{{ $stage['label'] }}</h5>
                                </div>
                                <span class="text-muted">{{ $stageCount }} ticket(s)</span>
                            </div>
                            <div class="table-responsive">
                                @include($stage['view'])
                            </div>
                        </section>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
