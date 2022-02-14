@include('theme.pages.Ticket.__datatable.__with_options')

{{-- @include('theme.pages.Ticket.__datatable.__default') --}}

@auth('admin')
    @if (auth()->user()->can('ticket.create.invoice'))
        @include('theme.pages.Ticket.__datatable.__pert_facture')
    @endif
@endauth
