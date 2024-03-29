<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-lg-8 mb-4">

                                @if (auth()->user()->hasAnyRole('SuperAdmin', 'Admin', 'Reception'))
                                    <a href="{{ route('admin:tickets.create') }}" type="button" class="btn btn-info">
                                        créer un nouveau ticket
                                    </a>
                                @endif

                                @if (request()->routeIs('admin:tickets.list.old'))
                                    <a href="{{ route('admin:tickets.list') }}" type="button" onclick="openFilters()"
                                        class="btn btn-danger">
                                        Nouveau Tickets
                                    </a>
                                @endif
                                @if (request()->routeIs('admin:tickets.list'))
                                    <a href="{{ route('admin:tickets.list.old') }}" type="button"
                                        onclick="openFilters()" class="btn btn-warning">
                                        Tous les Tickets
                                    </a>
                                @endif
                                {{-- @if (auth()->user()->hasAnyRole('SuperAdmin', 'Admin'))
                                <button type="button" class="btn btn-info" data-bs-toggle="modal"
                                        data-bs-target=".ticketSettings">
                                    Settings
                                </button>
                            @endif --}}

                                <a href="{{ route('admin:warranty.index') }}" type="button" class="btn btn-success">
                                    Garanties
                                </a>

                            </div>

                            <div class="col-lg-4 mb-4">
                                {{ $tickets->links('vendor.pagination.bootstrap-4') }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered border-danger table-hover align-middle table-nowrap table-check">
                        <thead class="table-light">
                            <tr>
                                <th class="align-middle">Ticket N°</th>
                                <th class="align-middle">Client</th>
                                <th class="align-middle">Article</th>
                                <th class="align-middle">Date d'entrée</th>
                                <th class="align-middle">Status</th>
                                {{-- <th>Client</th> --}}
                                <th class="align-middle">Technicien</th>
                                @if (auth()->user()->hasRole('Technicien'))
                                    <th class="align-middle">Diagnostique</th>
                                @endif
                                @if (auth()->user()->hasAnyRole('SuperAdmin', 'Admin'))
                                    <th class="align-middle">Action</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tickets as $ticket)
                                <tr>
                                    <td>
                                        <a href="{{ $ticket->url }}" class="text-body fw-bold"
                                            style="color:#556ee6 !important">

                                            {{ $ticket->code }}

                                        </a>
                                    </td>
                                    <td>
                                        {{ optional($ticket->client)->entreprise }}

                                    </td>
                                    <td> {{ $ticket->article }}</td>
                                    <td>
                                        {{ $ticket->created_at->format('d-m-Y') }}
                                    </td>
                                    <td>
                                        @php
                                            $status = $ticket->status;
                                            $textt = __('status.statuses.' . $status);
                                            $color = 'danger';
                                        @endphp

                                        <i class="mdi mdi-circle text-{{ $color }} font-size-10"></i>
                                        {{ $textt }}
                                        @if ($ticket->etat != App\Constants\Etat::NON_DIAGNOSTIQUER)
                                            <br>
                                            <i class="mdi mdi-circle text-info font-size-10"></i>
                                            {{ __('etat.etats.' . $ticket->etat) }}
                                        @endif

                                    </td>

                                    {{-- <td>
                                    <i class="fas fas fa-building me-1"></i> {{ optional($ticket->client)->entreprise}}
                                </td> --}}
                                    <td>
                                        <i class="fas fas fa-user me-1"></i>
                                        {{ optional($ticket->technicien)->full_name }}
                                    </td>
                                    @if (auth()->user()->hasRole('Technicien'))
                                        <td class="d-grid gap-2">

                                            @if ($ticket->user_id == null && !$ticket->technicien()->is(auth()->user()))
                                                <a href="{{ $ticket->diagnose_url }}" type="button"
                                                    class="btn btn-warning btn-sm">
                                                    Diagnostiquer
                                                </a>
                                            @else
                                                <button class="btn btn-info btn-sm" disabled>
                                                    ***
                                                </button>
                                            @endif
                                        </td>
                                    @endif
                                    @if (auth()->user()->hasAnyRole('SuperAdmin', 'Admin'))
                                        <td>
                                            <div class="d-flex gap-3">
                                                {{-- <a href="{{ $ticket->media_url }}" class="text-success"><i
                                                    class="mdi mdi-file-image font-size-18"></i></a> --}}
                                                <a class="btn btn-sm btn-info" href="{{ $ticket->edit }}" class="text-success"><i
                                                        class="mdi mdi-pencil font-size-18"></i></a>
                                                {{-- <a href="#" class="text-danger"
                                               onclick="document.getElementById('delete-ticket-{{ $ticket->uuid }}').submit();">
                                                <i class="mdi mdi-delete font-size-18"></i>
                                            </a> --}}
                                            </div>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>
