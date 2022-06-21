@extends('theme.layouts.app')

@section('content')
    <div class="container-fluid">

        @include('theme.pages.Home.sections.section_0_page_title')

        @role('SuperAdmin')
            <div class="row">

                @include('theme.pages.Home.sections.section_a_period')
            </div>

            <div class="row">

                @include('theme.pages.Home.sections.section_b_b')
            </div>
        @endrole

        <div class="row">

            @include('theme.pages.Home.sections.section_a_orders')
        </div>

        <div class="row">
            @include('theme.pages.Home.sections.section_a_chart')
        </div> 

        @role('SuperAdmin')
            <div class="row">

                @include('theme.pages.Home.sections.section_c_c')

            </div>
            <div class="row">

                @include('theme.pages.Home.sections.section_a_a')
            </div>
        @endrole

    </div>
@endsection


@push('scripts')
    {!! $chart->renderChartJsLibrary() !!}
    {!! $chart->renderJs() !!}

    {!! $chart2->renderChartJsLibrary() !!}
    {!! $chart2->renderJs() !!}

    <script src="{{ asset('js/pages/dashboard.init.js') }}"></script>
@endpush
