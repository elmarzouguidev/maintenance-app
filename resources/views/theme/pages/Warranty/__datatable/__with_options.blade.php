<div class="row">
    <div class="col-12">
        <section class="card" aria-labelledby="warranty-list-title">
            <div class="card-header bg-transparent border-bottom">
                <h2 class="h5 card-title mb-1" id="warranty-list-title">Garanties enregistrées</h2>
                <p class="text-muted mb-0">Consultez la période de garantie associée à chaque ticket.</p>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table id="datatable-buttons" class="table table-hover align-middle dt-responsive nowrap w-100">
                        <thead class="table-light">
                            <tr>
                                <th scope="col" class="text-center">
                                    <div class="form-check d-inline-flex">
                                        <input class="form-check-input" type="checkbox" id="warranty-check-all" aria-label="Sélectionner toutes les garanties">
                                        <label class="form-check-label" for="warranty-check-all"></label>
                                    </div>
                                </th>
                                <th scope="col">Ticket</th>
                                <th scope="col">Statut du ticket</th>
                                <th scope="col">Début de garantie</th>
                                <th scope="col">Fin de garantie</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($warranties as $warranty)
                                <tr>
                                    <td class="text-center">
                                        <div class="form-check d-inline-flex">
                                            <input class="form-check-input" type="checkbox" id="warranty-{{ $warranty->id }}"
                                                value="{{ $warranty->id }}" aria-label="Sélectionner la garantie du ticket {{ optional($warranty->ticket)->code }}">
                                            <label class="form-check-label" for="warranty-{{ $warranty->id }}"></label>
                                        </div>
                                    </td>
                                    <td class="fw-medium">{{ optional($warranty->ticket)->code }}</td>
                                    <td>
                                        <span class="badge bg-info-subtle text-info-emphasis">
                                            {{ __('status.statuses.' . optional($warranty->ticket)->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $warranty->start_at->format('d-m-Y') }}</td>
                                    <td>{{ $warranty->end_at->format('d-m-Y') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <i class="bx bx-shield-quarter d-block fs-2 text-muted mb-2" aria-hidden="true"></i>
                                        <span class="text-muted">Aucune garantie enregistrée.</span>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
</div>
