<div class="row" id="invoices_lister">

    {{-- @include('theme.pages.Commercial.Invoice.__datatable.__filters') --}}

    <div class="col-lg-12" id="invoices-list">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="button-items">
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="col-lg-6 mb-4">
                                    <a href="{{ route('commercial:invoices.create') }}" type="button"
                                        class="btn btn-info">
                                        Créer une facture
                                    </a>
                                    <a href="{{ route('admin:tickets.invoiceable') }}" class="btn btn-primary">
                                        En attente de facturation
                                        @if ($tickets_invoiceable)
                                            <span class="badge bg-warning ms-1">{{ $tickets_invoiceable }}</span>
                                        @endif
                                    </a>

                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                @include('theme.layouts._parts.__messages')

                <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th>{{ __('invoice.table.number') }}</th>
                            <th>{{ __('invoice.table.client') }}</th>
                            <th>{{ __('invoice.table.date_invoice') }}</th>
                            <th>{{ __('invoice.table.total_ht') }}</th>
                            <th>{{ __('invoice.table.total_tva') }}</th>

                            <th>{{ __('invoice.table.date_due') }}</th>
                            <th>Status</th>
                            <th>Règlement</th>

                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach ($invoices as $invoice)
                            <tr>
                                <td>
                                    <a href="{{ $invoice->url }}" class="text-body fw-bold">
                                        <i class="bx bx-hash"></i> {{ $invoice->code }}
                                    </a>
                                    <p style="color:#556ee6">
                                        <i class="bx bx-buildings"></i> <b>{{ optional($invoice->company)->name }}</b>
                                    </p>
                                    @if ($invoice->ticket_count > 0 || $invoice->tickets_count > 0)
                                        <p class="text-muted mb-0">

                                            @if ($invoice->ticket_count)
                                                <span class="badge rounded-pill bg-primary">
                                                    {{ $invoice->ticket?->code }}
                                                </span>
                                            @else
                                                @foreach ($invoice->tickets as $tickett)
                                                    <span class="badge rounded-pill bg-primary"> {{ $tickett->code }}
                                                    </span>{!! $loop->remaining % 4 == 0 ? '<br>' : '' !!}
                                                @endforeach
                                            @endif
                                        </p>
                                    @endif
                                </td>
                                <td style="white-space:normal; !important">
                                    <a href="{{ optional($invoice->client)->url }}" class="text-body fw-bold">
                                        {{ optional($invoice->client)->entreprise }}
                                    </a>
                                </td>
                                <td>
                                    {{ $invoice->invoice_date?->format('d-m-Y') }}
                                </td>
                                <td>
                                    {{ $invoice->formated_price_ht }} DH
                                </td>
                                <td>
                                    {{ $invoice->formated_total_tva }} DH
                                </td>

                                <td>
                                    {{ $invoice->due_date?->format('d-m-Y') }}
                                </td>
                                <td>
                                    @php
                                        $status = $invoice->status;
                                        $textt = '';
                                        $color = '';
                                        if ($status == 'paid') {
                                            $textt = 'PAYÉE';
                                            $color = 'info';
                                        } elseif ($status == 'non-paid') {
                                            $textt = 'A régler';
                                            $color = 'warning';
                                        } else {
                                            $textt = 'IMPAYÉE';
                                            $color = 'warning';
                                        }
                                    @endphp

                                    <i class="mdi mdi-circle text-{{ $color }} font-size-10"></i>
                                    {{ $textt }}
                                </td>
                                <td>
                                    @if ($invoice->bill_count && $invoice->status == 'paid' && !$invoice->avoir_count)
                                        {{-- <button type="button" class="btn btn-info  btn-sm" data-bs-toggle="modal"
                                            data-bs-target=".orderdetailsModal-{{ $invoice->id }}">
                                            Détails
                                        </button> --}}

                                        <a href="{{ route('commercial:bills.edit', $invoice->bill?->uuid) }}"
                                            target="__blank" class="btn btn-info  btn-sm">

                                            Détails
                                        </a>
                                    @else
                                        @if ($invoice->avoir_count && $invoice->avoir()->count() > 0)
                                            <a title="Facture Avoir N° : {{ $invoice->avoir?->code }}" target="_blank"
                                                href="{{ route('public.show.invoice.avoir', [$invoice->avoir?->uuid, 'has_header' => true]) }}"
                                                type="button" class="btn btn btn-danger btn-sm">
                                                Annulé par avoir
                                            </a>
                                        @else
                                            <button type="button" class="btn btn-warning  btn-sm"
                                                data-bs-toggle="modal"
                                                data-bs-target=".addPaymentToInvoice-{{ $invoice->uuid }}">
                                                Régler
                                            </button>
                                        @endif
                                    @endif
                                </td>

                                <td>
                                    <div class="d-flex gap-3">

                                        <a href="{{-- route('public.show.invoice',[$invoice->uuid,'has_header'=>true]) --}}" target="__blank" class="text-success"
                                            data-bs-toggle="modal" data-bs-target=".printInvoice-{{ $invoice->uuid }}">
                                            <i class="mdi mdi-file-pdf-outline font-size-18"></i>
                                        </a>

                                        <a href="{{ $invoice->edit_url }}" class="text-success">
                                            <i class="mdi mdi-pencil font-size-18"></i>
                                        </a>

                                    </div>
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>

            </div>
           
                <div class="card-body">
                    <div class="col-lg-12 mb-4">
                 
                        {{ $invoices->onEachSide(5)->links() }}
                    </div>
                </div>
          
        </div>
    </div>
</div>
