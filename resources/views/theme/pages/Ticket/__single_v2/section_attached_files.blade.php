<section class="card h-100" aria-labelledby="ticket-documents-title">
    <div class="card-body">
        <div class="mb-3">
            <h2 id="ticket-documents-title" class="h5 mb-1">Documents associés</h2>
            <p class="text-muted mb-0">Rapports et documents commerciaux liés au ticket.</p>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th scope="col">Document</th>
                        <th scope="col" class="text-end">Ouvrir</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($ticket->estimate_count > 0)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <span class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title rounded-circle bg-primary-subtle text-primary">
                                            <i class="bx bxs-file-pdf" aria-hidden="true"></i>
                                        </span>
                                    </span>
                                    <div class="flex-grow-1 text-break">
                                        <a target="_blank" rel="noopener noreferrer" title="{{ $ticket->estimate->full_number }}" href="{{ route('public.show.estimate', [$ticket->estimate->uuid, 'has_header' => true]) }}" class="fw-medium">
                                            DEVIS-{{ $ticket->estimate->code }}.pdf
                                        </a>
                                        <div class="small text-muted">{{ $ticket->estimate->full_number }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="text-end">
                                <a target="_blank" rel="noopener noreferrer" href="{{ route('public.show.estimate', [$ticket->estimate->uuid, 'has_header' => true]) }}" class="btn btn-sm btn-outline-primary" aria-label="Ouvrir le devis {{ $ticket->estimate->full_number }}">
                                    <i class="bx bx-download" aria-hidden="true"></i>
                                </a>
                            </td>
                        </tr>
                    @endif
                    @if ($ticket->invoice_count > 0)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <span class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title rounded-circle bg-primary-subtle text-primary">
                                            <i class="bx bxs-file-pdf" aria-hidden="true"></i>
                                        </span>
                                    </span>
                                    <div class="flex-grow-1 text-break">
                                        <a target="_blank" rel="noopener noreferrer" title="{{ $ticket->invoice->full_number }} : {{ optional($ticket->invoice->company)->name }}" href="{{ route('public.show.invoice', [$ticket->invoice->uuid, 'has_header' => true]) }}" class="fw-medium">
                                            FACTURE-{{ $ticket->invoice->code }}.pdf
                                        </a>
                                        <div class="small text-muted">{{ $ticket->invoice->full_number }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="text-end">
                                <a target="_blank" rel="noopener noreferrer" href="{{ route('public.show.invoice', [$ticket->invoice->uuid, 'has_header' => true]) }}" class="btn btn-sm btn-outline-primary" aria-label="Ouvrir la facture {{ $ticket->invoice->full_number }}">
                                    <i class="bx bx-download" aria-hidden="true"></i>
                                </a>
                            </td>
                        </tr>
                    @endif
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <span class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title rounded-circle bg-primary-subtle text-primary">
                                        <i class="bx bxs-file-pdf" aria-hidden="true"></i>
                                    </span>
                                </span>
                                <div>
                                    <a target="_blank" rel="noopener noreferrer" title="{{ $ticket->code }}" href="{{ route('admin:tickets.report.generate', $ticket->uuid) }}" class="fw-medium">
                                        Rapport complet
                                    </a>
                                    <div class="small text-muted">PDF · Ticket {{ $ticket->code }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="text-end">
                            <a target="_blank" rel="noopener noreferrer" href="{{ route('admin:tickets.report.generate', $ticket->uuid) }}" class="btn btn-sm btn-outline-primary" aria-label="Ouvrir le rapport complet du ticket {{ $ticket->code }}">
                                <i class="bx bx-download" aria-hidden="true"></i>
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</section>
