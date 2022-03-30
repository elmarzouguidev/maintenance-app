<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-4">Filters</h5>

                <form class="row gy-2 gx-3 align-items-center">
                    <div class="col-sm-auto">
                        <label class="visually-hidden" for="autoSizingInput">Name</label>
                        <input type="text" class="form-control" id="autoSizingInput" placeholder="Jane Doe">
                    </div>
                    <div class="col-sm-auto">
                        <label class="visually-hidden" for="clientsList">Client</label>
                        <select class="form-select select2" id="clientsList">
                            <option selected value="">Client</option>
                            @foreach ($clients as $client)
                                <option value="{{ $client->id }}">{{ $client->entreprise }}</option>
                            @endforeach

                        </select>
                    </div>
                    <div class="col-sm-auto">
                        <label class="visually-hidden" for="statusList">Status</label>
                        <select class="form-select " id="statusList">
                            <option selected value="">Status</option>
                            <option value="{{ App\Constants\Status::NON_TRAITE }}">Non traité</option>
                            <option value="{{ App\Constants\Status::EN_COURS_DE_DIAGNOSTIC }}">En cours de diagnostic
                            </option>
                            <option value="{{ App\Constants\Status::EN_COURS_DE_REPARATION }}">En cours de réparation
                            </option>
                            <option value="{{ App\Constants\Status::RETOUR_NON_REPARABLE }}">Retour non réparable
                            </option>
                            <option value="{{ App\Constants\Status::RETOUR_DEVIS_NON_CONFIRME }}">Retour Devis non
                                confirmé</option>
                            <option value="{{ App\Constants\Status::RETOUR_LIVRE }}">Retour livré</option>
                            <option value="{{ App\Constants\Status::EN_ATTENTE_DE_DEVIS }}">En attente de devis
                            </option>
                            <option value="{{ App\Constants\Status::EN_ATTENTE_DE_BON_DE_COMMAND }}">En attente de bon
                                de command</option>
                            <option value="{{ App\Constants\Status::DEVIS_CONFIRME }}">Devis Confirmé</option>
                            <option value="{{ App\Constants\Status::A_REPARER }}">à réparer</option>
                            <option value="{{ App\Constants\Status::PRET_A_ETRE_LIVRE }}">Prêt à être livré</option>
                            <option value="{{ App\Constants\Status::PRET_A_ETRE_FACTURE }}">Prêt à être Facturé
                            </option>
                            <option value="{{ App\Constants\Status::LIVRE }}">Livré</option>
                        </select>
                    </div>
                    <div class="col-sm-auto">
                        <div class="form-check">
                            <input class="form-check-input" name="etat" type="radio" id="autoSizingCheck1">
                            <label class="form-check-label" for="autoSizingCheck1">
                                Réparable
                            </label>
                        </div>

                    </div>
                    <div class="col-sm-auto">
                        <div class="form-check">
                            <input class="form-check-input" name="etat" type="radio" id="autoSizingCheck2">
                            <label class="form-check-label" for="autoSizingCheck2">
                                Non Réparable
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
