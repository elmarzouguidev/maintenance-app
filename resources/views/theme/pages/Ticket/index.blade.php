@extends('theme.layouts.app')

@section('content')

<div class="container-fluid">

    @include('theme.pages.Ticket.section_0_page_title')

    {{--@include('theme.pages.Ticket.__datatable.index')--}}

    @include('theme.pages.Ticket.__normal_table.index')

</div>

@endsection

@push('scripts')

@endpush