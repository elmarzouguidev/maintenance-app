@extends('theme.layouts.app')

@section('content')

    <div class="container-fluid">

        @include('theme.pages.Commercial.Invoice.section_0_title')

         @include('theme.pages.Commercial.Invoice.__list.__invoices')

    </div>

@endsection

@section('css')


@endsection

@push('scripts')

    @include('theme.pages.Commercial.Invoice.__js');

@endpush
