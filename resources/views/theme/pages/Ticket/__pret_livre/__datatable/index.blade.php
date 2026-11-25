{{--
    Tickets ready to leave (K2): tickets.livrable, the kit list pattern replacing DataTables and
    one Bootstrap modal per row. 30 per page. Admins release a ticket (tickets.livrablePostAdmin);
    Reception then records the delivery in one shared dialog (tickets.livrablePost: date, mode,
    customer's name, note). Filters: client, status, d-m-Y dates (converted on submit), periods.
--}}
@extends('layouts.admin')

@section('title', 'Tickets à livrer')

@section('content')
    @php
        use App\Constants\Status;

        $user = auth()->user();
        $isReception = $user->hasRole('Reception');
        $isManager = $user->hasAnyRole('Admin', 'SuperAdmin');
        $filters = (array) request()->input('appFilter', []);
        $kept = array_filter(\Illuminate\Support\Arr::only($filters, ['GetClient', 'GetStatus']), fn ($value) => $value !== null && $value !== '');
        $toPicker = fn (?string $date): string => $date && \Illuminate\Support\Carbon::hasFormat($date, 'd-m-Y') ? \Illuminate\Support\Carbon::createFromFormat('d-m-Y', $date)->format('Y-m-d') : '';
        $range = fn ($start, $end): array => ['GetStartDate' => $start->format('d-m-Y'), 'GetEndDate' => $end->format('d-m-Y')];
        $periods = [
            ['label' => 'Trimestre 1', 'filter' => ['GetPeriod' => 1]],
            ['label' => 'Trimestre 2', 'filter' => ['GetPeriod' => 2]],
            ['label' => 'Trimestre 3', 'filter' => ['GetPeriod' => 3]],
            ['label' => 'Trimestre 4', 'filter' => ['GetPeriod' => 4]],
            ['label' => 'Ce mois', 'filter' => $range(now()->startOfMonth(), now()->endOfMonth())],
            ['label' => 'Année en cours', 'filter' => $range(now()->startOfYear(), now()->endOfYear())],
        ];
        $hasFilters = $filters !== [];
        $listUrl = route('admin:tickets.livrable');
        $statusOptions = collect([Status::PRET_A_ETRE_LIVRE, Status::RETOUR_NON_REPARABLE, Status::RETOUR_DEVIS_NON_CONFIRME])->mapWithKeys(fn ($status) => [$status => __('status.statuses.'.$status)])->all();
    @endphp

    <div class="space-y-6" x-data="{ delivery: { ticket: '', code: '' }, openDelivery(ticket, code) { this.delivery = { ticket, code }; document.getElementById('confirm-delivery').showModal(); } }">
        <x-admin.page-header title="Tickets à livrer" description="Les tickets réparés, non réparables ou dont le devis a été refusé, prêts à sortir." />

        <section aria-labelledby="delivery-filters-title" class="admin-card space-y-4 p-5">
            <h2 id="delivery-filters-title" class="sr-only">Filtres</h2>

            <form method="GET" action="{{ $listUrl }}" class="space-y-4"
                x-on:submit="
                    const toFilter = (value) => value ? value.split('-').reverse().join('-') : '';
                    $refs.startDate.value = toFilter($el.elements.namedItem('start')?.value);
                    $refs.endDate.value = toFilter($el.elements.namedItem('end')?.value);
                    for (const field of $el.elements) {
                        if (field.name && (field.value === '' || field.name === 'start' || field.name === 'end')) {
                            field.disabled = true;
                        }
                    }
                ">
                <input type="hidden" name="appFilter[GetStartDate]" x-ref="startDate">
                <input type="hidden" name="appFilter[GetEndDate]" x-ref="endDate">
                <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                    <x-admin.form-select2 name="appFilter[GetClient]" label="Client" :options="$clients->pluck('entreprise', 'id')->all()" :value="(string) ($filters['GetClient'] ?? '')" placeholder="Tous les clients" />
                    <x-admin.form-select name="appFilter[GetStatus]" id="filter-status" label="Statut" :options="$statusOptions" :value="(string) ($filters['GetStatus'] ?? '')" placeholder="Tous les statuts" />
                    <x-admin.date-picker name="start" label="Du" :value="$toPicker($filters['GetStartDate'] ?? null)" />
                    <x-admin.date-picker name="end" label="Au" :value="$toPicker($filters['GetEndDate'] ?? null)" />
                </div>
                <div class="flex flex-wrap items-center gap-3">
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

        <section aria-labelledby="delivery-list-title" class="space-y-4">
            <h2 id="delivery-list-title" class="sr-only">Tickets à livrer</h2>

            @if ($tickets->isEmpty())
                <div class="admin-card">
                    <x-admin.empty-state icon="truck" title="Aucun ticket à livrer" :description="$hasFilters ? 'Aucun ticket ne correspond à ces filtres.' : 'Aucun ticket n’attend de sortie.'" />
                </div>
            @else
                <x-admin.table :headers="['Ticket', 'Client', 'Article', 'Entrée', 'Statut', 'Technicien', '']">
                    @foreach ($tickets as $ticket)
                        <tr>
                            <td><a href="{{ $ticket->url }}" class="font-medium tabular-nums hover:underline">{{ $ticket->code }}</a></td>
                            <td class="whitespace-normal">{{ optional($ticket->client)->entreprise }}</td>
                            <td class="whitespace-normal">{{ $ticket->article }}</td>
                            <td><x-admin.date :value="$ticket->created_at" /></td>
                            <td><x-tickets.status-badge :ticket="$ticket" /></td>
                            <td>{{ optional($ticket->technicien)->full_name }}</td>
                            <td class="text-end">
                                <div class="flex items-center justify-end gap-2">
                                    @if ($ticket->delivery_count)
                                        <x-admin.badge variant="neutral">Livré</x-admin.badge>
                                    @else
                                        @if ($isReception && $ticket->livrable)
                                            <x-admin.button size="sm" icon="truck" x-on:click="openDelivery(@js($ticket->uuid), @js((string) $ticket->code))">Confirmer la livraison</x-admin.button>
                                        @endif
                                        @if ($isManager && ! $ticket->livrable)
                                            <form method="POST" action="{{ route('admin:tickets.livrablePostAdmin') }}" onsubmit="return confirm(@js('Autoriser la livraison du ticket '.$ticket->code.' ?'))">
                                                @csrf
                                                <input type="hidden" name="ticket" value="{{ $ticket->uuid }}">
                                                <x-admin.button type="submit" size="sm" variant="secondary" icon="check">Autoriser la livraison</x-admin.button>
                                            </form>
                                        @elseif ($isManager)
                                            <span class="text-caption text-fg-muted">Livraison autorisée</span>
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </x-admin.table>

                {{ $tickets->links('pagination::admin') }}
            @endif
        </section>

        @if ($isReception)
            <x-admin.modal id="confirm-delivery" title="Confirmer la livraison" description="Le ticket passe à « Livré » ; un appareil réparé reçoit une garantie de 3 mois.">
                <form id="confirm-delivery-form" method="POST" action="{{ route('admin:tickets.livrablePost') }}" class="grid gap-4">
                    @csrf
                    <input type="hidden" name="ticket" :value="delivery.ticket">
                    <p class="text-small text-fg">Ticket <span class="font-medium tabular-nums" x-text="delivery.code"></span></p>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <x-admin.form-input name="date_end" type="date" label="Date de sortie" :value="now()->format('Y-m-d')" required />
                        <x-admin.form-select name="mode" label="Mode de sortie" :options="['parmoi' => 'Par moi', 'parclient' => 'Par le client']" placeholder="Choisir" required />
                    </div>
                    <x-admin.form-input name="info_client" label="Nom de la personne qui récupère" help="Si le client vient récupérer l’appareil." />
                    <x-admin.textarea name="notes" label="Note" rows="2" />
                </form>
                <x-slot:footer>
                    <x-admin.button variant="secondary" onclick="this.closest('dialog').close()">Annuler</x-admin.button>
                    <x-admin.button type="submit" form="confirm-delivery-form" icon="truck">Confirmer la livraison</x-admin.button>
                </x-slot:footer>
            </x-admin.modal>
        @endif
    </div>
@endsection
