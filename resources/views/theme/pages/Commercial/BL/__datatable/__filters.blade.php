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
                            <label for="clienter" class="form-label">Client</label>
                            <select class="form-select select2" id="clienter">
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
                        <div class="flex-fill" style="min-width: 180px;">
                            <label for="statusList" class="form-label">Statut</label>
                            <select name="status" class="form-select" id="statusList">
                                <option value="">Tous les statuts</option>
                                <option value="pending"
                                    {{ in_array('pending', explode(',', request()->input('appFilter.GetStatus'))) ? 'selected' : '' }}>
                                    En attente
                                </option>
                                <option value="delivered"
                                    {{ in_array('delivered', explode(',', request()->input('appFilter.GetStatus'))) ? 'selected' : '' }}>
                                    Livré
                                </option>
                                <option value="completed"
                                    {{ in_array('completed', explode(',', request()->input('appFilter.GetStatus'))) ? 'selected' : '' }}>
                                    Terminé
                                </option>
                            </select>
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

                        <!-- Date Range Filter -->
                        <div class="flex-fill" style="min-width: 280px;">
                            <label class="form-label">Période</label>
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
