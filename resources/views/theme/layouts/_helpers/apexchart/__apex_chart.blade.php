@push('scripts')
    <script src="{{ asset('assets/libs/apexcharts/apexcharts.min.js') }}"></script>

    {{-- <script src="{{ global_asset('js/pages/apexcharts.init.js') }}?ver={{ rand(450, 5554) }}"></script> --}}

    @include('theme.layouts._helpers.apexchart.LineChart')
@endpush
