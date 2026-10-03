@php
    $repairReportIsClosed = isset($ticket->reparationReports) && $ticket->reparationReports->close_report;
    $disabled = $repairReportIsClosed ? 'disabled' : '';
@endphp

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="mb-4">
                    <h2 class="h5 card-title mb-1">Rapport de réparation</h2>
                    <p class="text-muted mb-0">Consignez les travaux effectués sur l’appareil.</p>
                </div>

                @if (session('success'))
                    <div class="alert alert-success" role="status">{{ session('success') }}</div>
                @endif

                @canany(['reparations.edit', 'reparations.manage_assigned'])
                    <form action="{{ route('admin:reparations.store', $ticket->uuid) }}" method="post" id="TicketRapportForm">
                        @csrf
                        <div class="mb-4">
                            <label class="form-label" for="ticketdesc-editor">Observations et travaux effectués</label>
                            <textarea name="content" class="form-control @error('content') is-invalid @enderror"
                                id="ticketdesc-editor" rows="7">{{ optional($ticket->reparationReports)->content ?? old('content') }}</textarea>
                            @error('content')
                                <div class="invalid-feedback" role="alert">{{ $message }}</div>
                            @enderror
                        </div>
                        <input id="reparation-end" type="hidden" name="reparation_done" value="no">
                    </form>

                    <div class="d-flex flex-wrap gap-2">
                        <button class="btn btn-primary" type="button" {{ $disabled }}
                            onclick="document.getElementById('TicketRapportForm').submit();">
                            <i class="bx bx-save me-1" aria-hidden="true"></i> Enregistrer le rapport
                        </button>

                        @can('reparations.complete')
                            <button class="btn btn-outline-danger" id="closeTicketReparation" type="button" {{ $disabled }}
                                onclick="document.getElementById('reparation-end').value='reparation_done';">
                                <i class="bx bx-check-circle me-1" aria-hidden="true"></i> Terminer la réparation
                            </button>
                        @endcan
                    </div>
                @endcanany
            </div>
        </div>
    </div>
</div>
