@extends('theme.layouts.app')

@section('content')

<div class="container-fluid">

    @include('theme.pages.Ticket.section_0_page_title')

    @include('theme.pages.Ticket.__edit.form')

</div>

@endsection

@section('css')

   <link rel="stylesheet" type="text/css" href="{{asset('assets/libs/bootstrap-datepicker/css/bootstrap-datepicker.min.css')}}" />
   <link rel="stylesheet" type="text/css" href="{{asset('assets/libs/dropzone/min/dropzone.min.css')}}" />

@endsection

@once

    @push('scripts')
      <script src="{{asset('assets/libs/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>
      <script src="{{asset('assets/libs/dropzone/min/dropzone.min.js')}}"></script>
    @endpush

@endonce