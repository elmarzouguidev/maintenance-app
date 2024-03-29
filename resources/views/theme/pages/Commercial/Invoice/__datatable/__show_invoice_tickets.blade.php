@if($invoice->ticket_count > 0 || $invoice->tickets_count > 0)
    <div class="modal fade showTicketInvoice-{{$invoice->uuid}}" tabindex="-1" role="dialog"
         aria-labelledby=orderdetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id=orderdetailsModalLabel">Les tickets attaché a la facture N° : {{ $invoice->code }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @include('theme.pages.Commercial.Invoice.__datatable.__show_invoice_tickets_table')
                </div>
            </div>
        </div>
    </div>
@endif

