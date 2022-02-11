@extends('theme.layouts.app')

@section('content')

<div class="container-fluid">

    @include('theme.pages.Diagnostic.section_0_page_title')

    @include('theme.pages.Diagnostic.__admin.__datatable.__tickets_table')

</div>

@endsection