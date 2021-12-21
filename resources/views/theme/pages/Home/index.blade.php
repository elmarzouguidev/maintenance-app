@extends('theme.layouts.app')

@section('content')

    <div class="container-fluid">

        @includePart('Home.sections.section_0_page_title')

        <div class="row">
            @includePart('Home.sections.section_a_welcom')
            @includePart('Home.sections.section_a_orders')
        </div>

        <div class="row">

            @includePart('Home.sections.section_b_social_source')
            @includePart('Home.sections.section_b_activity')
            @includePart('Home.sections.section_b_top')
            
        </div>
 
        <div class="row">
            @includePart('Home.sections.section_c_transactions')
        </div>

    </div>

@endsection

@once
    @push('scripts')

    <script src="{{asset('js/libs/apexcharts/apexcharts.min.js')}}"></script>

    <script src="{{asset('js/pages/dashboard.init.js')}}"></script>

    @endpush
@endonce