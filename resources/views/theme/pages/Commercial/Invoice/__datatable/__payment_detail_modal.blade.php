@if ($invoice->bill_count)
    <div class="modal fade orderdetailsModal-{{ $invoice->id }}" tabindex="-1" role="dialog"
        aria-labelledby=orderdetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id=orderdetailsModalLabel">Règlement N° :
                        {{ optional($invoice->bill)->full_number }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-2">Facture N° : <span class="text-primary">{{ $invoice->full_number }}</span></p>
                    <p class="mb-4">Client : <span
                            class="text-primary">{{ optional($invoice->client)->entreprise }}</span></p>
                    <hr>
                    <p class="mb-2">Date de Règlement : <span class="text-primary">
                            {{ optional($invoice->bill)->bill_date->format('d-m-Y') }}</span>
                    </p>
                    <p class="mb-2">Mode de Règlement : <span class="text-primary">
                            {{ optional($invoice->bill)->bill_mode }}</span>
                    </p>

                    <p class="mb-4">Référence de transaction : <span
                            class="text-primary">{{ optional($invoice->bill)->reference }}</span></p>
                    <p class="mb-4">Montant : <span
                            class="text-primary">{{ optional($invoice->bill)->formated_price_total }} DH</span></p>
                    @if (optional($invoice->bill)->added_by)
                        <hr>
                        <p class="mb-4">Ajouté par : <span
                                class="text-primary">{{ optional($invoice->bill)->added_by }}</span> le
                            <span
                                class="text-primary">{{ optional($invoice->bill)->created_at->format('d-m-Y H:i') }}</span>
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endif
