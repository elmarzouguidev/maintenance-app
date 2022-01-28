@extends('theme.layouts.app')

@section('content')

    <div class="container-fluid">

        @include('theme.pages.Commercial.Invoice.section_0_title')

         @include('theme.pages.Commercial.Invoice.__list.__invoices')

    </div>

@endsection

@section('css')

    @livewireStyles

@endsection

@push('scripts')

    @livewireScripts

    @include('theme.pages.Commercial.Invoice.__js');

@endpush
