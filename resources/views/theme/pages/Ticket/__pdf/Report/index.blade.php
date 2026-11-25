{{--
    Ticket report (mPDF, App\Support\TicketReportPdf): a standard intervention report. Running
    header with the company and the ticket reference, the company's legal footer with page numbers
    (theme/pdf/partials/footer, as on invoices), then numbered sections, photographs in annex and
    signature blocks. Tables only: mPDF has no flex or grid.
--}}
@php
    use App\Constants\Etat;

    $brand = '#1572A1';
    $diagnostic = $ticket->diagnoseReports;
    $repair = $ticket->reparationReports;
    $delivery = $ticket->delivery_count ? $ticket->delivery : null;
    $etat = (int) $ticket->etat;
    $conclusion = match ($etat) {
        Etat::REPARABLE => ['label' => 'Appareil réparable', 'class' => 'ok'],
        Etat::NON_REPARABLE => ['label' => 'Appareil non réparable', 'class' => 'ko'],
        default => ['label' => 'Diagnostic non terminé', 'class' => 'neutral'],
    };
    $date = fn ($value): string => $value ? \Illuminate\Support\Carbon::parse($value)->format('d/m/Y') : '—';
    $dateTime = fn ($value): string => $value ? \Illuminate\Support\Carbon::parse($value)->format('d/m/Y à H:i') : '—';
    $deliveryModes = ['parmoi' => 'Remis par l’atelier', 'parclient' => 'Retiré par le client'];
    $number = 0;
@endphp
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8" />
    <title>Rapport d’intervention – Ticket {{ $ticket->code }}</title>
    <style>
        body { font-family: sans-serif; font-size: 9.5pt; color: #1f2937; line-height: 1.45; }
        table { border-collapse: collapse; width: 100%; }
        td, th { vertical-align: top; }
        p { margin: 0 0 4pt 0; }
        .muted { color: #6b7280; }
        .small { font-size: 8pt; }

        /* Running header */
        .running td { padding: 0 0 3pt 0; font-size: 8pt; color: #4b5563; }
        .running-rule { border-bottom: 1.2pt solid {{ $brand }}; }
        .running .company { font-size: 10pt; font-weight: bold; color: #111827; }

        /* Title */
        .title-block td { padding: 6pt 0 10pt 0; }
        .eyebrow { font-size: 8pt; letter-spacing: 1pt; text-transform: uppercase; color: {{ $brand }}; font-weight: bold; }
        .doc-title { font-size: 18pt; font-weight: bold; color: #111827; margin-top: 2pt; }
        .doc-subtitle { font-size: 10.5pt; color: #374151; margin-top: 2pt; }
        .meta-table td { padding: 3pt 6pt; font-size: 8.5pt; border-bottom: 0.5pt solid #e5e7eb; }
        .meta-table .label { color: #6b7280; width: 45%; }
        .meta-table .value { font-weight: bold; color: #111827; text-align: right; }

        /* Sections */
        .section { margin-top: 12pt; }
        .section-title { font-size: 10.5pt; font-weight: bold; color: #ffffff; background-color: {{ $brand }}; padding: 4pt 8pt; }
        .section-body { border: 0.6pt solid #d1d5db; border-top: none; padding: 8pt 10pt; }

        .card { border: 0.6pt solid #d1d5db; }
        .card-title { background-color: #f3f4f6; font-size: 8.5pt; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5pt; color: #374151; padding: 4pt 8pt; border-bottom: 0.6pt solid #d1d5db; }
        .fields td { padding: 3pt 8pt; font-size: 9pt; }
        .card .fields td { padding-top: 3pt; }
        .fields .label { color: #6b7280; width: 38%; }
        .fields .value { color: #111827; }

        .timeline td { text-align: center; padding: 6pt 4pt; border: 0.6pt solid #d1d5db; }
        .timeline .step { font-size: 7.5pt; text-transform: uppercase; letter-spacing: 0.4pt; color: #6b7280; }
        .timeline .day { font-size: 10.5pt; font-weight: bold; color: #111827; margin-top: 2pt; }

        .report-meta td { font-size: 8.5pt; color: #4b5563; padding: 0 0 6pt 0; }
        .rich { font-size: 9.5pt; color: #1f2937; }
        .empty { color: #9ca3af; font-style: italic; }

        .verdict { padding: 5pt 8pt; font-weight: bold; font-size: 9.5pt; }
        .verdict.ok { background-color: #dcfce7; color: #166534; border-left: 3pt solid #16a34a; }
        .verdict.ko { background-color: #fee2e2; color: #991b1b; border-left: 3pt solid #dc2626; }
        .verdict.neutral { background-color: #f3f4f6; color: #374151; border-left: 3pt solid #9ca3af; }

        .history th { background-color: #f3f4f6; font-size: 8pt; text-transform: uppercase; letter-spacing: 0.4pt; color: #374151; text-align: left; padding: 4pt 6pt; border-bottom: 0.8pt solid #9ca3af; }
        .history td { font-size: 8.5pt; padding: 4pt 6pt; border-bottom: 0.5pt solid #e5e7eb; }

        .photos td { width: 50%; padding: 4pt; text-align: center; }
        .caption { font-size: 8pt; color: #6b7280; margin-top: 3pt; }

        .signatures td { width: 32%; height: 28mm; border: 0.6pt solid #d1d5db; padding: 5pt 8pt; }
        .signatures td.gap { width: 2%; border: none; padding: 0; }
        .signature-label { font-size: 8pt; font-weight: bold; text-transform: uppercase; letter-spacing: 0.4pt; color: #374151; }
    </style>
</head>
<body>
    <htmlpageheader name="report-header">
        <table class="running">
            <tr>
                <td style="width: 60%;">
                    @if ($companyLogo)
                        <img src="{{ $companyLogo }}" style="height: 10mm;" />
                    @else
                        <span class="company">{{ $company?->name }}</span>
                    @endif
                </td>
                <td style="width: 40%; text-align: right;">
                    Rapport d’intervention<br />
                    <strong style="color: #111827;">Ticket N° {{ $ticket->code }}</strong>
                </td>
            </tr>
        </table>
        <div class="running-rule"></div>
    </htmlpageheader>
    <sethtmlpageheader name="report-header" value="on" show-this-page="1" />
    @include('theme.pdf.partials.footer', ['company' => $company, 'hasHeader' => (bool) $company])

    {{-- Title --}}
    <table class="title-block">
        <tr>
            <td style="width: 58%;">
                <div class="eyebrow">Rapport d’intervention technique</div>
                <div class="doc-title">Ticket N° {{ $ticket->code }}</div>
                <div class="doc-subtitle">{{ $ticket->article }}</div>
                @if ($company)
                    <p class="small muted" style="margin-top: 6pt;">
                        {{ $company->name }} · {{ $company->addresse }}<br />
                        @if ($company->telephone) Tél. {{ $company->telephone }} @endif
                        @if ($company->email) · {{ $company->email }} @endif
                    </p>
                @endif
            </td>
            <td style="width: 42%;">
                <table class="meta-table">
                    <tr><td class="label">Date du rapport</td><td class="value">{{ $issuedAt->format('d/m/Y') }}</td></tr>
                    <tr><td class="label">Date de réception</td><td class="value">{{ $date($ticket->created_at) }}</td></tr>
                    <tr><td class="label">Statut</td><td class="value">{{ __('status.statuses.'.(int) $ticket->status) }}</td></tr>
                    <tr><td class="label">Technicien</td><td class="value">{{ optional($ticket->technicien)->full_name ?: 'Non assigné' }}</td></tr>
                </table>
            </td>
        </tr>
    </table>

    {{-- 1. General information --}}
    <div class="section">
        <div class="section-title">{{ ++$number }}. Informations générales</div>
        <div class="section-body">
            <table>
                <tr>
                    <td style="width: 50%; padding-right: 5pt;">
                        <table class="card fields">
                            <tr><td colspan="2" class="card-title">Client</td></tr>
                                <tr><td class="label">Raison sociale</td><td class="value"><strong>{{ optional($ticket->client)->entreprise }}</strong></td></tr>
                                <tr><td class="label">Contact</td><td class="value">{{ optional($ticket->client)->contact ?: '—' }}</td></tr>
                                <tr><td class="label">Téléphone</td><td class="value">{{ optional($ticket->client)->telephone ?: '—' }}</td></tr>
                                <tr><td class="label">E-mail</td><td class="value">{{ optional($ticket->client)->email ?: '—' }}</td></tr>
                                <tr><td class="label">Adresse</td><td class="value">{{ optional($ticket->client)->addresse ?: '—' }}</td></tr>
                                <tr><td class="label">ICE</td><td class="value">{{ optional($ticket->client)->ice ?: '—' }}</td></tr>
                        </table>
                    </td>
                    <td style="width: 50%; padding-left: 5pt;">
                        <table class="card fields">
                            <tr><td colspan="2" class="card-title">Appareil</td></tr>
                                <tr><td class="label">Désignation</td><td class="value"><strong>{{ $ticket->article }}</strong></td></tr>
                                <tr><td class="label">Référence</td><td class="value">{{ $ticket->article_reference ?: '—' }}</td></tr>
                                <tr><td class="label">N° de ticket</td><td class="value">{{ $ticket->code }}</td></tr>
                                <tr><td class="label">Retour</td><td class="value">{{ $ticket->is_retour ? 'Oui (retour d’un appareil déjà réparé)' : 'Non' }}</td></tr>
                                <tr><td class="label">Photos jointes</td><td class="value">{{ $ticket->getMedia('tickets-images')->count() }}</td></tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    {{-- 2. Timeline --}}
    <div class="section">
        <div class="section-title">{{ ++$number }}. Déroulement</div>
        <div class="section-body">
            <table class="timeline">
                <tr>
                    <td><div class="step">Réception</div><div class="day">{{ $date($ticket->created_at) }}</div></td>
                    <td><div class="step">Début d’intervention</div><div class="day">{{ $date($ticket->started_at) }}</div></td>
                    <td><div class="step">Fin de réparation</div><div class="day">{{ $date($ticket->finished_at) }}</div></td>
                    <td><div class="step">Sortie</div><div class="day">{{ $date($delivery?->date_end) }}</div></td>
                </tr>
            </table>
        </div>
    </div>

    {{-- 3. Reported fault --}}
    <div class="section">
        <div class="section-title">{{ ++$number }}. Panne déclarée</div>
        <div class="section-body rich">
            @if (filled(strip_tags((string) $ticket->description)))
                {!! sanitize_html($ticket->description) !!}
            @else
                <p class="empty">Aucune description.</p>
            @endif
        </div>
    </div>

    {{-- 4. Diagnostic --}}
    <div class="section">
        <div class="section-title">{{ ++$number }}. Diagnostic</div>
        <div class="section-body">
            <table class="report-meta">
                <tr>
                    <td>Établi par : <strong>{{ optional($diagnostic?->technicien)->full_name ?: optional($ticket->technicien)->full_name ?: '—' }}</strong></td>
                    <td style="text-align: right;">Date : <strong>{{ $date($diagnostic?->updated_at ?? $diagnostic?->created_at) }}</strong></td>
                </tr>
            </table>
            <div class="rich">
                @if (filled(strip_tags((string) $diagnostic?->content)))
                    {!! sanitize_html($diagnostic->content) !!}
                @else
                    <p class="empty">Aucun rapport de diagnostic.</p>
                @endif
            </div>
            <div class="verdict {{ $conclusion['class'] }}" style="margin-top: 6pt;">Conclusion : {{ $conclusion['label'] }}</div>
        </div>
    </div>

    {{-- 5. Repair --}}
    <div class="section">
        <div class="section-title">{{ ++$number }}. Réparation</div>
        <div class="section-body">
            @if ($repair)
                <table class="report-meta">
                    <tr>
                        <td>Effectuée par : <strong>{{ optional($repair->technicien)->full_name ?: optional($ticket->technicien)->full_name ?: '—' }}</strong></td>
                        <td style="text-align: right;">Date : <strong>{{ $date($ticket->finished_at ?? $repair->updated_at) }}</strong></td>
                    </tr>
                </table>
            @endif
            <div class="rich">
                @if (filled(strip_tags((string) $repair?->content)))
                    {!! sanitize_html($repair->content) !!}
                @else
                    <p class="empty">{{ $etat === Etat::NON_REPARABLE ? 'Pas de réparation : l’appareil a été déclaré non réparable.' : 'Aucune réparation enregistrée.' }}</p>
                @endif
            </div>
        </div>
    </div>

    {{-- 6. Delivery --}}
    @if ($delivery)
        <div class="section">
            <div class="section-title">{{ ++$number }}. Livraison</div>
            <div class="section-body">
                <table class="fields">
                    <tr><td class="label">Date de sortie</td><td class="value"><strong>{{ $date($delivery->date_end) }}</strong></td></tr>
                    <tr><td class="label">Mode</td><td class="value">{{ $deliveryModes[$delivery->mode] ?? $delivery->mode }}</td></tr>
                    <tr><td class="label">Remis par</td><td class="value">{{ optional($delivery->reception)->full_name ?: '—' }}</td></tr>
                    @if ($delivery->info_client)
                        <tr><td class="label">Récupéré par</td><td class="value">{{ $delivery->info_client }}</td></tr>
                    @endif
                    @if ($delivery->notes)
                        <tr><td class="label">Observation</td><td class="value">{{ $delivery->notes }}</td></tr>
                    @endif
                </table>
            </div>
        </div>
    @endif

    {{-- 7. History --}}
    @if ($ticket->statuses->isNotEmpty())
        <div class="section">
            <div class="section-title">{{ ++$number }}. Historique</div>
            <div class="section-body">
                <table class="history">
                    <thead>
                        <tr><th style="width: 22%;">Date</th><th style="width: 28%;">Étape</th><th>Détail</th></tr>
                    </thead>
                    <tbody>
                        @foreach ($ticket->statuses->sortBy(fn ($status) => $status->pivot->start_at ?? $status->pivot->created_at) as $status)
                            <tr>
                                <td>{{ $dateTime($status->pivot->start_at ?? $status->pivot->created_at) }}</td>
                                <td>{{ $status->name }}</td>
                                <td>{{ \Illuminate\Support\Str::limit(trim(preg_replace('/\s+/', ' ', (string) $status->pivot->description)), 180) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    {{-- Signatures --}}
    <table class="signatures" style="margin-top: 14pt; page-break-inside: avoid;">
        <tr>
            <td><div class="signature-label">Le technicien</div><p class="small muted">{{ optional($ticket->technicien)->full_name }}</p></td>
            <td class="gap"></td>
            <td><div class="signature-label">Le client</div><p class="small muted">Nom, date et signature</p></td>
            <td class="gap"></td>
            <td><div class="signature-label">Cachet de la société</div></td>
        </tr>
    </table>

    {{-- Annex: photographs --}}
    @if ($photos->isNotEmpty())
        <pagebreak />
        <div class="section" style="margin-top: 0;">
            <div class="section-title">Annexe : photographies</div>
            <div class="section-body">
                <table class="photos">
                    @foreach ($photos->chunk(2) as $row)
                        <tr style="page-break-inside: avoid;">
                            @foreach ($row as $index => $photo)
                                <td>
                                    <img src="{{ $photo }}" style="max-width: 100%; max-height: 95mm; border: 0.6pt solid #d1d5db; padding: 3pt;" />
                                    <div class="caption">Photo {{ $index + 1 }} – {{ \Illuminate\Support\Str::limit($ticket->article, 60) }}</div>
                                </td>
                            @endforeach
                            @if ($row->count() === 1)
                                <td></td>
                            @endif
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    @endif
</body>
</html>
