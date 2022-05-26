@extends('theme.layouts.app')

@section('content')

<div class="container-fluid">

    @include('theme.pages.Ticket.__single_v2.section_a_title')

    @include('theme.pages.Ticket.__single_v2.article_detail')

    {{--@include('theme.pages.Ticket.__single_v2.section_bas')--}}

</div>

@endsection

@push('scripts')

    <script src="{{ asset('assets/libs/magnific-popup/jquery.magnific-popup.min.js') }}"></script>

    <script src="{{ asset('js/pages/lightbox.init.js') }}"></script>

@endpush