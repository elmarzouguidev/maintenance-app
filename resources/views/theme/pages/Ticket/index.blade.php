@extends('theme.layouts.app')

@section('content')

<div class="container-fluid">

    @include('theme.pages.Ticket.section_0_page_title')

    @include('theme.pages.Ticket.__list_2.index')

</div>

@endsection

@push('scripts')
    {{--<script>
        $(document).ready(function()
        {
            function refresh()
            {
                var div = $('#tickets_data'),
                    divHtml = div.html();

                div.html(divHtml);
            }

            setInterval(function()
            {
                refresh()
            }, 5000); //300000 is 5minutes in ms
        })
    </script>--}}
@endpush