{{--
    Reports (K2): rapports.index, every diagnostic and repair report, newest first; the kit list
    pattern replacing DataTables, 30 per page. The PDF opens the ticket's full report.
--}}
@extends('layouts.admin')

@section('title', 'Rapports')

@section('content')
    <div class="space-y-6">
        <x-admin.page-header title="Rapports" description="Les rapports de diagnostic et de réparation, du plus récent au plus ancien.">
            @can('report.edit')
                <x-slot:actions>
                    <x-admin.button href="{{ route('admin:rapports.editions.index') }}" variant="secondary" icon="pencil">Modifier des rapports</x-admin.button>
                </x-slot:actions>
            @endcan
        </x-admin.page-header>

        <section aria-labelledby="reports-title" class="space-y-4">
            <h2 id="reports-title" class="sr-only">Liste des rapports</h2>

            @if ($reportes->isEmpty())
                <div class="admin-card">
                    <x-admin.empty-state icon="document" title="Aucun rapport" description="Aucun rapport n’a encore été rédigé." />
                </div>
            @else
                <x-admin.table :headers="['Ticket', 'Type', 'Technicien', 'Rédigé le', '']">
                    @foreach ($reportes as $report)
                        <tr>
                            <td>
                                @if ($report->ticket)
                                    <a href="{{ $report->ticket->url }}" class="font-medium tabular-nums hover:underline">{{ $report->ticket->code }}</a>
                                @endif
                            </td>
                            <td>{{ $report->type === 'reparation' ? 'Réparation' : 'Diagnostic' }}</td>
                            <td>{{ $report->technicien?->full_name }}</td>
                            <td><x-admin.date :value="$report->created_at" /></td>
                            <td class="text-end">
                                @if ($report->ticket)
                                    <x-admin.icon-button icon="printer" :label="'Rapport PDF du ticket '.$report->ticket->code" href="{{ route('admin:rapports.report.generate', [$report->ticket->uuid, 'has_header' => true]) }}" target="_blank" rel="noopener" size="sm" />
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </x-admin.table>

                {{ $reportes->links('pagination::admin') }}
            @endif
        </section>
    </div>
@endsection
