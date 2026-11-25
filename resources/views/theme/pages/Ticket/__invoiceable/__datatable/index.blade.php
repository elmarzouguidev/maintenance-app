{{--
    Tickets to invoice (K2): tickets.invoiceable, delivered repaired tickets on no invoice yet;
    the kit list pattern replacing DataTables, 30 per page. "Facturer" opens the new invoice form
    for the ticket, as before.
--}}
@extends('layouts.admin')

@section('title', 'Tickets à facturer')

@section('content')
    <div class="space-y-6">
        <x-admin.page-header title="Tickets à facturer" description="Les tickets réparés et livrés qui ne sont sur aucune facture." />

        <section aria-labelledby="invoiceable-title" class="space-y-4">
            <h2 id="invoiceable-title" class="sr-only">Tickets à facturer</h2>

            @if ($tickets->isEmpty())
                <div class="admin-card">
                    <x-admin.empty-state icon="banknotes" title="Aucun ticket à facturer" description="Tous les tickets livrés sont facturés." />
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
                                @can('invoices.create')
                                    <x-admin.button href="{{ route('commercial:invoices.create', ['ticket' => $ticket->uuid]) }}" size="sm" icon="banknotes">Facturer</x-admin.button>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                </x-admin.table>

                {{ $tickets->links('pagination::admin') }}
            @endif
        </section>
    </div>
@endsection
