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
                            <label for="clienterd" class="form-label">Client</label>
                            <select class="form-control select2 chk-filter-client" name="client" id="clienterd">
                                <option value="">Tous les clients</option>
                                <optgroup label="Clients">
                                    @foreach ($clients as $client)
                                        <option value="{{ $client->id }}"
                                            {{ in_array($client->id, explode(',', request()->input('appFilter.GetClient'))) ? 'selected' : '' }}>
                                            {{ $client->entreprise }}
                                        </option>
                                    @endforeach
                                </optgroup>
                            </select>
                        </div>

                        <!-- Status Filter -->
                        <div class="flex-fill" style="min-width: 180px;">
                            <label class="form-label">Statut</label>
                            <div class="d-flex gap-3">
                                <div class="form-check">
                                    <input class="form-check-input chk-filter" type="checkbox" name="status" value="non-paid"
                                        id="status-nonpaid"
                                        {{ in_array('non-paid', explode(',', request()->input('appFilter.GetStatus'))) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="status-nonpaid">
                                        A régler
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input chk-filter" type="checkbox" name="status" value="paid"
                                        id="status-paid"
                                        {{ in_array('paid', explode(',', request()->input('appFilter.GetStatus'))) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="status-paid">
                                        PAYÉE
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Company Filters -->
                        <div class="flex-fill" style="min-width: 200px;">
                            <label class="form-label">Société</label>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach ($companies as $company)
                                    <div class="form-check">
                                        <input class="form-check-input chk-filter" type="radio" name="company"
                                            id="company-{{ $company->id }}" value="{{ $company->id }}"
                                            {{ in_array($company->id, explode(',', request()->input('appFilter.GetCompany'))) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="company-{{ $company->id }}">
                                            {{ $company->name }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
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
                                    @elseif(request()->input('appFilter.DateBetween'))
                                        @php
                                            $dateRange = request()->input('appFilter.DateBetween');
                                            $currentYear = now()->startOfYear()->format('Y-m-d') . ',' . now()->endOfYear()->format('Y-m-d');
                                            $lastYear = now()->subYear()->startOfYear()->format('Y-m-d') . ',' . now()->subYear()->endOfYear()->format('Y-m-d');
                                            $currentMonth = now()->startOfMonth()->format('Y-m-d') . ',' . now()->endOfMonth()->format('Y-m-d');
                                            $lastMonth = now()->subMonth()->startOfMonth()->format('Y-m-d') . ',' . now()->subMonth()->endOfMonth()->format('Y-m-d');
                                        @endphp
                                        @if($dateRange == $currentYear)
                                            Année en cours
                                        @elseif($dateRange == $lastYear)
                                            Année dernière
                                        @elseif($dateRange == $currentMonth)
                                            Ce mois
                                        @elseif($dateRange == $lastMonth)
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
                                    <li><a class="dropdown-item {{ request()->input('appFilter.DateBetween') == now()->startOfYear()->format('Y-m-d') . ',' . now()->endOfYear()->format('Y-m-d') ? 'active' : '' }}" href="#" onclick="setYearPeriod()">Année en cours</a></li>
                                    <li><a class="dropdown-item {{ request()->input('appFilter.DateBetween') == now()->subYear()->startOfYear()->format('Y-m-d') . ',' . now()->subYear()->endOfYear()->format('Y-m-d') ? 'active' : '' }}" href="#" onclick="setLastYearPeriod()">Année dernière</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item {{ request()->input('appFilter.DateBetween') == now()->startOfMonth()->format('Y-m-d') . ',' . now()->endOfMonth()->format('Y-m-d') ? 'active' : '' }}" href="#" onclick="setMonthPeriod()">Ce mois</a></li>
                                    <li><a class="dropdown-item {{ request()->input('appFilter.DateBetween') == now()->subMonth()->startOfMonth()->format('Y-m-d') . ',' . now()->subMonth()->endOfMonth()->format('Y-m-d') ? 'active' : '' }}" href="#" onclick="setLastMonthPeriod()">Mois dernier</a></li>
                                </ul>
                            </div>
                        </div>

                        <!-- Date Range Filter -->
                        <div class="flex-fill" style="min-width: 280px;">
                            <label class="form-label">Période personnalisée</label>
                            @if (request()->input('appFilter.DateBetween'))
                                @php
                                    $dates = explode(',', request()->input('appFilter.DateBetween'));
                                @endphp
                            @endif
                            <div class="input-daterange input-group" id="datepicker6" data-date-format="yyyy-mm-dd"
                                data-date-autoclose="true" data-provide="datepicker"
                                data-date-container='#datepicker6'>
                                <input type="text" class="form-control" name="start" id="filterDateStart"
                                    placeholder="Date de début" value="{{ $dates[0] ?? '' }}" />
                                <span class="input-group-text">à</span>
                                <input type="text" class="form-control" name="end" id="filterDateEnd"
                                    placeholder="Date de fin" value="{{ $dates[1] ?? '' }}" />
                            </div>
                        </div>

                        <!-- Filter Button -->
                        <div class="flex-shrink-0 ms-2">
                            <button type="button" class="btn btn-primary" id="filterData">
                                <i class="bx bx-search me-1"></i>Filtrer
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
