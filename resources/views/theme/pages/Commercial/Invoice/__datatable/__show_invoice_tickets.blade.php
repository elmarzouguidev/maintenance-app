@if($invoice->ticket_count || $invoice->tickets_count)
    <div class="modal fade showTicketInvoice-{{$invoice->uuid}}" tabindex="-1" role="dialog"
         aria-labelledby=orderdetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id=orderdetailsModalLabel">Facture N° : {{ $invoice->code }} - {{optional($invoice->company)->name}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                 @include('theme.pages.Commercial.Invoice.__datatable.__show_invoice_tickets_table')
                </div>
            </div>
        </div>
    </div>
@endif

