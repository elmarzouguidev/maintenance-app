{{--
    DataTables 1.10 (core, Buttons, Responsive) for pages on the Tailwind layout, without the
    Bootstrap 4 integration. Styles: resources/css/app.css. `init` picks the init script in
    public/js/pages/tailwind (Tailwind twins of the legacy js/pages/datatables.init*.js).
--}}
@props(['init' => 'datatables.init.js'])

@once
    @push('scripts')
        <script src="{{ asset('assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
        <script src="{{ asset('assets/libs/jszip/jszip.min.js') }}"></script>
        <script src="{{ asset('assets/libs/pdfmake/build/pdfmake.min.js') }}"></script>
        <script src="{{ asset('assets/libs/pdfmake/build/vfs_fonts.js') }}"></script>
        <script src="{{ asset('assets/libs/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
        <script src="{{ asset('assets/libs/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
        <script src="{{ asset('assets/libs/datatables.net-buttons/js/buttons.colVis.min.js') }}"></script>
        <script src="{{ asset('assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
        <script src="{{ asset('js/pages/tailwind/'.$init) }}"></script>
    @endpush
@endonce
