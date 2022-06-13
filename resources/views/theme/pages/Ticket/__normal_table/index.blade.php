@extends('theme.layouts.app')

@section('content')
    <div class="container-fluid">

        @include('theme.pages.Ticket.section_0_page_title')

        @include('theme.pages.Ticket.__normal_table.__filters')

        @include('theme.pages.Ticket.__normal_table.table')

    </div>
@endsection

@section('css')

    <link href="{{ asset('assets/libs/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />

    <link href="{{ asset('assets/libs/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}" rel="stylesheet"
    type="text/css">
@endsection

@push('scripts')
  

    <script src="{{ asset('js/pages/datatables.init.js') }}"></script>

    <script src="{{ asset('assets/libs/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>

    <script src="{{ asset('assets/libs/select2/js/select2.min.js') }}"></script>

    <script>

        $(".select2").select2({
            width: '100%'
        });

    </script>

    @include('theme.pages.Ticket.__datatable.__js_filters')

@endpush
