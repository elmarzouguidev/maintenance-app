@extends('theme.layouts.tailwind')

@section('content')
    @include('theme.pages.Report.section_0_page_title')

    {{-- @include('theme.pages.Report.__datatable.periode_filter') --}}

    @include('theme.pages.Report.__datatable.graphs')

    @include('theme.pages.Report.__datatable.__with_options')
@endsection
