@extends('theme.layouts.app')

@section('content')

<div class="container-fluid">

    @include('theme.pages.Admin.section_0_page_title')

    @include('theme.pages.Admin.__profile.form')

</div>

@endsection


@section('css')

    <link href="{{ asset('assets/libs/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <style>
        .permission-panel {
            border: 1px solid #edf0f4;
            border-radius: 12px;
            box-shadow: 0 8px 28px rgba(15, 34, 58, .06);
        }

        .permission-panel-heading,
        .permission-panel-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
        }

        .permission-panel-heading {
            padding-bottom: 1.5rem;
            margin-bottom: 1.5rem;
            border-bottom: 1px solid #edf0f4;
        }

        .permission-panel-heading h4 {
            font-weight: 600;
        }

        .permission-panel-icon {
            display: inline-flex;
            flex: 0 0 48px;
            width: 48px;
            height: 48px;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            background: rgba(85, 110, 230, .1);
            color: var(--bs-primary, #556ee6);
            font-size: 1.5rem;
        }

        .permission-total,
        .permission-group-count {
            display: inline-flex;
            align-items: center;
            white-space: nowrap;
            border-radius: 999px;
            background: #f1f3f8;
            color: #596579;
            font-size: .8rem;
            font-weight: 600;
        }

        .permission-total {
            padding: .45rem .75rem;
        }

        .permission-group {
            overflow: hidden;
            border: 1px solid #e9edf3;
            border-radius: 10px;
            background: #fff;
        }

        .permission-group-heading {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: .75rem;
            padding: .9rem 1rem;
            border-bottom: 1px solid #e9edf3;
            background: #f8f9fc;
        }

        .permission-group-heading h5 {
            color: #343a40;
            font-size: .95rem;
            font-weight: 600;
        }

        .permission-group-count {
            min-width: 1.75rem;
            height: 1.75rem;
            justify-content: center;
            padding: 0 .45rem;
        }

        .permission-options {
            padding: .35rem .9rem;
        }

        .permission-option {
            display: flex;
            align-items: flex-start;
            gap: .65rem;
            padding: .7rem .1rem;
            border-bottom: 1px solid #f0f2f5;
        }

        .permission-option:last-child {
            border-bottom: 0;
        }

        .permission-option--inherited {
            background: #fafbfc;
        }

        .permission-option .form-check-input {
            flex: 0 0 auto;
            width: 1.05rem;
            height: 1.05rem;
            margin: .15rem 0 0;
            cursor: pointer;
        }

        .permission-option-label {
            display: flex;
            flex: 1 1 auto;
            align-items: flex-start;
            justify-content: space-between;
            gap: .5rem;
            color: #495466;
            line-height: 1.45;
            cursor: pointer;
        }

        .permission-option--inherited .form-check-input {
            opacity: .65;
            cursor: not-allowed;
        }

        .permission-source-badge {
            flex: 0 0 auto;
            padding: .2rem .45rem;
            border-radius: 999px;
            background: #edf1ff;
            color: #4356a8;
            font-size: .68rem;
            font-weight: 600;
            white-space: nowrap;
        }

        .permission-panel-footer {
            padding-top: 1.5rem;
            margin-top: 1.5rem;
            border-top: 1px solid #edf0f4;
        }

        @media (max-width: 767.98px) {
            .permission-panel-heading,
            .permission-panel-footer {
                align-items: flex-start;
                flex-direction: column;
            }

            .permission-panel-footer .btn {
                width: 100%;
            }
        }
    </style>

@endsection

@once

    @push('scripts')
      <script src="{{asset('assets/libs/select2/js/select2.min.js')}}"></script>
      <script src="{{asset('js/pages/select_2_init.js')}}"></script>
    @endpush

@endonce
