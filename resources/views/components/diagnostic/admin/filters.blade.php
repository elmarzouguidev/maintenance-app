@props(['clients', 'techniciens'])

@php
    $dateRange = array_pad(explode(',', request()->input('appFilter.DateBetween', ''), 2), 2, '');
    $hasDiagnosticFilters = collect([
        request()->input('appFilter.GetClient'),
        request()->input('appFilter.GetEtat'),
        request()->input('appFilter.GetTechnicien'),
        request()->input('appFilter.DateBetween'),
    ])->contains(fn ($value) => filled($value));
@endphp

<section class="card diagnostic-filter-card {{ $hasDiagnosticFilters ? 'diagnostic-filter-card--active' : '' }}">
    <div class="card-body">
        <div class="diagnostic-filter-heading">
            <div class="d-flex align-items-center gap-3">
                <span class="diagnostic-filter-icon"><i class="mdi mdi-filter-variant" aria-hidden="true"></i></span>
                <div>
                    <h2>Filtres</h2>
                    <p>Affinez la liste par client, état, technicien ou période.</p>
                </div>
            </div>
            <a href="{{ route('admin:diagnostic.index') }}" class="btn btn-light btn-sm">
                <i class="mdi mdi-filter-remove-outline me-1" aria-hidden="true"></i>Effacer
            </a>
        </div>

        <form id="diagnosticFilterForm" method="GET" action="{{ route('admin:diagnostic.index') }}">
            <div class="row g-3 align-items-end">
                <div class="col-12 col-md-6 col-xl-3">
                    <label for="diagnosticClientFilter" class="form-label">Client</label>
                    <select class="form-select select2" name="appFilter[GetClient]" id="diagnosticClientFilter">
                        <option value="">Tous les clients</option>
                        @foreach ($clients as $client)
                            <option value="{{ $client->id }}"
                                @selected((string) $client->id === request()->input('appFilter.GetClient'))>
                                {{ $client->entreprise }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12 col-md-6 col-xl-2">
                    <label for="diagnosticStateFilter" class="form-label">État</label>
                    <select class="form-select" name="appFilter[GetEtat]" id="diagnosticStateFilter">
                        <option value="">Tous les états</option>
                        <option value="reparable" @selected(request()->input('appFilter.GetEtat') === 'reparable')>Réparable</option>
                        <option value="non-reparable" @selected(request()->input('appFilter.GetEtat') === 'non-reparable')>Non réparable</option>
                    </select>
                </div>

                <div class="col-12 col-md-6 col-xl-3">
                    <label for="diagnosticTechnicianFilter" class="form-label">Technicien</label>
                    <select class="form-select" name="appFilter[GetTechnicien]" id="diagnosticTechnicianFilter">
                        <option value="">Tous les techniciens</option>
                        @foreach ($techniciens as $technicien)
                            <option value="{{ $technicien->id }}"
                                @selected((string) $technicien->id === request()->input('appFilter.GetTechnicien'))>
                                {{ $technicien->full_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12 col-md-6 col-xl-3">
                    <label class="form-label" for="diagnosticDateStart">Période</label>
                    <div class="input-daterange input-group" id="diagnosticDateRange" data-date-format="yyyy-mm-dd"
                        data-date-autoclose="true" data-provide="datepicker">
                        <input type="text" class="form-control" id="diagnosticDateStart"
                            placeholder="Début" value="{{ $dateRange[0] }}" autocomplete="off">
                        <span class="input-group-text">à</span>
                        <input type="text" class="form-control" id="diagnosticDateEnd"
                            placeholder="Fin" value="{{ $dateRange[1] }}" autocomplete="off">
                    </div>
                    <input type="hidden" name="appFilter[DateBetween]" id="diagnosticDateBetween"
                        value="{{ request()->input('appFilter.DateBetween', '') }}"
                        @disabled(! $hasDiagnosticFilters || ! request()->filled('appFilter.DateBetween'))>
                </div>

                <div class="col-12 col-xl-1 d-grid">
                    <button type="submit" class="btn btn-primary">
                        <i class="mdi mdi-magnify me-1" aria-hidden="true"></i>Filtrer
                    </button>
                </div>
            </div>
        </form>
    </div>
</section>
