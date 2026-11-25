{{--
    Tickets (K2): the kit list pattern, replacing DataTables and the Bootstrap filters. Serves
    tickets.list (new tickets: not diagnosed, not handled) and tickets.list.old (all tickets);
    30 per page, newest first (DataTables sorted by number, newest first). Filters send the same
    appFilter[...] parameters as before: client, status, state, returns, GetStartDate and
    GetEndDate as d-m-Y (the date pickers give Y-m-d, converted on submit), periods.
--}}
@extends('layouts.admin')

@php
    $allTickets = request()->routeIs('admin:tickets.list.old');
    $pageTitle = $allTickets ? 'Tous les tickets' : 'Nouveaux tickets';
@endphp

@section('title', $pageTitle)

@section('content')
    @php
        $user = auth()->user();
        $isTechnician = $user->worksOnTickets();
        $canCreate = $user->can('ticket.create');
        $filters = (array) request()->input('appFilter', []);
        $kept = array_filter(\Illuminate\Support\Arr::only($filters, ['GetClient', 'GetStatus', 'GetEtat', 'GetRetour']), fn ($value) => $value !== null && $value !== '');
        $toPicker = fn (?string $date): string => $date && \Illuminate\Support\Carbon::hasFormat($date, 'd-m-Y') ? \Illuminate\Support\Carbon::createFromFormat('d-m-Y', $date)->format('Y-m-d') : '';
        $range = fn ($start, $end): array => ['GetStartDate' => $start->format('d-m-Y'), 'GetEndDate' => $end->format('d-m-Y')];
        $periods = [
            ['label' => 'Trimestre 1', 'filter' => ['GetPeriod' => 1]],
            ['label' => 'Trimestre 2', 'filter' => ['GetPeriod' => 2]],
            ['label' => 'Trimestre 3', 'filter' => ['GetPeriod' => 3]],
            ['label' => 'Trimestre 4', 'filter' => ['GetPeriod' => 4]],
            ['label' => 'Ce mois', 'filter' => $range(now()->startOfMonth(), now()->endOfMonth())],
            ['label' => 'Mois dernier', 'filter' => $range(now()->subMonthNoOverflow()->startOfMonth(), now()->subMonthNoOverflow()->endOfMonth())],
            ['label' => 'Année en cours', 'filter' => $range(now()->startOfYear(), now()->endOfYear())],
            ['label' => 'Année précédente', 'filter' => $range(now()->subYear()->startOfYear(), now()->subYear()->endOfYear())],
        ];
        $hasFilters = $filters !== [];
        $listUrl = route($allTickets ? 'admin:tickets.list.old' : 'admin:tickets.list');
        $statusOptions = collect(\App\Constants\Status::TICKET_STATUS)->values()->mapWithKeys(fn ($status) => [$status => __('status.statuses.'.$status)])->all();
        $etatOptions = [\App\Constants\Etat::REPARABLE => __('etat.etats.'.\App\Constants\Etat::REPARABLE), \App\Constants\Etat::NON_REPARABLE => __('etat.etats.'.\App\Constants\Etat::NON_REPARABLE)];
        $headers = array_merge(['Ticket', 'Client', 'Article', 'Entrée', 'Statut', 'Technicien'], $isTechnician ? ['Diagnostic'] : [], ['']);
    @endphp

    <div class="space-y-6">
        <x-admin.page-header :title="$pageTitle" :description="$allTickets ? 'Tous les tickets, du plus récent au plus ancien.' : 'Les tickets ni diagnostiqués ni traités, du plus récent au plus ancien.'">
            <x-slot:actions>
                <x-admin.button href="{{ route('admin:warranty.index') }}" variant="secondary" icon="shield">Garanties</x-admin.button>
                @if ($canCreate)
                    <x-admin.button href="{{ route('admin:tickets.create') }}" icon="plus">Nouveau ticket</x-admin.button>
                @endif
            </x-slot:actions>
        </x-admin.page-header>

        <nav class="flex flex-wrap gap-2" aria-label="Listes de tickets">
            <x-admin.button href="{{ route('admin:tickets.list') }}" size="sm" :variant="$allTickets ? 'secondary' : 'primary'" :aria-current="$allTickets ? null : 'page'">Nouveaux tickets</x-admin.button>
            <x-admin.button href="{{ route('admin:tickets.list.old') }}" size="sm" :variant="$allTickets ? 'primary' : 'secondary'" :aria-current="$allTickets ? 'page' : null">Tous les tickets</x-admin.button>
        </nav>

        <section aria-labelledby="ticket-filters-title" class="admin-card space-y-4 p-5">
            <h2 id="ticket-filters-title" class="sr-only">Filtres</h2>

            <form method="GET" action="{{ $listUrl }}" class="space-y-4"
                x-on:submit="
                    const toFilter = (value) => value ? value.split('-').reverse().join('-') : '';
                    $refs.startDate.value = toFilter($el.elements.namedItem('start')?.value);
                    $refs.endDate.value = toFilter($el.elements.namedItem('end')?.value);
                    for (const field of $el.elements) {
                        if (field.name && (field.value === '' || field.name === 'start' || field.name === 'end' || (field.type === 'checkbox' && ! field.checked))) {
                            field.disabled = true;
                        }
                    }
                ">
                <input type="hidden" name="appFilter[GetStartDate]" x-ref="startDate">
                <input type="hidden" name="appFilter[GetEndDate]" x-ref="endDate">
                <div class="grid gap-4 md:grid-cols-3 xl:grid-cols-5">
                    <x-admin.form-select2 name="appFilter[GetClient]" label="Client" :options="$clients->pluck('entreprise', 'id')->all()" :value="(string) ($filters['GetClient'] ?? '')" placeholder="Tous les clients" />
                    <x-admin.form-select name="appFilter[GetStatus]" id="filter-status" label="Statut" :options="$statusOptions" :value="(string) ($filters['GetStatus'] ?? '')" placeholder="Tous les statuts" />
                    <x-admin.form-select name="appFilter[GetEtat]" id="filter-etat" label="État" :options="$etatOptions" :value="(string) ($filters['GetEtat'] ?? '')" placeholder="Tous les états" />
                    <x-admin.date-picker name="start" label="Du" :value="$toPicker($filters['GetStartDate'] ?? null)" />
                    <x-admin.date-picker name="end" label="Au" :value="$toPicker($filters['GetEndDate'] ?? null)" />
                </div>
                <div class="flex flex-wrap items-center gap-4">
                    <label class="flex items-center gap-2 text-body">
                        <input type="checkbox" name="appFilter[GetRetour]" value="on" class="admin-checkbox" @checked(($filters['GetRetour'] ?? '') === 'on')>
                        Retours seulement
                    </label>
                    <x-admin.button type="submit" variant="secondary" icon="filter">Filtrer</x-admin.button>
                    @if ($hasFilters)
                        <x-admin.button href="{{ $listUrl }}" variant="ghost">Effacer les filtres</x-admin.button>
                    @endif
                </div>
            </form>

            <nav class="flex flex-wrap gap-2 border-t border-line pt-4" aria-label="Périodes">
                @foreach ($periods as $period)
                    @php($isActive = array_intersect_assoc(array_map('strval', $period['filter']), array_map('strval', $filters)) === array_map('strval', $period['filter']))
                    <x-admin.button href="{{ $listUrl.'?'.http_build_query(['appFilter' => [...$kept, ...$period['filter']]]) }}" size="sm" :variant="$isActive ? 'primary' : 'secondary'" :aria-current="$isActive ? 'true' : null">{{ $period['label'] }}</x-admin.button>
                @endforeach
            </nav>
        </section>

        <section aria-labelledby="tickets-title" class="space-y-4">
            <h2 id="tickets-title" class="sr-only">Liste des tickets</h2>

            @if ($tickets->isEmpty())
                <div class="admin-card">
                    @if ($hasFilters)
                        <x-admin.empty-state icon="filter" title="Aucun résultat" description="Aucun ticket ne correspond à ces filtres.">
                            <x-admin.button href="{{ $listUrl }}" variant="secondary">Effacer les filtres</x-admin.button>
                        </x-admin.empty-state>
                    @else
                        <x-admin.empty-state icon="inbox" title="Aucun ticket" :description="$allTickets ? 'Aucun ticket n’a encore été créé.' : 'Aucun nouveau ticket à traiter.'" />
                    @endif
                </div>
            @else
                <x-admin.table :headers="$headers">
                    @foreach ($tickets as $ticket)
                        <tr>
                            <td><a href="{{ $ticket->url }}" class="font-medium tabular-nums hover:underline">{{ $ticket->code }}</a></td>
                            <td class="whitespace-normal">{{ optional($ticket->client)->entreprise }}</td>
                            <td class="whitespace-normal">{{ $ticket->article }}</td>
                            <td><x-admin.date :value="$ticket->created_at" /></td>
                            <td><x-tickets.status-badge :ticket="$ticket" /></td>
                            <td>{{ optional($ticket->technicien)->full_name }}</td>
                            @if ($isTechnician)
                                <td>
                                    @if ($ticket->user_id === null)
                                        <x-admin.button href="{{ $ticket->diagnose_url }}" size="sm" variant="secondary" icon="wrench">Diagnostiquer</x-admin.button>
                                    @else
                                        <span class="text-small text-fg-muted">—</span>
                                    @endif
                                </td>
                            @endif
                            <td class="text-end">
                                <div class="flex items-center justify-end gap-2">
                                    <x-admin.icon-button icon="photo" :label="'Photos du ticket '.$ticket->code" href="{{ $ticket->media_url }}" size="sm" />
                                    @can('ticket.edit')
                                        <x-admin.icon-button icon="pencil" :label="'Modifier le ticket '.$ticket->code" href="{{ $ticket->edit }}" size="sm" />
                                    @endcan
                                    @if ($user->can('ticket.reassign') && $ticket->user_id !== null)
                                        <x-admin.icon-button icon="arrows" :label="'Réassigner le ticket '.$ticket->code" href="{{ $ticket->edit }}#reassignment" size="sm" />
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </x-admin.table>

                {{ $tickets->links('pagination::admin') }}
            @endif
        </section>
    </div>
@endsection
