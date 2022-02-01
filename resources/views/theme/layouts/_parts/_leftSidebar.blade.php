<div class="vertical-menu">

    <div data-simplebar class="h-100">

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title" key="t-menu">Menu</li>

                <li>
                    <a href="{{ route('admin:home') }}" class="waves-effect">
                        <i class="bx bx-home-circle"></i><span class="badge rounded-pill bg-info float-end">04</span>
                        <span key="t-dashboards">{{ __('navbar.dashboard') }}</span>
                    </a>
                </li>
                <li class="menu-title" key="t-apps">{{ __('navbar.commercial') }}</li>
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-file"></i>
                        <span key="t-factures">{{ __('navbar.commercial') }}</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li>
                            <a href="{{ route('commercial:companies.index') }}" key="t-companies-list">
                                {{ __('navbar.companies') }}
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('commercial:invoices.index') }}" key="t-factures-list">
                                {{ __('navbar.invoices') }}
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('commercial:estimates.index') }}" key="t-factures-devis">
                                {{ __('navbar.estimates') }}
                            </a>
                        </li>
                        <li><a href="{{ route('commercial:bcommandes.index') }}"
                                key="t-bc-list">{{ __('navbar.bc') }}</a></li>
                        <li><a href="{{ route('commercial:documents.bl') }}"
                                key="t-bl-list">{{ __('navbar.bl') }}</a></li>
                        <li>
                            <a href="{{ route('commercial:providers.index') }}" key="t-factures-devis">
                               Fournisseurs
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="menu-title" key="t-apps">{{ __('navbar.application') }}</li>

                <li>
                    <a href="{{ route('admin:calendar') }}" class="waves-effect">
                        <i class="bx bx-calendar"></i>
                        <span key="t-calendar">{{ __('navbar.calendar') }}</span>
                    </a>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-task"></i>
                        <span key="t-tasks">{{ __('navbar.tickets') }}</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{ route('admin:tickets.list') }}"
                                key="t-task-list">{{ __('navbar.tickets') }}</a></li>

                        <li><a href="{{ route('admin:tickets.create') }}"
                                key="t-create-task">{{ __('navbar.tickets_add') }}</a></li>

                    </ul>
                </li>

                @auth('technicien')
                    <li>
                        <a href="javascript: void(0);" class="has-arrow waves-effect">
                            <i class="bx bx-task"></i>
                            <span key="t-diagnostic">{{ __('navbar.diagnostic_tech') }}</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            <li>
                                <a href="{{ route('admin:diagnostic.index') }}" key="t-diagnostic-list">
                                    {{ __('navbar.diagnostic_tech') }}
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="javascript: void(0);" class="has-arrow waves-effect">
                            <i class="bx bx-task"></i>
                            <span key="t-diagnostic">{{ __('navbar.reparations') }}</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            <li>
                                <a href="{{ route('admin:reparations.index') }}" key="t-diagnostic-list">
                                    {{ __('navbar.reparations_tech') }}
                                </a>
                            </li>
                        </ul>
                    </li>
                @endauth
                @auth('admin')
                    <li>
                        <a href="javascript: void(0);" class="has-arrow waves-effect">
                            <i class="bx bx-task"></i>
                            <span key="t-diagnostic">{{ __('navbar.diagnostic') }}</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            <li><a href="{{ route('admin:diagnostic.index') }}"
                                    key="t-diagnostic-list">{{ __('navbar.diagnostic') }}</a>
                            </li>

                        </ul>
                    </li>
                @endauth

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bxs-user-detail"></i>
                        <span key="t-clients">{{ __('navbar.clients') }}</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{ route('admin:clients.index') }}"
                                key="t-clients-list">{{ __('navbar.clients') }}</a></li>
                        <li><a href="{{ route('admin:clients.create') }}"
                                key="t-create-clients">{{ __('navbar.clients_add') }}</a>
                        </li>

                    </ul>
                </li>


                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-file"></i>
                        <span key="t-clients">{{ __('navbar.categories') }}</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{ route('admin:categories.index') }}"
                                key="t-categories-list">{{ __('navbar.categories') }}</a>
                        </li>
                    </ul>
                </li>
                {{-- <li>
                    <a href="{{route('admin:chat.index')}}" class="waves-effect">
                        <i class="bx bx-chat"></i>
                        <span key="t-chat">Chat</span>
                    </a>
                </li> --}}

                {{-- <li>
                    <a href="apps-filemanager.html" class="waves-effect">
                        <i class="bx bx-file"></i>
                        <span class="badge rounded-pill bg-success float-end" key="t-new">New</span>
                        <span key="t-file-manager">File Manager</span>
                    </a>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-envelope"></i>
                        <span key="t-email">Email</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{route('admin:emails.inbox')}}" key="t-inbox">Inbox</a></li>
                        <li><a href="#" key="t-read-email">Compose Email</a></li>
                        <li>
                            <a href="javascript: void(0);">
                                <span class="badge rounded-pill badge-soft-success float-end" key="t-new">New</span>
                                <span key="t-email-templates">Templates</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="true">
                                <li><a href="email-template-basic.html" key="t-basic-action">Basic Action</a></li>
                                <li><a href="email-template-alert.html" key="t-alert-email">Alert Email</a></li>
                                <li><a href="email-template-billing.html" key="t-bill-email">Billing Email</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>


                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-task"></i>
                        <span key="t-tasks">Tasks</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="tasks-list.html" key="t-task-list">Task List</a></li>
                        <li><a href="tasks-kanban.html" key="t-kanban-board">Kanban Board</a></li>
                        <li><a href="tasks-create.html" key="t-create-task">Create Task</a></li>
                    </ul>
                </li> --}}

                <li>
                    <a href="{{ route('admin:contacts') }}" class="waves-effect">
                        <i class="bx bxs-user-detail"></i>
                        <span key="t-contacts">{{ __('navbar.contacts') }}</span>
                    </a>
                </li>


                <li class="menu-title" key="t-pages">{{ __('navbar.authentification') }}</li>

                <li>
                    <a href="javascript: void(0);" class="waves-effect">
                        <span class="badge rounded-pill bg-success float-end" key="t-new">New</span>
                        <i class="bx bx-user-circle"></i>
                        <span key="t-authentication">{{ __('navbar.authentification') }}</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{ route('admin:admins') }}" key="t-login">{{ __('navbar.admins') }}</a></li>
                        <li><a href="{{ route('admin:techniciens.list') }}"
                                key="t-login">{{ __('navbar.techniciens') }}</a></li>
                        <li><a href="{{ route('admin:receptions.list') }}"
                                key="t-login">{{ __('navbar.receptions') }}</a></li>
                    </ul>
                </li>

                <li class="menu-title" key="t-components">{{ __('navbar.advanced') }}</li>
                <li>
                    <a href="javascript: void(0);" class="waves-effect">
                        <span class="badge rounded-pill bg-success float-end" key="t-new">New</span>

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

                <li class="menu-title" key="t-components">{{ __('navbar.files') }}</li>
                <li>
                    <a href="javascript: void(0);" class="waves-effect">
                        <span class="badge rounded-pill bg-success float-end" key="t-new">New</span>
                        <i class="bx bx-user-circle"></i>
                        <span key="t-authentication">{{ __('navbar.files') }}</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{ route('admin:files.importers.csv') }}"
                                key="t-login">{{ __('navbar.files') }} </a></li>

                    </ul>
                </li>
            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>
