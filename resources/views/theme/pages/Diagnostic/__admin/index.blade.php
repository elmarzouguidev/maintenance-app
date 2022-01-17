@extends('theme.layouts.app')

@section('content')

<div class="container-fluid">

    @include('theme.pages.Diagnostic.section_0_page_title')

    @include('theme.pages.Diagnostic.__admin.list')

</div>

@endsection