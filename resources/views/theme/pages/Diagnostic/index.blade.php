@extends('theme.layouts.app')

@section('content')
<div class="container-fluid">
    @include('theme.pages.Diagnostic.section_0_page_title')
    
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">List de Diagnostique</h4>

                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#diagnistique-open-tech" role="tab">
                                <span class="badge rounded-pill bg-info float-end" style="font-size: 1rem;">
                                    @if (Arr::exists($tickets, 'ouvert'))
                                        {{ count($tickets['ouvert']) }}
                                    @else
                                        0
                                    @endif
                                </span>
                                <span class="d-none d-sm-block">Diagnostique ouverts</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#diagnistique-wait-tech" role="tab">
                                <span class="badge rounded-pill bg-warning float-end" style="font-size: 1rem;">
                                    @if (Arr::exists($tickets, 'en-attent-de-devis'))
                                        {{ count($tickets['en-attent-de-devis']) }}
                                    @else
                                        0
                                    @endif
                                </span>
                                <span class="d-none d-sm-block">En attente de devis</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#diagnistique-attend-bc-tech" role="tab">
                                <span class="badge rounded-pill bg-info float-end" style="font-size: 1rem;">
                                    @if (Arr::exists($tickets, 'en-attent-de-bc'))
                                        {{ count($tickets['en-attent-de-bc']) }}
                                    @else
                                        0
                                    @endif
                                </span>
                                <span class="d-none d-sm-block">En attente de BC</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#diagnistique-repare-tech" role="tab">
                                <span class="badge rounded-pill bg-info float-end" style="font-size: 1rem;">
                                    @if (Arr::exists($tickets, 'a-preparer'))
                                        {{ count($tickets['a-preparer']) }}
                                    @else
                                        0
                                    @endif
                                </span>
                                <span class="d-none d-sm-block">Produits a réparer</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#diagnistique-repare-encours-tech" role="tab">
                                <span class="badge rounded-pill bg-info float-end" style="font-size: 1rem;">
                                    @if (Arr::exists($tickets, 'encours-de-reparation'))
                                        {{ count($tickets['encours-de-reparation']) }}
                                    @else
                                        0
                                    @endif
                                </span>
                                <span class="d-none d-sm-block">Produits en cours de réparation</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#diagnistique-repare-done-tech" role="tab">
                                <span class="badge rounded-pill bg-success float-end" style="font-size: 1rem;">
                                    @if (Arr::exists($tickets, 'pret-a-etre-livre'))
                                        {{ count($tickets['pret-a-etre-livre']) }}
                                    @else
                                        0
                                    @endif
                                </span>
                                <span class="d-none d-sm-block">Produits réparé</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#diagnistique-cancled-tech" role="tab">
                                <span class="badge rounded-pill bg-danger float-end" style="font-size: 1rem;">
                                    @if (Arr::exists($tickets, 'annuler'))
                                        {{ count($tickets['annuler']) }}
                                    @else
                                        0
                                    @endif
                                </span>
                                <span class="d-none d-sm-block">Annuler par L'administration</span>
                            </a>
                        </li>
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content p-3 text-muted">
                        <div class="tab-pane active" id="diagnistique-open-tech" role="tabpanel">
                            @include('theme.pages.Diagnostic.__tap_view.tables.diagnistique-open')
                        </div>
                        <div class="tab-pane" id="diagnistique-wait-tech" role="tabpanel">
                            @include('theme.pages.Diagnostic.__tap_view.tables.diagnistique-wait')
                        </div>
                        <div class="tab-pane" id="diagnistique-attend-bc-tech" role="tabpanel">
                            @include('theme.pages.Diagnostic.__tap_view.tables.diagnistique-attend-bc')
                        </div>
                        <div class="tab-pane" id="diagnistique-repare-tech" role="tabpanel">
                            @include('theme.pages.Diagnostic.__tap_view.tables.diagnistique-a-reparer')
                        </div>
                        <div class="tab-pane" id="diagnistique-repare-encours-tech" role="tabpanel">
                            @include('theme.pages.Diagnostic.__tap_view.tables.diagnistique-encours-de-reparation')
                        </div>
                        <div class="tab-pane" id="diagnistique-repare-done-tech" role="tabpanel">
                            @include('theme.pages.Diagnostic.__tap_view.tables.diagnistique-pret-a-livre')
                        </div>
                        <div class="tab-pane" id="diagnistique-cancled-tech" role="tabpanel">
                            @include('theme.pages.Diagnostic.__tap_view.tables.diagnistique-cancled')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('css')
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