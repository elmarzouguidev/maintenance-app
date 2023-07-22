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

            {{--@include('theme.pages.Home.sections.section_dd')--}}
            @livewire('dasboard.dashboard')
        @endhasanyrole

    </div>
@endsection

@include('theme.layouts._helpers.apexchart.__apex_chart')

@push('scripts')
    <script src="{{ asset('assets/libs/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
    
    <script>
        var periodeSelector = document.getElementById('select_periode');

        var companySelector = document.getElementById('select_company');

        var companyHref = document.getElementsByClassName('get-company');

        var selected = document.getElementsByClassName("selected-filter");

        var company = document.getElementsByClassName("selected-filter-company");

        if (selected.length > 0) {

            for (let item of companyHref) {
                item.classList.remove("disabled");
            }
            periodeSelector.innerHTML = selected[0].text;

        }

        if (company.length > 0) {

            companySelector.innerHTML = company[0].text;

        }
    </script>

    <script>
        function getDateFilter() {
            let startDate = document.getElementById("filterDateStart");
            let endDate = document.getElementById("filterDateEnd");
            console.log(startDate.value, "##", endDate.value);
            return [startDate.value, endDate.value];
        }

        function filterResults() {


            let getDate = getDateFilter();
            // console.log(clientId);

            let href = '{{ collect(request()->segments())->last() }}?';

            if (getDate.length) {
                href += '&appFilter[GetCompany]=1&appFilter[DateBetween]=' + getDate;
            }
            document.location.href = href;
            // return href;
        }

        document.getElementById("filterData").addEventListener("click", function(event) {

            event.preventDefault();
            filterResults();

            /*$.ajax({
                url: filterResults(),
                type: 'GET',
                success: function() {
                    console.log("it Works");
                    $("#invoices_lister").load(window.location.href + " #invoices_lister");
                }
            });*/
        });

        /*$(".chk-filter").on("click", function() {
            if (this.checked) {
               // $('#filter').click();
                filterResults()
            }
        });*/
    </script>
@endpush

@section('css')
    <link href="{{ asset('assets/libs/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}" rel="stylesheet"
        type="text/css">
@endsection
