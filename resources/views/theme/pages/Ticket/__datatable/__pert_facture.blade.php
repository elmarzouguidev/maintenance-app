<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            {{-- <th style="width: 20px;" class="align-middle">
                                <div class="form-check font-size-16">
                                    <input class="form-check-input" type="checkbox" id="checkAll">
                                    <label class="form-check-label" for="checkAll"></label>
                                </div>
                            </th> --}}
                            <th>Ticket N°</th>
                            <th>Article</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Etat</th>
                            <th>Client</th>
                            <th>Technicien</th>
                            @auth('admin')
                                <th class="align-middle">Action</th>
                            @endauth
                        </tr>
                    </thead>

                    <tbody>

                        @foreach ($tickets as $ticket)
                            @if ($ticket->pret_a_facture)
                                <tr>
                                    {{-- <td>
                                    <div class="form-check font-size-16">
                                        <input class="form-check-input" type="checkbox" id="orderidcheck01">
                                        <label class="form-check-label" for="orderidcheck01"></label>
                                    </div>
                                </td> --}}
                                    <td><a href="{{ $ticket->url }}" class="text-body fw-bold">
                                            {{ $ticket->unique_code }}</a>

                                    </td>
                                    <td> {{ $ticket->article }}</td>
                                    <td>
                                        {{ $ticket->full_date }}
                                    </td>
                                    <td>
                                        @php
                                            $status = $ticket->stat;
                                            $textt = '';
                                            $color = '';
                                            if ($status === 'pret-a-livre' && $ticket->pret_a_facture) {
                                                $textt = 'Produit réparé pret a Facturé';
                                                $color = 'success';
                                            } else {
                                                $textt = 'Inconnu';
                                                $color = 'warning';
                                            }
                                        @endphp

                                        <i class="mdi mdi-circle text-{{ $color }} font-size-10"></i>
                                        {{ $textt }}
                                    </td>
                                    <td>
                                        <span class="badge badge-pill badge-soft-success font-size-12">
                                            {{ $ticket->etat }}
                                        </span>
                                    </td>
                                    <td>
                                        <i class="fas fas fa-building me-1"></i>
                                        {{ $ticket->client->entreprise ?? '' }}
                                    </td>
                                    <td>
                                        @if ($ticket->technicien_count)
                                            <i class="fas fas fa-user me-1"></i>
                                            {{ $ticket->technicien->full_name ?? '' }}
                                        @else
                                            <i class="mdi mdi-circle  font-size-10"></i>
                                        @endif
                                    </td>
                                    @auth('admin')
                                        <td>
                                            <div class="d-flex gap-3">
                                                @if ($ticket->invoice_count <= 0)
                                                    <a href="{{ route('commercial:invoices.create', ['ticket' => $ticket->uuid]) }}"
                                                        type="button" class="btn btn-primary btn-sm btn-rounded">
                                                        Facturation
                                                    </a>
                                                @else
                                                    <a href="#"
                                                        type="button" class="btn btn-warning">
                                                        Deja factué
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    @endauth
                                </tr>
                            @endif
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
