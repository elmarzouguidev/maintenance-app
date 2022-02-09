@extends('theme.layouts.app')

@section('content')

    <div class="container-fluid">

        @include('theme.pages.Commercial.InvoiceAvoir.section_0_title')

         @include('theme.pages.Commercial.InvoiceAvoir.__list.__invoices')

    </div>

@endsection

@section('css')


@endsection

@push('scripts')

    @include('theme.pages.Commercial.InvoiceAvoir.__js');

@endpush
