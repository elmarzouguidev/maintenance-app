<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">

                        <div class="col-lg-6 mb-4">

                            <b class="text-danger">Total tickets Livré non facturé : <b>{{ count($tickets) }}</b></span>

                        </span>
                    </div>
                </div>
                <table class="table table-bordered dt-responsive nowrap w-100">
                    <thead>
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
                            <th>Status</th>

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
                                    <i class="fas fas fa-building me-1"></i> {{ optional($ticket->client)->entreprise }}
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
                                        if ($status === \App\Constants\Status::PRET_A_ETRE_LIVRE && $ticket->can_invoiced) {
                                            $textt = __('status.statuses.' . \App\Constants\Status::PRET_A_ETRE_FACTURE);
                                            $color = 'success';
                                        } elseif ($status === \App\Constants\Status::LIVRE && $ticket->can_invoiced) {
                                            $textt = __('status.statuses.' . \App\Constants\Status::LIVRE);
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
                                    <i class="fas fas fa-user me-1"></i>
                                    {{ optional($ticket->technicien)->full_name }}
                                </td>
                                <td>
                                    <a href="{{ route('commercial:invoices.create', ['ticket' => $ticket->uuid]) }}"
                                        type="button" class="btn btn-primary btn-sm btn-rounded">
                                        {{-- A facturé --}}
                                        Attente de facturation
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
