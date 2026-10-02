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

<section class="diagnostic-dashboard">
    <div class="diagnostic-dashboard-heading">
        <div>
            <span class="diagnostic-eyebrow">ESPACE ATELIER</span>
            <h1>Diagnostic et réparations</h1>
            <p>Suivez chaque ticket et passez rapidement à l’étape suivante.</p>
        </div>
        <div class="diagnostic-total-card">
            <span class="diagnostic-total-icon"><i class="mdi mdi-clipboard-list-outline" aria-hidden="true"></i></span>
            <span><strong>{{ $totalTickets }}</strong><small>tickets dans votre flux</small></span>
        </div>
    </div>

    <div class="card diagnostic-board">
        <div class="card-body p-3 p-xl-4">
            <div class="row g-4">
                <div class="col-12 col-xl-3">
                    <aside class="diagnostic-workflow-nav" aria-label="Étapes du flux de travail">
                        <div class="diagnostic-workflow-nav-heading">
                            <span>VOTRE PARCOURS</span>
                            <small>Choisissez une étape</small>
                        </div>
                        <div class="nav nav-pills flex-xl-column diagnostic-stage-nav" role="tablist" aria-orientation="vertical">
                            @foreach ($stages as $stage)
                                @php
                                    $stageCount = count(data_get($tickets, $stage['key'], []));
                                @endphp
                                <a class="nav-link diagnostic-stage-link {{ $stage['active'] ? 'active' : '' }}"
                                    id="{{ $stage['id'] }}-tab" data-bs-toggle="tab"
                                    href="#{{ $stage['id'] }}" role="tab"
                                    aria-controls="{{ $stage['id'] }}" aria-selected="{{ $stage['active'] ? 'true' : 'false' }}">
                                    <span class="diagnostic-stage-icon"><i class="mdi {{ $stage['icon'] }}" aria-hidden="true"></i></span>
                                    <span class="diagnostic-stage-copy">
                                        <span>{{ $stage['label'] }}</span>
                                        <small>{{ $stage['group'] }}</small>
                                    </span>
                                    <span class="diagnostic-stage-count">{{ $stageCount }}</span>
                                </a>
                            @endforeach
                        </div>
                    </aside>
                </div>

                <div class="col-12 col-xl-9">
                    <div class="tab-content diagnostic-stage-content">
                        @foreach ($stages as $stage)
                            <section class="tab-pane fade {{ $stage['active'] ? 'show active' : '' }}"
                                id="{{ $stage['id'] }}" role="tabpanel"
                                aria-labelledby="{{ $stage['id'] }}-tab" tabindex="0">
                                <header class="diagnostic-stage-heading">
                                    <div>
                                        <span>{{ $stage['group'] }}</span>
                                        <h2>{{ $stage['label'] }}</h2>
                                    </div>
                                    <span class="diagnostic-stage-heading-count">
                                        {{ count(data_get($tickets, $stage['key'], [])) }} ticket(s)
                                    </span>
                                </header>
                                <div class="diagnostic-table-wrap">
                                    @include($stage['view'])
                                </div>
                            </section>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
