@section('css')
    <style>
        .diagnostic-dashboard {
            --diagnostic-border: #e9edf4;
            --diagnostic-muted: #7a8495;
            --diagnostic-primary: var(--bs-primary, #556ee6);
        }

        .diagnostic-dashboard-heading {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 1.25rem;
        }

        .diagnostic-dashboard-heading h1 {
            margin: .25rem 0;
            color: #343a40;
            font-size: clamp(1.35rem, 2vw, 1.8rem);
            font-weight: 600;
        }

        .diagnostic-dashboard-heading p {
            margin: 0;
            color: var(--diagnostic-muted);
        }

        .diagnostic-eyebrow {
            color: var(--diagnostic-primary);
            font-size: .7rem;
            font-weight: 700;
            letter-spacing: .08em;
        }

        .diagnostic-total-card {
            display: flex;
            align-items: center;
            gap: .75rem;
            min-width: 190px;
            padding: .75rem 1rem;
            border: 1px solid var(--diagnostic-border);
            border-radius: 12px;
            background: #fff;
        }

        .diagnostic-total-icon,
        .diagnostic-stage-icon,
        .diagnostic-filter-icon {
            display: inline-flex;
            flex: 0 0 auto;
            align-items: center;
            justify-content: center;
        }

        .diagnostic-total-icon {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            background: #eef1ff;
            color: var(--diagnostic-primary);
            font-size: 1.35rem;
        }

        .diagnostic-total-card strong,
        .diagnostic-total-card small {
            display: block;
        }

        .diagnostic-total-card strong {
            color: #343a40;
            font-size: 1.15rem;
            line-height: 1.2;
        }

        .diagnostic-total-card small {
            color: var(--diagnostic-muted);
        }

        .diagnostic-board,
        .diagnostic-filter-card {
            overflow: hidden;
            border: 1px solid var(--diagnostic-border);
            border-radius: 12px;
            box-shadow: 0 6px 22px rgba(24, 40, 72, .045);
        }

        .diagnostic-workflow-nav {
            position: sticky;
            top: 1rem;
            padding: 1rem;
            border: 1px solid var(--diagnostic-border);
            border-radius: 10px;
            background: #f8f9fc;
        }

        .diagnostic-workflow-nav-heading {
            display: flex;
            flex-direction: column;
            gap: .15rem;
            padding: .25rem .4rem .8rem;
        }

        .diagnostic-workflow-nav-heading span {
            color: #687386;
            font-size: .7rem;
            font-weight: 700;
            letter-spacing: .07em;
        }

        .diagnostic-workflow-nav-heading small {
            color: var(--diagnostic-muted);
        }

        .diagnostic-stage-nav {
            gap: .4rem;
        }

        .diagnostic-stage-link {
            display: flex;
            align-items: center;
            gap: .7rem;
            min-height: 58px;
            padding: .65rem .7rem;
            border: 1px solid transparent;
            border-radius: 8px;
            background: transparent;
            color: #4d5869;
            text-decoration: none;
            text-align: left;
            transition: background-color .15s ease, border-color .15s ease, color .15s ease;
        }

        .diagnostic-stage-link:hover {
            border-color: #e0e5f3;
            background: #fff;
            color: #343a40;
        }

        .diagnostic-stage-link.active,
        .diagnostic-stage-link.active:hover {
            border-color: #dce3ff;
            background: #edf1ff;
            color: #384b9b;
            box-shadow: none;
        }

        .diagnostic-stage-icon {
            width: 34px;
            height: 34px;
            border-radius: 9px;
            background: #fff;
            color: #687386;
            font-size: 1.1rem;
        }

        .diagnostic-stage-link.active .diagnostic-stage-icon {
            color: var(--diagnostic-primary);
        }

        .diagnostic-stage-copy {
            display: flex;
            flex: 1 1 auto;
            min-width: 0;
            flex-direction: column;
            gap: .1rem;
            line-height: 1.25;
        }

        .diagnostic-stage-copy > span {
            font-size: .88rem;
            font-weight: 600;
        }

        .diagnostic-stage-copy small {
            color: var(--diagnostic-muted);
            font-size: .72rem;
        }

        .diagnostic-stage-count,
        .diagnostic-stage-heading-count {
            display: inline-flex;
            flex: 0 0 auto;
            align-items: center;
            justify-content: center;
            min-width: 1.8rem;
            min-height: 1.8rem;
            padding: .1rem .45rem;
            border-radius: 999px;
            background: #fff;
            color: #586477;
            font-size: .78rem;
            font-weight: 700;
        }

        .diagnostic-stage-link.active .diagnostic-stage-count {
            background: #dfe5ff;
            color: #384b9b;
        }

        .diagnostic-stage-content {
            min-width: 0;
        }

        .diagnostic-stage-heading {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding: .25rem 0 1rem;
            margin-bottom: 1rem;
            border-bottom: 1px solid var(--diagnostic-border);
        }

        .diagnostic-stage-heading > div > span {
            color: var(--diagnostic-primary);
            font-size: .72rem;
            font-weight: 700;
            letter-spacing: .04em;
            text-transform: uppercase;
        }

        .diagnostic-stage-heading h2 {
            margin: .2rem 0 0;
            color: #343a40;
            font-size: 1.15rem;
            font-weight: 600;
        }

        .diagnostic-stage-heading-count {
            background: #f2f4f8;
        }

        .diagnostic-table-wrap {
            min-width: 0;
            overflow-x: auto;
        }

        .diagnostic-table-wrap table thead th {
            padding: .85rem .75rem;
            border-bottom: 1px solid var(--diagnostic-border);
            background: #f8f9fc;
            color: #687386;
            font-size: .72rem;
            font-weight: 700;
            letter-spacing: .035em;
            text-transform: uppercase;
            vertical-align: middle;
        }

        .diagnostic-table-wrap table tbody td {
            padding: .8rem .75rem;
            color: #495466;
            vertical-align: middle;
        }

        .diagnostic-filter-card {
            margin-bottom: 1.25rem;
        }

        .diagnostic-filter-heading {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .diagnostic-filter-heading h2 {
            margin: 0 0 .15rem;
            color: #343a40;
            font-size: 1rem;
            font-weight: 600;
        }

        .diagnostic-filter-heading p {
            margin: 0;
            color: var(--diagnostic-muted);
            font-size: .85rem;
        }

        .diagnostic-filter-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: #eef1ff;
            color: var(--diagnostic-primary);
            font-size: 1.25rem;
        }

        .diagnostic-filter-card--active {
            border-color: #dce3ff;
        }

        .diagnostic-filter-card .form-label {
            color: #586477;
            font-size: .82rem;
            font-weight: 600;
        }

        @media (max-width: 1199.98px) {
            .diagnostic-workflow-nav {
                position: static;
            }

            .diagnostic-stage-nav {
                flex-direction: row;
                flex-wrap: nowrap;
                overflow-x: auto;
                padding-bottom: .4rem;
            }

            .diagnostic-stage-link {
                flex: 0 0 235px;
            }
        }

        @media (max-width: 767.98px) {
            .diagnostic-dashboard-heading {
                align-items: flex-start;
                flex-direction: column;
            }

            .diagnostic-total-card {
                width: 100%;
            }

            .diagnostic-stage-heading {
                align-items: flex-start;
                flex-direction: column;
            }

            .diagnostic-filter-heading {
                align-items: flex-start;
            }
        }
    </style>

    <!-- DataTables -->
    <link href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Responsive datatable examples -->
    <link href="{{ asset('assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@push('scripts')
    <!-- Required datatable js -->
    <script src="{{ asset('assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <!-- Buttons examples -->
    <script src="{{ asset('assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/libs/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('assets/libs/pdfmake/build/pdfmake.min.js') }}"></script>
    <script src="{{ asset('assets/libs/pdfmake/build/vfs_fonts.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-buttons/js/buttons.colVis.min.js') }}"></script>
    <!-- Responsive examples -->
    <script src="{{ asset('assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('js/pages/datatables.init.2.js') }}?ver={{ rand(143,890) }}"></script>
@endpush
