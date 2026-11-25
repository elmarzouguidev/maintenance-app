{{--
    Reports to edit (K2): rapports.editions.index, the tickets that have a report, newest first;
    the kit list pattern replacing DataTables, 30 per page.
--}}
@extends('layouts.admin')

@section('title', 'Modifier des rapports')

@section('content')
    <div class="space-y-6">
        <x-admin.page-header title="Modifier des rapports" description="Les tickets qui ont un rapport de diagnostic ou de réparation."
            :breadcrumbs="[['label' => 'Rapports', 'href' => route('admin:rapports.index')], ['label' => 'Modifier']]" />

        <section aria-labelledby="report-tickets-title" class="space-y-4">
            <h2 id="report-tickets-title" class="sr-only">Tickets avec un rapport</h2>

            @if ($tickets->isEmpty())
                <div class="admin-card">
                    <x-admin.empty-state icon="document" title="Aucun rapport" description="Aucun ticket n’a encore de rapport." />
                </div>
            @else
                <x-admin.table :headers="['Ticket', 'Client', 'Technicien', 'Créé le', 'Modifié le', '']">
                    @foreach ($tickets as $ticket)
                        <tr>
                            <td><a href="{{ route('admin:rapports.editions.edit', $ticket->uuid) }}" class="font-medium tabular-nums hover:underline">{{ $ticket->code }}</a></td>
                            <td class="whitespace-normal">{{ optional($ticket->client)->entreprise }}</td>
                            <td>{{ optional($ticket->technicien)->full_name }}</td>
                            <td><x-admin.date :value="$ticket->created_at" /></td>
                            <td><x-admin.date :value="$ticket->updated_at" /></td>
                            <td class="text-end">
                                <x-admin.icon-button icon="pencil" :label="'Modifier les rapports du ticket '.$ticket->code" href="{{ route('admin:rapports.editions.edit', $ticket->uuid) }}" size="sm" />
                            </td>
                        </tr>
                    @endforeach
                </x-admin.table>

                {{ $tickets->links('pagination::admin') }}
            @endif
        </section>
    </div>
@endsection
