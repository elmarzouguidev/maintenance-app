@include('theme.pages.Commercial.Invoice.__datatable.__with_options')

{{-- @include('theme.pages.Commercial.Invoice.__datatable.__payment_detail_modal') --}}

@each('theme.pages.Commercial.Invoice.__datatable.__payment_detail_modal',$invoices ,'invoice' )
