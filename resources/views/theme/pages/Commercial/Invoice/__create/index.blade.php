@extends('theme.layouts.app')

@section('content')

<div class="container-fluid">

    @include('theme.pages.Commercial.Invoice.section_0_title')

    @include('theme.pages.Commercial.Invoice.__create.__form_create')

</div>

@endsection

@section('css')

   <link href="{{asset('assets/libs/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css" />

@endsection

@once

    @push('scripts')
      <script src="{{asset('assets/libs/select2/js/select2.min.js')}}"></script>
      <script src="{{asset('js/pages/add_invoice.js')}}"></script>
      {{--@include('theme.pages.Commercial.Invoice.__create.__javascript.add_invoice')--}}
    @endpush

@endonce