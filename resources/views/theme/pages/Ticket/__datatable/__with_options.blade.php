<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-8">

                        <div class="col-lg-4 mb-4">

                            <a href="#" type="button" onclick="openFilters()" class="btn btn-primary">
                                Filters
                            </a>
                            @if(auth()->user()->hasAnyRole('SuperAdmin','Admin'))
                                <a href="{{ route('admin:tickets.create') }}" type="button" class="btn btn-info">
                                    créer un nouveau ticket
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
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
                        <th class="align-middle">Diagnostiquer</th>
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
                            <td><a href="{{ $ticket->url }}" class="text-body fw-bold">
                                    {{ $ticket->code }}</a>

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
                                    if ($status === 'non-traite') {
                                        $textt = 'Non traité';
                                        $color = 'danger';
                                    } elseif ($status === 'encours-de-reparation') {
                                        $textt = 'En cours de réparation';
                                        $color = 'warning ';
                                    } elseif ($status === 'retour-devis-non-confirme') {
                                        $textt = 'Retour devis non confirmé';
                                        $color = 'info';
                                    } elseif ($status === 'devis-confirme') {
                                        $textt = 'Devis confirmé';
                                        $color = 'success';
                                    } elseif ($status === 'encours-diagnostique') {
                                        $textt = 'Encours de diagnostic';
                                        $color = 'success';
                                    } elseif ($status === 'en-attent-de-devis') {
                                        $textt = 'En attent de devis';
                                        $color = 'success';
                                    } elseif ($status === 'retour-non-reparable') {
                                        $textt = 'Retour non reparable';
                                        $color = 'danger';
                                    } elseif ($status === 'pret-a-livre') {
                                        $textt = 'Produit réparé';
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
                                <i class="fas fas fa-building me-1"></i> {{ optional($ticket->client)->entreprise}}
                            </td>
                            <td>
                                <i class="fas fas fa-user me-1"></i>
                                {{ optional($ticket->technicien)->full_name}}
                            </td>
                            <td>
                                @if(auth()->user()->hasRole('Technicien') && !$ticket->technicien)
                                    <a href="{{ $ticket->diagnose_url }}" type="button"
                                       class="btn btn-warning btn-sm btn-rounded">
                                        Diagnostiquer
                                    </a>
                                @endif
                            </td>
                            <td>
                                @if(auth()->user()->hasRole('SuperAdmin'))
                                    <div class="d-flex gap-3">
                                        <a href="{{ $ticket->media_url }}" class="text-success"><i
                                                class="mdi mdi-file-image font-size-18"></i></a>
                                        <a href="{{ $ticket->edit }}" class="text-success"><i
                                                class="mdi mdi-pencil font-size-18"></i></a>
                                        <a href="#" class="text-danger"
                                           onclick="document.getElementById('delete-ticket-{{ $ticket->uuid }}').submit();">
                                            <i class="mdi mdi-delete font-size-18"></i>
                                        </a>
                                    </div>
                                    <form id="delete-ticket-{{ $ticket->uuid }}" method="post"
                                          action="{{ route('admin:tickets.delete') }}">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="ticket" value="{{ $ticket->uuid }}">
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->
