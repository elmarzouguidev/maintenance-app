@extends('theme.layouts.app')

@section('content')

    <div class="container-fluid">

        @include('theme.pages.Commercial.Invoice.__detail.invoice_detail_top')

        @include('theme.pages.Commercial.Invoice.__detail.invoice')

    </div>

@endsection

@section('css')

    <style type="text/css" media="print">
  

  @media print {
    @page {
        margin-top: 0;
        margin-bottom: 0;
    }
    body {
        padding-top: 720px;
        padding-bottom: 72px ;
    }
}

    </style>

    @livewireStyles

@endsection

@section('meta')

@endsection

@push('scripts')

    @livewireScripts

    <script>
        function printDiv(divName) {
            var printContents = document.getElementById(divName).innerHTML;
            var originalContents = document.body.innerHTML;

            document.body.innerHTML = printContents;

            window.print();

            document.body.innerHTML = originalContents;
        }
    </script>

@endpush
