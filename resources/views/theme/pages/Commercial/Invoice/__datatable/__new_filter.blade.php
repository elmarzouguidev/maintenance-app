<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-4">Filters</h5>

                <form class="row gy-2 gx-3 align-items-center">

                    <div class="col-lg-2 col-md-2">
                        <label class="visually-hidden" for="clienter">Client</label>
                        <select class="form-select select2" id="clienter">
                            <option selected value="">Client</option>
                            @foreach ($clients as $client)
                                <option value="{{ $client->id }}"
                                    {{ in_array($client->id, explode(',', request()->input('appFilter.GetClient'))) ? 'selected' : '' }}>

                                    {{ $client->entreprise }}
                                </option>
                            @endforeach

                        </select>
                    </div>
                    <div class="col-sm-auto">
                        <label class="visually-hidden" for="statusList">Status</label>
                        <select name="status" class="form-select " id="statusList">
                            <option selected value="">Status</option>
                            <option value="non-paid"
                                {{ in_array('non-paid', explode(',', request()->input('appFilter.GetStatus'))) ? 'selected' : '' }}>
                                A régler</option>
                            <option value="paid"
                                {{ in_array('paid', explode(',', request()->input('appFilter.GetStatus'))) ? 'selected' : '' }}>
                                PAYÉE</option>
                        </select>
                    </div>
                    @foreach ($companies as $company)
                        <div class="col-sm-auto">
                            <div class="form-check">
                                <input class="form-check-input chk-filter" type="radio" name="company"
                                    id="company-{{ $company->id }}" value="{{ $company->id }}"
                                    {{ in_array($company->id, explode(',', request()->input('appFilter.GetCompany'))) ? 'checked' : '' }}>

                                <label class="form-check-label" for="company-{{ $company->id }}">
                                    {{ $company->name }}
                                </label>
                            </div>
                        </div>
                    @endforeach
                    @if (request()->input('appFilter.DateBetween'))
                        @php
                            $dates = explode(',', request()->input('appFilter.DateBetween'));
                        @endphp
                    @endif
                    <div class="col-xl-4">
                        <div class="row">
                            {{-- <div class="col-xl-3 mt-2">
                            <label>Filtre par date</label>
                        </div> --}}
                            <div class="col-xl-10">
                                <div class="input-daterange input-group" id="datepicker6" data-date-format="yyyy-mm-dd"
                                    data-date-autoclose="true" data-provide="datepicker"
                                    data-date-container='#datepicker6'>
                                    <input type="text" class="form-control" name="start" id="filterDateStart"
                                        placeholder="date de début" value="{{ $dates[0] ?? '' }}" />
                                    <input type="text" class="form-control" name="end" id="filterDateEnd"
                                        placeholder="date de fin" value="{{ $dates[1] ?? '' }}" />
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-sm-auto">
                        <button type="submit" id="filterData" class="btn btn-primary w-md">filter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
