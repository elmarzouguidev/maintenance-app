<div class="vertical-menu">

    <div data-simplebar class="h-100">

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title" key="t-menu">Menu</li>

                <li>
                    <a href="
                    {{ route('admin:home') }}
                    ?appFilter[DateBetween]={{ now()->startOfYear()->format('Y-m-d') }},{{ now()->endOfYear()->format('Y-m-d') }}&appFilter[GetCompany]=1&currentYear=true"
                        class="waves-effect">
                        <i class="bx bx-home-circle"></i>{{-- <span class="badge rounded-pill bg-warning float-end">04</span> --}}
                        <span key="t-dashboards">{{ __('navbar.dashboard') }}</span>
                    </a>
                </li>

                @if (auth()->user()->hasRole('Reception'))
                    <li>
                        <a href="{{ route('admin:clients.index') }}" key="t-clients">
                            <i class="bx bx-user"></i>
                            {{ __('navbar.clients') }}
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('admin:tickets.livrable') }}" class="waves-effect">

                            @if ($tickets_livrable)
                                <span class="badge rounded-pill bg-warning float-end">.</span>
                            @endif

                            <span key="t-pret">Prét a la livraison</span>
                        </a>
                    </li>

                @endif

                {{-- @if (auth()->user()->hasAnyRole('SuperAdmin', 'Admin'))
                    <li>
                        <a href="{{ route('admin:tickets.invoiceable') }}" class="waves-effect">
                            <i class="bx bx-home-circle"> </i>
                            @if ($tickets_invoiceable)
                                <span class="badge rounded-pill bg-warning float-end">.</span>
                            @endif
                            <span key="t-pret">Prét a la Facturation</span>
                        </a>
                    </li>
                @endif --}}


                <li class="menu-title" key="t-apps">Gestion commercial</li>
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-grid-alt"></i>
                        <span key="t-factures">{{ __('navbar.commercial') }}</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">

                        @can('estimates.browse')
                            <li>
                                <a href="{{ route('commercial:estimates.index') }}" key="t-factures-devis">
                                    <i class="bx bx-file-blank"></i>

                                    {{-- <span
                                        class="badge rounded-pill bg-warning float-end">{{ $estimates_not_send }}</span> --}}

                                    {{ __('navbar.estimates') }}
                                </a>
                            </li>
                        @endcan

                        @can('invoices.browse')
                            <li>
                                <a href="{{ route('commercial:invoices.index') }}" key="t-invoice-list">
                                    <i class="bx bx-food-menu"></i>
                                    {{ __('navbar.invoices') }}
                                </a>
                            </li>
                        @endcan
                        @can('invoices.browse')
                            <li>
                                <a href="{{ route('commercial:invoices.index.avoir') }}" key="t-avoir-list">
                                    <i class="bx bx-food-menu"></i>
                                    Avoirs
                                </a>
                            </li>
                        @endcan
                        @can('payments.browse')
                            <li>
                                <a href="{{ route('commercial:bills.index') }}" key="t-factures-list">
                                    <i class="bx bx-money"></i>
                                    Règlements
                                </a>
                            </li>
                        @endcan
                        @can('bcommandes.browse')
                            <li>
                                <a href="{{ route('commercial:bcommandes.index') }}" key="t-bc-list">
                                    <i class="bx bx-file"></i>
                                    {{ __('navbar.bc') }}
                                </a>
                            </li>
                        @endcan
                        @can('blivraison.browse')
                            <li>
                                <a href="{{ route('commercial:blivraison.index') }}" key="t-bl-list">
                                    <i class="bx bx-file"></i>
                                    Bon de livraison
                                </a>
                            </li>
                        @endcan
                        @can('providers.browse')
                            <li>
                                <a href="{{ route('commercial:providers.index') }}" key="t-factures-devis">
                                    <i class="bx bx-user"></i>
                                    Fournisseurs
                                </a>
                            </li>
                        @endcan
                        @can('client.browse')
                            <li>
                                <a href="{{ route('admin:clients.index') }}" key="t-clients">
                                    <i class="bx bx-user"></i>
                                    {{ __('navbar.clients') }}
                                </a>
                            </li>
                        @endcan
                        @hasanyrole('Admin|SuperAdmin')
                            <li>
                                <a href="{{ route('commercial:reports.index') }}" key="t-reports">
                                    <i class="bx bx-line-chart"></i>
                                    Rapport Clients
                                </a>
                            </li>
                        @endhasanyrole
                    </ul>
                </li>

                <li class="menu-title" key="t-apps">Gestion de ticket</li>

                {{-- <li>
                    <a href="{{ route('admin:calendar') }}" class="waves-effect">
                        <i class="bx bx-calendar"></i>
                        <span key="t-calendar">{{ __('navbar.calendar') }}</span>
                    </a>
                </li> --}}



                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-task"></i><span
                            class="badge rounded-pill bg-warning float-end">{{ $new_tickets }}</span>
                        <span key="t-tasks">{{ __('navbar.tickets') }}</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">

                        {{-- <li>

                            <a href="{{ route('admin:warranty.index') }}" key="t-task-list">Garanties
                                
                            </a>
                        </li> --}}

                        <li>

                            <a href="{{ route('admin:tickets.list.old') }}"
                                key="t-task-list">{{ __('navbar.tickets') }}
                                <span class="badge rounded-pill bg-warning float-end">{{ $new_tickets }}</span>
                            </a>
                        </li>
                        @if (auth()->user()->hasAnyRole('SuperAdmin', 'Admin'))
                            <li>
                                <a href="{{ route('admin:tickets.livrable') }}" class="waves-effect">

                                    @if ($tickets_livrable)
                                        <span class="badge rounded-pill bg-warning float-end">.</span>
                                    @endif

                                    <span key="t-pret">Livraison</span>
                                </a>
                            </li>

                        @endif
                    </ul>
                </li>

                @role('Technicien')
                    <li>
                        <a href="javascript: void(0);" class="has-arrow waves-effect">
                            <i class="bx bx-task"></i>

                            @if ($new_tickets_diagnostic_tech)
                                <span class="badge rounded-pill bg-warning float-end">.</span>
                            @endif
                            <span key="t-diagnostic">{{ __('navbar.diagnostic_tech') }}</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            <li>
                                <a href="{{ route('admin:diagnostic.index') }}" key="t-diagnostic-list">
                                    {{ __('navbar.diagnostic_tech') }}
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('admin:rapports.index') }}" key="t-rapports-list">
                                    <i class="bx bx-task"></i>
                                    Rapports techniques
                                </a>
                            </li>
                        </ul>
                    </li>
                @endrole
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-task"></i>
                        <span key="t-tasks">Rapports</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        @can('report.browse')
                            <li>
                                <a href="{{ route('admin:rapports.index') }}" key="t-rapports-list">
                                    <i class="bx bx-task"></i>
                                    List
                                </a>
                            </li>
                        @endcan
                        @can('report.edit')
                            <li>
                                <a href="{{ route('admin:rapports.editions.index') }}" key="t-rapports-list-edit">
                                    <i class="bx bx-task"></i>
                                    Edition
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>

                @hasanyrole('Admin|SuperAdmin')
                    <li>
                        <a href="{{ route('admin:diagnostic.index') }}" class="waves-effect" key="t-diagnostic-list">
                            <i class="bx bx-task"></i>
                            @if ($new_tickets_diagnostic)
                                <span class="badge rounded-pill bg-warning float-end">.</span>
                            @endif
                            <span key="t-diagnostic">{{ __('navbar.diagnostic') }}</span>
                        </a>
                    </li>

                @endhasanyrole

                @hasanyrole('Admin|SuperAdmin')
                    <li class="menu-title" key="t-components">{{ __('Paramètres') }}</li>

                    <li>
                        <a href="{{ route('commercial:companies.index') }}" class="waves-effect">
                            {{-- <span class="badge rounded-pill bg-success float-end" key="t-new">New</span> --}}
                            <i class="bx bx-building"></i>
                            <span key="t-companies">{{ __('navbar.companies') }}</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin:admins') }}" class="waves-effect">
                            {{-- <span class="badge rounded-pill bg-success float-end" key="t-new">New</span> --}}
                            <i class="bx bx-user-circle"></i>
                            <span key="t-authentication">Utilisateurs</span>
                        </a>
                    </li>
                @endhasanyrole

                @if (auth()->user()->hasRole('Developper'))
                    <li>
                        <a href="javascript: void(0);" class="waves-effect">
                            {{-- <span class="badge rounded-pill bg-success float-end" key="t-new">New</span> --}}
                            <i class="bx bx-lock-alt"></i>
                            <span key="t-authentication">{{ __('navbar.roles_permissions') }}</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            <li><a href="{{ route('admin:permissions-roles.index') }}"
                                    key="t-login">{{ __('navbar.roles') }}</a></li>
                            <li><a href="{{ route('admin:permissions-roles.permissions') }}"
                                    key="t-login">{{ __('navbar.permissions') }}</a>
                            </li>
                        </ul>
                    </li>
                @endif

            </ul>
        </div>
    </div>
</div>
<!--------- Elmarzougui Abdelghafour ------->
