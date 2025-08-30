<style>
    .dropdown-item.active {
        background-color: #556ee6 !important;
        color: white !important;
    }
    .dropdown-item:hover {
        background-color: #f8f9fa;
    }
</style>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-4">
                    <i class="bx bx-filter-alt me-2"></i>Filtres
                </h5>

                <form>
                    <div class="d-flex flex-wrap gap-2 align-items-end">
                        <!-- Client Filter -->
                        <div class="flex-fill" style="min-width: 200px;">
                            <label for="clientsList" class="form-label">Client</label>
                            <select class="form-select select2" id="clientsList">
                                <option value="">Tous les clients</option>
                                @foreach ($clients as $client)
                                    <option value="{{ $client->id }}"
                                        {{ in_array($client->id, explode(',', request()->input('appFilter.GetClient'))) ? 'selected' : '' }}>
                                        {{ $client->entreprise }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Status Filter -->
                        <div class="flex-fill" style="min-width: 200px;">
                            <label for="statusList" class="form-label">Statut</label>
                            <select class="form-select" id="statusList">
                                <option value="">Tous les statuts</option>
                                <option value="{{ App\Constants\Status::NON_TRAITE }}"
                                    {{ in_array(App\Constants\Status::NON_TRAITE, explode(',', request()->input('appFilter.GetStatus')))? 'selected': '' }}>
                                    Non traité
                                </option>
                                <option value="{{ App\Constants\Status::EN_COURS_DE_DIAGNOSTIC }}"
                                    {{ in_array(App\Constants\Status::EN_COURS_DE_DIAGNOSTIC, explode(',', request()->input('appFilter.GetStatus')))? 'selected': '' }}>
                                    En cours de diagnostic
                                </option>
                                <option value="{{ App\Constants\Status::EN_COURS_DE_REPARATION }}"
                                    {{ in_array(App\Constants\Status::EN_COURS_DE_REPARATION, explode(',', request()->input('appFilter.GetStatus')))? 'selected': '' }}>
                                    En cours de réparation
                                </option>
                                <option value="{{ App\Constants\Status::RETOUR_DEVIS_NON_CONFIRME }}"
                                    {{ in_array(App\Constants\Status::RETOUR_DEVIS_NON_CONFIRME,explode(',', request()->input('appFilter.GetStatus')))? 'selected': '' }}>
                                    Devis refusé
                                </option>
                                <option value="{{ App\Constants\Status::EN_ATTENTE_DE_DEVIS }}"
                                    {{ in_array(App\Constants\Status::EN_ATTENTE_DE_DEVIS, explode(',', request()->input('appFilter.GetStatus')))? 'selected': '' }}>
                                    En attente de devis
                                </option>
                                <option value="{{ App\Constants\Status::EN_ATTENTE_DE_BON_DE_COMMAND }}"
                                    {{ in_array(App\Constants\Status::EN_ATTENTE_DE_BON_DE_COMMAND,explode(',', request()->input('appFilter.GetStatus')))? 'selected': '' }}>
                                    En attente de bon de command
                                </option>
                                <option value="{{ App\Constants\Status::DEVIS_CONFIRME }}"
                                    {{ in_array(App\Constants\Status::DEVIS_CONFIRME, explode(',', request()->input('appFilter.GetStatus')))? 'selected': '' }}>
                                    Devis Confirmé
                                </option>
                                <option value="{{ App\Constants\Status::A_REPARER }}"
                                    {{ in_array(App\Constants\Status::A_REPARER, explode(',', request()->input('appFilter.GetStatus')))? 'selected': '' }}>
                                    Confirmation de réparation
                                </option>
                                <option value="{{ App\Constants\Status::PRET_A_ETRE_LIVRE }}"
                                    {{ in_array(App\Constants\Status::PRET_A_ETRE_LIVRE, explode(',', request()->input('appFilter.GetStatus')))? 'selected': '' }}>
                                    Prêt à être livré
                                </option>
                                <option value="{{ App\Constants\Status::PRET_A_ETRE_FACTURE }}"
                                    {{ in_array(App\Constants\Status::PRET_A_ETRE_FACTURE, explode(',', request()->input('appFilter.GetStatus')))? 'selected': '' }}>
                                    Prêt à être Facturé
                                </option>
                                <option value="{{ App\Constants\Status::LIVRE }}"
                                    {{ in_array(App\Constants\Status::LIVRE, explode(',', request()->input('appFilter.GetStatus')))? 'selected': '' }}>
                                    Livré
                                </option>
                            </select>
                        </div>

                        <!-- Period Filter -->
                        <div class="flex-fill" style="min-width: 200px;">
                            <label class="form-label">Période</label>
                            <div class="btn-group w-100">
                                <button type="button" class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bx bx-calendar me-1"></i>
                                    @if(request()->input('appFilter.GetPeriod'))
                                        @php
                                            $period = request()->input('appFilter.GetPeriod');
                                            $periodNames = [
                                                '1' => 'Trimestre 1',
                                                '2' => 'Trimestre 2', 
                                                '3' => 'Trimestre 3',
                                                '4' => 'Trimestre 4'
                                            ];
                                        @endphp
                                        {{ $periodNames[$period] ?? 'Choisir la période' }}
                                    @elseif(request()->input('appFilter.GetStartDate') && request()->input('appFilter.GetEndDate'))
                                        @php
                                            $startDate = request()->input('appFilter.GetStartDate');
                                            $endDate = request()->input('appFilter.GetEndDate');
                                            $currentYear = now()->startOfYear()->format('d-m-Y') . ',' . now()->endOfYear()->format('d-m-Y');
                                            $lastYear = now()->subYear()->startOfYear()->format('d-m-Y') . ',' . now()->subYear()->endOfYear()->format('d-m-Y');
                                            $currentMonth = now()->startOfMonth()->format('d-m-Y') . ',' . now()->endOfMonth()->format('d-m-Y');
                                            $lastMonth = now()->subMonth()->startOfMonth()->format('d-m-Y') . ',' . now()->subMonth()->endOfMonth()->format('d-m-Y');
                                        @endphp
                                        @if($startDate . ',' . $endDate == $currentYear)
                                            Année en cours
                                        @elseif($startDate . ',' . $endDate == $lastYear)
                                            Année dernière
                                        @elseif($startDate . ',' . $endDate == $currentMonth)
                                            Ce mois
                                        @elseif($startDate . ',' . $endDate == $lastMonth)
                                            Mois dernier
                                        @else
                                            Période personnalisée
                                        @endif
                                    @else
                                        Choisir la période
                                    @endif
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item {{ request()->input('appFilter.GetPeriod') == '1' ? 'active' : '' }}" href="#" onclick="setPeriod(1)">Trimestre 1</a></li>
                                    <li><a class="dropdown-item {{ request()->input('appFilter.GetPeriod') == '2' ? 'active' : '' }}" href="#" onclick="setPeriod(2)">Trimestre 2</a></li>
                                    <li><a class="dropdown-item {{ request()->input('appFilter.GetPeriod') == '3' ? 'active' : '' }}" href="#" onclick="setPeriod(3)">Trimestre 3</a></li>
                                    <li><a class="dropdown-item {{ request()->input('appFilter.GetPeriod') == '4' ? 'active' : '' }}" href="#" onclick="setPeriod(4)">Trimestre 4</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item {{ request()->input('appFilter.GetStartDate') == now()->startOfYear()->format('d-m-Y') && request()->input('appFilter.GetEndDate') == now()->endOfYear()->format('d-m-Y') ? 'active' : '' }}" href="#" onclick="setYearPeriod()">Année en cours</a></li>
                                    <li><a class="dropdown-item {{ request()->input('appFilter.GetStartDate') == now()->subYear()->startOfYear()->format('d-m-Y') && request()->input('appFilter.GetEndDate') == now()->subYear()->endOfYear()->format('d-m-Y') ? 'active' : '' }}" href="#" onclick="setLastYearPeriod()">Année dernière</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item {{ request()->input('appFilter.GetStartDate') == now()->startOfMonth()->format('d-m-Y') && request()->input('appFilter.GetEndDate') == now()->endOfMonth()->format('d-m-Y') ? 'active' : '' }}" href="#" onclick="setMonthPeriod()">Ce mois</a></li>
                                    <li><a class="dropdown-item {{ request()->input('appFilter.GetStartDate') == now()->subMonth()->startOfMonth()->format('d-m-Y') && request()->input('appFilter.GetEndDate') == now()->subMonth()->endOfMonth()->format('d-m-Y') ? 'active' : '' }}" href="#" onclick="setLastMonthPeriod()">Mois dernier</a></li>
                                </ul>
                            </div>
                        </div>

                        <!-- Date Range Filter -->
                        <div class="flex-fill" style="min-width: 280px;">
                            <label class="form-label">Période personnalisée</label>
                            @if (request()->input('appFilter.GetStartDate') || request()->input('appFilter.GetEndDate'))
                                @php
                                    $startDate = request()->input('appFilter.GetStartDate') ?? '';
                                    $endDate = request()->input('appFilter.GetEndDate') ?? '';
                                @endphp
                            @endif
                            <div class="input-daterange input-group" id="datepicker6" data-date-format="dd-mm-yyyy"
                                data-date-autoclose="true" data-provide="datepicker"
                                data-date-container='#datepicker6'>
                                <input type="text" class="form-control" name="start" id="startDate"
                                    placeholder="Date de début" value="{{ $startDate ?? '' }}" />
                                <span class="input-group-text">à</span>
                                <input type="text" class="form-control" name="end" id="endDate"
                                    placeholder="Date de fin" value="{{ $endDate ?? '' }}" />
                            </div>
                        </div>

                        <!-- Etat Filters -->
                        <div class="flex-fill" style="min-width: 180px;">
                            <label class="form-label">État</label>
                            <div class="d-flex gap-3">
                                <div class="form-check">
                                    <input class="form-check-input" name="etat" value="{{ App\Constants\Etat::REPARABLE }}"
                                        type="radio" id="autoSizingCheck1"
                                        {{ in_array(App\Constants\Etat::REPARABLE, explode(',', request()->input('appFilter.GetEtat')))? 'checked': '' }}>
                                    <label class="form-check-label" for="autoSizingCheck1">
                                        Réparable
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" name="etat"
                                        value="{{ App\Constants\Etat::NON_REPARABLE }}" type="radio" id="autoSizingCheck2"
                                        {{ in_array(App\Constants\Etat::NON_REPARABLE, explode(',', request()->input('appFilter.GetEtat')))? 'checked': '' }}>
                                    <label class="form-check-label" for="autoSizingCheck2">
                                        Non Réparable
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Retour Filter -->
                        <div class="flex-fill" style="min-width: 120px;">
                            <div class="form-check">
                                <input class="form-check-input" name="has_router" type="checkbox" id="has_router"
                                {{ in_array('on', explode(',', request()->input('appFilter.GetRetour')))? 'checked': '' }}>
                                <label class="form-check-label" for="has_router">
                                    Retour
                                </label>
                            </div>
                        </div>

                        <!-- Filter Button -->
                        <div class="flex-shrink-0 ms-2">
                            <button type="submit" id="filterData" class="btn btn-primary">
                                <i class="bx bx-search me-1"></i>Filtrer
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
