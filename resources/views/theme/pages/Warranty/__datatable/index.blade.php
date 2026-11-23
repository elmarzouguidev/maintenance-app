@extends('theme.layouts.tailwind')

@section('content')
    @include('theme.pages.Warranty.section_0_page_title')

    @include('theme.pages.Warranty.__datatable.__with_options')

    @include('theme.pages.Warranty.__datatable.__add_warranty')

    <x-datatables-scripts />
@endsection
