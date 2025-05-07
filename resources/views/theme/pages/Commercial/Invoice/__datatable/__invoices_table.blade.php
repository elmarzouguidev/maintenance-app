{{--@include('theme.pages.Commercial.Invoice.__datatable.__top')--}}

@include('theme.pages.Commercial.Invoice.__datatable.__new_filter')

@include('theme.pages.Commercial.Invoice.__datatable.__with_options')

@each('theme.pages.Commercial.Invoice.__datatable.__payment_detail_modal',$invoices ,'invoice' )


@each('theme.pages.Commercial.Invoice.__datatable.__add_payment',$invoices ,'invoice' ) 

@each('theme.pages.Commercial.Invoice.__edit.__print_document',$invoices,'invoice')

{{--@each('theme.pages.Commercial.Invoice.__datatable.__show_invoice_tickets',$invoices ,'invoice' )--}}

{{--@each('theme.pages.Commercial.Invoice.__datatable.__send_invoice',$invoices ,'invoice' )--}}
