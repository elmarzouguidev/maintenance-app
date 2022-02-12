@extends('theme.layouts.app')

@section('content')

    <div class="container-fluid">

        @include('theme.pages.Home.sections.section_0_page_title')

        <div class="row">

            @include('theme.pages.Home.sections.section_a_period')
        </div>

        <div class="row">

            @include('theme.pages.Home.sections.section_a_orders')
        </div>

        <div class="row">

            @include('theme.pages.Home.sections.section_b_b')
        </div>
        <div class="row">
            @include('theme.pages.Home.sections.section_a_chart')
        </div>
        <div class="row">

            @include('theme.pages.Home.sections.section_a_a')
        </div>



        <div class="row">

            @include('theme.pages.Home.sections.section_b_social_source')
            @include('theme.pages.Home.sections.section_b_activity')
            @include('theme.pages.Home.sections.section_b_top')

        </div>


    </div>

@endsection

@once
    @push('scripts')

        <script src="{{ asset('js/libs/apexcharts/apexcharts.min.js') }}"></script>

        <script src="{{ asset('js/pages/dashboard.init.js') }}"></script>

    @endpush
@endonce
