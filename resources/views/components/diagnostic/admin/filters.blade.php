@props(['clients', 'techniciens'])

<div class="row mb-4">
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

                        <!-- Etat Filter -->
                        <div class="flex-fill" style="min-width: 180px;">
                            <label for="etatList" class="form-label">État</label>
                            <select name="etat" class="form-select" id="etatList">
                                <option value="">Tous les états</option>
                                <option value="reparable"
                                    {{ in_array('reparable', explode(',', request()->input('appFilter.GetEtat'))) ? 'selected' : '' }}>
                                    Réparable
                                </option>
                                <option value="non-reparable"
                                    {{ in_array('non-reparable', explode(',', request()->input('appFilter.GetEtat'))) ? 'selected' : '' }}>
                                    Non réparable
                                </option>
                            </select>
                        </div>

                        <!-- Technicien Filter -->
                        <div class="flex-fill" style="min-width: 200px;">
                            <label for="technicienList" class="form-label">Technicien</label>
                            <select class="form-select" name="technicien" id="technicienList">
                                <option value="">Tous les techniciens</option>
                                @foreach ($techniciens as $technicien)
                                    <option value="{{ $technicien->id }}"
                                        {{ in_array($technicien->id, explode(',', request()->input('appFilter.GetTechnicien'))) ? 'selected' : '' }}>
                                        {{ $technicien->full_name }}
                                    </option>
                                @endforeach
                            </select>
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
