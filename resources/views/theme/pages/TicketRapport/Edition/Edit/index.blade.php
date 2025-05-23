@extends('theme.layouts.app')

@section('content')

    <div class="container-fluid">

        @include('theme.pages.TicketRapport.section_0_page_title')

        @include('theme.pages.TicketRapport.Edition.Edit.__form_edit')

    </div>

@endsection

@section('css')

    <link href="{{ asset('assets/libs/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}" rel="stylesheet"
        type="text/css">
    <link href="{{ asset('assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@once

    @push('scripts')

        <script src="{{ asset('assets/libs/select2/js/select2.min.js') }}"></script>
        <script src="{{ asset('assets/libs/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
        <script src="{{ asset('assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>
        <script src="{{asset('js/pages/select_2_init.js')}}"></script>
        <script src="{{asset('assets/libs/tinymce/tinymce.min.js')}}"></script>
        <script src="{{asset('js/pages/ticket-create.init.js')}}?ver={{ rand(152,999) }}"></script>

    @endpush

@endonce
