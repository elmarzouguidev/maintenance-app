{{--
    Ticket processing (K2): the diagnostic page (tickets.diagnose), without jQuery, the Bootstrap
    carousel or SweetAlert. Same roles and actions as before:
    - SuperAdmin/Admin: create or open the estimate, record the customer's answer
      (tickets.diagnose.send-confirm), read the diagnostic report;
    - the assigned technician or a SuperTechnicien: write the report (state + rich text), save it
      or save and send it (sendreport=yessendit, after a confirmation); a sent report is read only.
--}}
@extends('layouts.admin')

@section('title', 'Traitement du ticket '.$ticket->code)

@section('content')
    @php
        use App\Constants\Etat;
        use App\Constants\Response;
        use App\Constants\Status;

        $user = auth()->user();
        $report = $ticket->diagnoseReports;
        $reportClosed = (bool) optional($report)->close_report;
        $canWriteReport = ($user->hasRole('Technicien') && $ticket->user_id == $user->id) || $user->hasRole('SuperTechnicien');
        $photos = $ticket->getMedia('tickets-images');
        $etatOptions = [Etat::REPARABLE => 'Réparable', Etat::NON_REPARABLE => 'Non réparable'];
        $answer = match ((int) $ticket->status) {
            Status::A_REPARER => Response::DEVIS_ACCEPTE,
            Status::RETOUR_DEVIS_NON_CONFIRME => Response::DEVIS_NON_ACCEPTE,
            default => null,
        };
    @endphp

    <div class="mx-auto w-full max-w-5xl space-y-6">
        <x-admin.page-header :title="'Traitement du ticket '.$ticket->code" :eyebrow="optional($ticket->client)->entreprise"
            :breadcrumbs="[['label' => 'Tickets', 'href' => route('admin:tickets.list')], ['label' => $ticket->code, 'href' => $ticket->url], ['label' => 'Traitement']]">
            <x-slot:actions>
                <x-tickets.status-badge :ticket="$ticket" />
            </x-slot:actions>
        </x-admin.page-header>

        <x-messages.errors />

        <x-admin.detail-page>
            <x-admin.detail-section :title="$ticket->article">
                <div class="text-body text-fg">{!! sanitize_html($ticket->description) !!}</div>
                @if ($photos->isNotEmpty())
                    <ul class="grid grid-cols-2 gap-3 sm:grid-cols-3" aria-label="Photos">
                        @foreach ($photos as $photo)
                            <li>
                                <a href="{{ $photo->getFullUrl() }}" target="_blank" rel="noopener" class="block overflow-hidden rounded-control border border-line hover:border-line-strong">
                                    <img src="{{ $photo->getFullUrl() }}" alt="Photo {{ $loop->iteration }} : {{ $ticket->article }}" class="aspect-square w-full object-cover" loading="lazy">
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </x-admin.detail-section>

            @if ($user->hasAnyRole('SuperAdmin', 'Admin'))
                <x-admin.detail-section title="Devis et réponse du client">
                    <div class="flex flex-wrap gap-3">
                        @if ($ticket->estimate_count == 1)
                            <x-admin.button href="{{ route('public.show.estimate', [$ticket->estimate->uuid, 'has_header' => true]) }}" target="_blank" rel="noopener" variant="secondary" icon="document">Voir le devis {{ $ticket->estimate->full_number }}</x-admin.button>
                        @else
                            <x-admin.button href="{{ route('commercial:estimates.create.ticket', $ticket->uuid) }}" icon="plus">Créer un devis</x-admin.button>
                        @endif
                    </div>

                    <form method="POST" action="{{ route('admin:tickets.diagnose.send-confirm', $ticket->uuid) }}" class="space-y-4 border-t border-line pt-5">
                        @csrf
                        <x-admin.segmented-radio name="response" label="Réponse au devis" :value="$answer"
                            :options="[Response::DEVIS_ACCEPTE => 'Accepté : réparer', Response::DEVIS_NON_ACCEPTE => 'Refusé : rendre l’appareil']" required />
                        <x-admin.button type="submit" variant="secondary">Enregistrer la réponse</x-admin.button>
                    </form>

                    <div class="space-y-1 border-t border-line pt-5">
                        <h3 class="admin-form-label">Rapport de diagnostic</h3>
                        @if (optional($report)->content)
                            <div class="text-body text-fg">{!! sanitize_html($report->content) !!}</div>
                        @else
                            <p class="text-small text-fg-muted">Pas encore de rapport.</p>
                        @endif
                    </div>
                </x-admin.detail-section>
            @endif

            @if ($canWriteReport)
                <x-admin.detail-section title="Rapport de diagnostic">
                    @if ($reportClosed)
                        <x-admin.alert type="info" message="Le rapport a été envoyé : il ne peut plus être modifié." />
                        <dl>
                            <x-admin.detail-item label="État">{{ __('etat.etats.'.$ticket->etat) }}</x-admin.detail-item>
                        </dl>
                        <div class="text-body text-fg">{!! sanitize_html(optional($report)->content) !!}</div>
                    @else
                        <form method="POST" action="{{ $ticket->diagnose_url }}" class="space-y-5">
                            @csrf
                            <input type="hidden" name="ticket" value="{{ $ticket->uuid }}">
                            <input type="hidden" name="type" value="diagnostique">
                            <x-admin.segmented-radio name="etat" label="État de l’appareil" :options="$etatOptions"
                                :value="(int) $ticket->etat === Etat::NON_DIAGNOSTIQUER ? null : $ticket->etat" required />
                            <x-forms.rich-text name="content" label="Rapport" :value="(string) optional($report)->content" required />
                            <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                                <x-admin.button type="submit" name="sendreport" value="no" variant="secondary">Enregistrer le rapport</x-admin.button>
                                <x-admin.button type="submit" name="sendreport" value="yessendit" icon="check"
                                    onclick="return confirm('Envoyer le rapport ? Il ne pourra plus être modifié.')">Enregistrer et envoyer</x-admin.button>
                            </div>
                        </form>
                    @endif
                </x-admin.detail-section>
            @endif

            <x-slot:aside>
                <section aria-labelledby="ticket-details-title" class="admin-card">
                    <div class="admin-card-header">
                        <h2 id="ticket-details-title" class="admin-section-title">Détails</h2>
                    </div>
                    <dl class="admin-card-body space-y-3">
                        <x-admin.detail-item label="Numéro"><span class="tabular-nums">{{ $ticket->code }}</span></x-admin.detail-item>
                        <x-admin.detail-item label="Client">{{ optional($ticket->client)->entreprise }}</x-admin.detail-item>
                        <x-admin.detail-item label="Technicien">{{ optional($ticket->technicien)->full_name ?: 'Non assigné' }}</x-admin.detail-item>
                        <x-admin.detail-item label="Entrée"><x-admin.date :value="$ticket->created_at" time /></x-admin.detail-item>
                    </dl>
                </section>
            </x-slot:aside>
        </x-admin.detail-page>
    </div>
@endsection
