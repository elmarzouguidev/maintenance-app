@extends('theme.layouts.app')

@section('content')
<div class="container-fluid">
    @include('theme.pages.Diagnostic.__admin.title')
    
    <x-diagnostic.admin.diagnostic-layout :tickets="$tickets" :clients="$clients" :techniciens="$techniciens" />
</div>
@endsection



@section('css')
    <link href="{{ asset('assets/libs/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}" rel="stylesheet" type="text/css">
@endsection

@push('scripts')
    <script src="{{ asset('assets/libs/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/libs/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            // Initialize Select2 for filters
            $(".select2").select2({
                width: '100%'
            });
        });
    </script>

    <x-diagnostic.admin.js-filters />
@endpush
<x-diagnostic.assets />