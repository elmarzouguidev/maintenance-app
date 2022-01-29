@extends('theme.layouts.app')

@section('content')

    <div class="container-fluid">

        @include('theme.pages.Importer.section_0_page_title')

        {{-- @include('theme.pages.Importer.__form') --}}
        @livewire('application.importer.c-s-v-file-importer')

    </div>

@endsection

@section('css')

    @livewireStyles

    <link href="{{ asset('css/animations.css') }}" rel="stylesheet" type="text/css" />

@endsection

@push('scripts')

    @livewireScripts

@endpush
