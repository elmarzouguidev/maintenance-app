{{--
    Repair page (K2): reparations.single, without jQuery or SweetAlert. The diagnostic report and
    the repair report (rich text), saved (reparation_done=no) or saved and closed
    (reparation_done=reparation_done, after a confirmation: the ticket is then ready to deliver).
    A closed report is read only.
--}}
@extends('layouts.admin')

@section('title', 'Réparation du ticket '.$ticket->code)

@section('content')
    @php
        $repairReport = $ticket->reparationReports;
        $closed = (bool) optional($repairReport)->close_report;
        $photo = $ticket->getFirstMediaUrl('tickets-images');
    @endphp

    <div class="mx-auto w-full max-w-5xl space-y-6">
        <x-admin.page-header :title="'Réparation du ticket '.$ticket->code" :eyebrow="optional($ticket->client)->entreprise"
            :breadcrumbs="[['label' => 'Diagnostics', 'href' => route('admin:diagnostic.index')], ['label' => $ticket->code, 'href' => $ticket->url], ['label' => 'Réparation']]">
            <x-slot:actions>
                <x-tickets.status-badge :ticket="$ticket" />
            </x-slot:actions>
        </x-admin.page-header>

        <x-messages.errors />

        <x-admin.detail-page>
            <x-admin.detail-section title="Rapport de diagnostic">
                @if (optional($ticket->diagnoseReports)->content)
                    <div class="text-body text-fg">{!! sanitize_html($ticket->diagnoseReports->content) !!}</div>
                @else
                    <p class="text-small text-fg-muted">Pas de rapport de diagnostic.</p>
                @endif
            </x-admin.detail-section>

            <x-admin.detail-section title="Rapport de réparation">
                @if ($closed)
                    <x-admin.alert type="info" message="La réparation est terminée : le rapport ne peut plus être modifié." />
                    <div class="text-body text-fg">{!! sanitize_html($repairReport->content) !!}</div>
                @else
                    <form method="POST" action="{{ route('admin:reparations.store', $ticket->uuid) }}" class="space-y-5">
                        @csrf
                        <x-forms.rich-text name="content" label="Rapport" :value="(string) optional($repairReport)->content" required />
                        <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                            <x-admin.button type="submit" name="reparation_done" value="no" variant="secondary">Enregistrer le rapport</x-admin.button>
                            <x-admin.button type="submit" name="reparation_done" value="reparation_done" icon="check"
                                onclick="return confirm('Terminer la réparation ? Le rapport ne pourra plus être modifié et le ticket sera prêt à livrer.')">Enregistrer et terminer la réparation</x-admin.button>
                        </div>
                    </form>
                @endif
            </x-admin.detail-section>

            <x-slot:aside>
                <section aria-labelledby="repair-ticket-title" class="admin-card overflow-hidden">
                    @if ($photo)
                        <img src="{{ $photo }}" alt="Photo : {{ $ticket->article }}" class="aspect-video w-full object-cover">
                    @endif
                    <div class="admin-card-body space-y-3">
                        <h2 id="repair-ticket-title" class="admin-section-title">{{ $ticket->article }}</h2>
                        <dl class="space-y-3">
                            <x-admin.detail-item label="Numéro"><span class="tabular-nums">{{ $ticket->code }}</span></x-admin.detail-item>
                            <x-admin.detail-item label="Technicien">{{ optional($ticket->technicien)->full_name ?: 'Non assigné' }}</x-admin.detail-item>
                        </dl>
                    </div>
                </section>
            </x-slot:aside>
        </x-admin.detail-page>
    </div>
@endsection
