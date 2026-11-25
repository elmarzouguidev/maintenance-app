{{--
    Tickets grouped by step (App\Collections\Ticket\TicketCollection::groupBy…), one kit table per
    tab (Alpine tabs, replacing the Bootstrap nav-tabs). Each tab: key (the group), label, url
    (the ticket attribute its rows link to: diagnose_url, repear_url or ticket_url) and action
    (button label, or null). $technician: "column" shows the technician, "me" also writes "Moi"
    for the current user's tickets.
--}}
@props(['tickets', 'tabs', 'technician' => null])

@php
    $groups = collect($tabs)->map(fn (array $tab): array => [...$tab, 'rows' => $tickets[$tab['key']] ?? collect()]);
    $firstTab = $groups->first()['key'] ?? '';
    $headers = array_merge(['Ticket'], $technician ? ['Technicien'] : [], ['Client', 'Article', 'Entrée', 'Statut', '']);
@endphp

<div x-data="{ tab: @js($firstTab) }" class="space-y-4">
    <div class="flex flex-wrap gap-2" role="tablist" aria-label="Étapes">
        @foreach ($groups as $group)
            {{-- Kit button classes, the variant bound to the active tab. --}}
            <button type="button" role="tab" id="tab-{{ $group['key'] }}" aria-controls="panel-{{ $group['key'] }}" class="admin-button admin-button-sm"
                x-bind:aria-selected="tab === @js($group['key']) ? 'true' : 'false'"
                x-bind:class="tab === @js($group['key']) ? 'admin-button-primary' : 'admin-button-secondary'"
                x-on:click="tab = @js($group['key'])">
                {{ $group['label'] }} <span class="tabular-nums">({{ count($group['rows']) }})</span>
            </button>
        @endforeach
    </div>

    @foreach ($groups as $group)
        <section id="panel-{{ $group['key'] }}" role="tabpanel" aria-labelledby="tab-{{ $group['key'] }}" x-show="tab === @js($group['key'])" @if (! $loop->first) x-cloak @endif>
            @if (count($group['rows']) === 0)
                <div class="admin-card">
                    <x-admin.empty-state icon="inbox" :title="'Aucun ticket : '.mb_strtolower($group['label'])" description="Rien à traiter à cette étape." />
                </div>
            @else
                <x-admin.table :headers="$headers">
                    @foreach ($group['rows'] as $ticket)
                        @php($url = $ticket->{$group['url']})
                        <tr>
                            <td><a href="{{ $url }}" class="font-medium tabular-nums hover:underline">{{ $ticket->code }}</a></td>
                            @if ($technician)
                                <td>{{ $technician === 'me' && $ticket->user_id === auth()->id() ? 'Moi' : optional($ticket->technicien)->full_name }}</td>
                            @endif
                            <td class="whitespace-normal">{{ optional($ticket->client)->entreprise }}</td>
                            <td class="whitespace-normal">{{ $ticket->article }}</td>
                            <td><x-admin.date :value="$ticket->created_at" /></td>
                            <td><x-tickets.status-badge :ticket="$ticket" /></td>
                            <td class="text-end">
                                @if ($group['action'])
                                    <x-admin.button href="{{ $url }}" size="sm" variant="secondary">{{ $group['action'] }}</x-admin.button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </x-admin.table>
            @endif
        </section>
    @endforeach
</div>
