{{--
    Edit a ticket's reports (K2): rapports.editions.update, the diagnostic and repair reports as
    rich text (UpdateReportFormRequest requires both, as before). The ticket details are shown
    only: the former form posted an article field that the update ignored.
--}}
@extends('layouts.admin')

@section('title', 'Rapports du ticket '.$ticket->code)

@section('content')
    <div class="mx-auto w-full max-w-4xl space-y-6">
        <x-admin.page-header :title="'Rapports du ticket '.$ticket->code" :description="$ticket->article"
            :breadcrumbs="[['label' => 'Rapports', 'href' => route('admin:rapports.index')], ['label' => 'Modifier', 'href' => route('admin:rapports.editions.index')], ['label' => $ticket->code]]">
            <x-slot:actions>
                <x-admin.button href="{{ route('admin:tickets.report.generate', $ticket->uuid) }}" target="_blank" rel="noopener" variant="secondary" icon="printer">Rapport PDF</x-admin.button>
                <x-admin.button href="{{ $ticket->url }}" variant="secondary" icon="eye">Ticket</x-admin.button>
            </x-slot:actions>
        </x-admin.page-header>

        <form method="POST" action="{{ route('admin:rapports.editions.update', $ticket->uuid) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <x-messages.errors />

            <section aria-labelledby="report-diagnose-title" class="admin-card space-y-4 p-5 sm:p-6">
                <h2 id="report-diagnose-title" class="admin-section-title">Rapport de diagnostic</h2>
                <x-forms.rich-text name="report_diagnose" label="Diagnostic" :value="(string) optional($ticket->diagnoseReports)->content" required />
            </section>

            <section aria-labelledby="report-repair-title" class="admin-card space-y-4 p-5 sm:p-6">
                <h2 id="report-repair-title" class="admin-section-title">Rapport de réparation</h2>
                <x-forms.rich-text name="report_reparation" label="Réparation" :value="(string) optional($ticket->reparationReports)->content" required />
            </section>

            <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                <x-admin.button href="{{ route('admin:rapports.editions.index') }}" variant="secondary">Retour à la liste</x-admin.button>
                <x-admin.button type="submit">Enregistrer les rapports</x-admin.button>
            </div>
        </form>
    </div>
@endsection
