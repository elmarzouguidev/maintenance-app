<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-4">Filters</h5>

                <form class="row gy-2 gx-3 align-items-center">
                    <div class="col-sm-auto">
                        <label class="visually-hidden" for="autoSizingInput">Name</label>
                        <input type="text" class="form-control" id="autoSizingInput" placeholder="">
                    </div>
                    <div class="col-lg-2 col-md-2">
                        <label class="visually-hidden" for="clientsList">Client</label>
                        <select class="form-select select2" id="clientsList">
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
                            <option value="{{ App\Constants\Response::DEVIS_EN_ATTENTE }}"
                                {{ in_array(App\Constants\Response::DEVIS_EN_ATTENTE, explode(',', request()->input('appFilter.GetStatus')))? 'selected': '' }}>
                                En attente</option>
                            <option value="{{ App\Constants\Response::DEVIS_ACCEPTE }}"
                                {{ in_array(App\Constants\Response::DEVIS_ACCEPTE, explode(',', request()->input('appFilter.GetStatus')))? 'selected': '' }}>
                                Accepté</option>
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
                    <div class="col-sm-auto">
                        <div class="form-check">
                            <input class="form-check-input chk-filter" type="radio" name="send" id="send1" value="1"
                                {{ in_array(1, explode(',', request()->input('appFilter.GetSend'))) ? 'checked' : '' }}>

                            <label class="form-check-label" for="send1">
                                Devis envoyer par mail
                            </label>
                        </div>
                    </div>
                    <div class="col-sm-auto">
                        <div class="form-check">
                            <input class="form-check-input chk-filter" type="radio" name="send" id="send0" value="0"
                                {{ in_array(0, explode(',', request()->input('appFilter.GetSend'))) ? 'checked' : '' }}>

                            <label class="form-check-label" for="send0">
                                Devis non envoyer par mail
                            </label>
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
