@extends('theme.layouts.app')

@section('content')
    <div class="container-fluid">

        @include('theme.pages.Home.sections.section_0_page_title')

        @hasanyrole('SuperAdmin|Admin')
        
            <div class="row">

                @include('theme.pages.Home.sections.section_a_period')

            </div>

            <div class="row">

                @include('theme.pages.Home.sections.section_b_b')

            </div>

        @endhasanyrole

        <div class="row">

            @include('theme.pages.Home.sections.section_a_orders')

        </div>


        @hasanyrole('SuperAdmin|Admin')

            <div class="row">

                @include('theme.pages.Home.sections.section_a_chart')

            </div>

            <div class="row">

                @include('theme.pages.Home.sections.section_c_c')

            </div>
            {{-- <div class="row">

                @include('theme.pages.Home.sections.section_a_a')
            </div> --}}
        @endhasanyrole

    </div>
@endsection


@push('scripts')
    {!! $chart->renderChartJsLibrary() !!}
    {!! $chart->renderJs() !!}

    {!! $chart2->renderChartJsLibrary() !!}
    {!! $chart2->renderJs() !!}

    {!! $chart3->renderChartJsLibrary() !!}
    {!! $chart3->renderJs() !!}


    <script>
        var periodeSelector = document.getElementById('select_periode');
        var companySelector = document.getElementById('select_company');

        var selected = document.getElementsByClassName("selected-filter");

        var company = document.getElementsByClassName("selected-filter-company");

        if(selected.length > 0)
        {
            periodeSelector.innerHTML = selected[0].text;  
        }

        if(company.length > 0)
        {
            companySelector.innerHTML = company[0].text;  
        }
    </script>

    <script src="{{ asset('js/pages/dashboard.init.js') }}"></script>
@endpush
