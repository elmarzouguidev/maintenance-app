<div class="card">
    <div class="card-body">
        @include('theme.layouts._parts.__messages')

        <div class="d-flex flex-wrap align-items-center gap-2 mb-4">
            <a href="{{ route('admin:tickets.list') }}" onclick="openFilters()" class="btn btn-outline-primary">
                Nouveaux tickets
            </a>

            <a href="{{ route('admin:tickets.list.old') }}" onclick="openFilters()" class="btn btn-outline-secondary">
                Tous les tickets
            </a>

            @can('warranty.browse')
                <a href="{{ route('admin:warranty.index') }}" class="btn btn-outline-success">
                    Garanties
                </a>
            @endcan
        </div>

        <div class="table-responsive">
            <table id="datatable-buttons" class="table table-hover align-middle dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            {{-- <th style="width: 20px;" class="align-middle">
                            <div class="form-check font-size-16">
                                <input class="form-check-input" type="checkbox" id="checkAll">
                                <label class="form-check-label" for="checkAll"></label>
                            </div>
                        </th> --}}
                            <th scope="col">Ticket N°</th>
                            <th scope="col">Client</th>
                            <th scope="col">Article</th>
                            <th scope="col">Date d'entrée</th>
                            <th scope="col">Statut</th>
                            {{-- <th>Client</th> --}}
                            <th>Technicien</th>
                            @can('diagnostic.assigned.browse')
                                <th class="align-middle">Diagnostique</th>
                            @endcan

                            <th scope="col" class="align-middle">Action</th>

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
                                    <a href="{{ $ticket->url }}" class="text-primary fw-semibold">

                                        {{ $ticket->code }}

                                    </a>
                                </td>
                                <td class="text-break">

                                    <a href="{{-- optional($ticket->client)->url --}} {{ $ticket->url }}" class="text-body fw-bold">
                                        {{ optional($ticket->client)->entreprise }}
                                    </a>

                                </td>
                                <td class="text-break">{{ $ticket->article }}</td>
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
                                    @can('diagnostic.assigned.browse')
                                        <td class="d-grid gap-2">

                                            @if (auth()->user()->can('diagnostic.edit') && ($ticket->user_id === null || $ticket->technicien()->is(auth()->user())))
                                            <a href="{{ $ticket->diagnose_url }}" type="button"
                                                class="btn btn-warning btn-sm">
                                                Diagnostiquer
                                            </a>
                                        @else
                                            <button class="btn btn-info btn-sm" disabled>
                                                ###
                                            </button>
                                    @endcan
                                    </td>
                                @endif

                                <td>
                                    <div class="d-flex gap-3">

                                        <a href="{{ $ticket->media_url }}" class="text-success" title="Médias du ticket {{ $ticket->code }}" aria-label="Médias du ticket {{ $ticket->code }}">
                                            <i class="mdi mdi-file-image font-size-18" aria-hidden="true"></i>

                                        </a>
                                        @can('ticket.edit')
                                            <a href="{{ $ticket->edit }}" class="text-success" title="Modifier le ticket {{ $ticket->code }}" aria-label="Modifier le ticket {{ $ticket->code }}">
                                                <i class="mdi mdi-pencil font-size-18" aria-hidden="true"></i>

                                            </a>
                                        @endcan
                                        @can('ticket.reassign')
                                        @if ($ticket->user_id !== null)
                                            <a href="{{ $ticket->edit }}#reassignment" class="text-warning" title="Réassigner le ticket {{ $ticket->code }}" aria-label="Réassigner le ticket {{ $ticket->code }}">
                                                <i class="mdi mdi-account-switch font-size-18" aria-hidden="true"></i>
                                            </a>
                                        @endif
                                        @endcan
                                    </div>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
            </table>
        </div>
    </div>
</div>

@include('theme.pages.Ticket.__datatable.__settings_modal')
