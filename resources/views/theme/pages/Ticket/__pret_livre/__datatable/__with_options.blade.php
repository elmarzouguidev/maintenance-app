<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-wrap align-items-start justify-content-between gap-2 mb-4">
                    <div>
                        <h2 class="h5 card-title mb-1">Suivi des livraisons</h2>
                        <p class="text-muted mb-0">Confirmez la remise des appareils et consultez leur statut.</p>
                    </div>
                </div>
                <div class="table-responsive">
                <table class="table table-hover align-middle dt-responsive nowrap w-100">
                    <thead class="table-light">
                        <tr>
                            {{-- <th style="width: 20px;" class="align-middle">
                            <div class="form-check font-size-16">
                                <input class="form-check-input" type="checkbox" id="checkAll">
                                <label class="form-check-label" for="checkAll"></label>
                            </div>
                        </th> --}}
                            <th>Ticket N°</th>
                            <th>Client</th>
                            <th>Article</th>
                            <th>Date</th>
                            <th>Statut</th>

                            <th>Technicien</th>
                            <th class="align-middle">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tickets as $ticket)
                            <tr>
                                {{-- <td>
                                <div class="form-check font-size-16">
                                    <input class="form-check-input" type="checkbox" id="orderidcheck01">
                                    <label class="form-check-label" for="orderidcheck01"></label>
                                </div>
                            </td> --}}
                                <td>
                                    <a href="{{ $ticket->url }}" class="text-body fw-bold">
                                        {{ $ticket->code }}
                                    </a>
                                </td>
                                <td>
                                    <i class="fas fa-building me-1 text-muted" aria-hidden="true"></i> {{ optional($ticket->client)->entreprise }}
                                </td>
                                <td> {{ $ticket->article }}</td>
                                <td>
                                    {{ $ticket->full_date }}
                                </td>
                                <td>
                                    @php
                                        $status = $ticket->status;
                                        $textt = '';
                                        $color = '';
                                        if ($status == \App\Constants\Status::RETOUR_DEVIS_NON_CONFIRME) {
                                            $textt = __(
                                                'status.statuses.' . \App\Constants\Status::RETOUR_DEVIS_NON_CONFIRME,
                                            );
                                            $color = 'info';
                                        } elseif ($status == \App\Constants\Status::LIVRE) {
                                            $textt = __('status.statuses.' . \App\Constants\Status::LIVRE);
                                            $color = 'success';
                                        } elseif ($status == \App\Constants\Status::PRET_A_ETRE_LIVRE) {
                                            $textt = __('status.statuses.' . \App\Constants\Status::PRET_A_ETRE_LIVRE);
                                            $color = 'success';
                                        } elseif ($status == \App\Constants\Status::RETOUR_NON_REPARABLE) {
                                            $textt = __(
                                                'status.statuses.' . \App\Constants\Status::RETOUR_NON_REPARABLE,
                                            );
                                            $color = 'danger';
                                        } else {
                                            $textt = 'Inconnu';
                                            $color = 'warning';
                                        }
                                    @endphp

                                    <span class="badge bg-{{ $color }}-subtle text-{{ $color }}-emphasis">{{ $textt }}</span>

                                </td>

                                <td>
                                    <i class="fas fa-user me-1 text-muted" aria-hidden="true"></i>
                                    {{ optional($ticket->technicien)->full_name }}
                                </td>
                                <td>
                                    @can('ticket.delivery.confirm')
                                        @if ($ticket->livrable && !$ticket->delivery_count)
                                            <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                                                data-bs-target=".confirmLivrable-{{ $ticket->uuid }}"
                                                aria-label="Confirmer la livraison du ticket {{ $ticket->code }}">
                                                Confirmer la livraison
                                            </button>
                                        @else
                                            <span class="badge bg-success-subtle text-success">Déjà livré</span>
                                        @endif
                                    @endcan
                                    @can('ticket.delivery.admin_confirm')
                                        @if (!$ticket->livrable && !$ticket->delivery_count)
                                            <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                                                data-bs-target=".confirmLivrableAdmin-{{ $ticket->uuid }}"
                                                aria-label="Valider l’autorisation de livraison du ticket {{ $ticket->code }}">
                                                Autoriser la livraison
                                            </button>
                                        @else
                                            {{-- <button type="button" class="btn btn-warning">
                                           
                                       ***
                                    </button> --}}
                                        @endif
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>
</div>
