<div class="vertical-menu">

    <div data-simplebar class="h-100">

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title" key="t-menu">Menu</li>

                <li>
                    <a href="{{route('admin:home')}}" class="waves-effect">
                        <i class="bx bx-home-circle"></i><span class="badge rounded-pill bg-info float-end">04</span>
                        <span key="t-dashboards">Dashboard</span>
                    </a>
                </li>

                
                <li class="menu-title" key="t-apps">Application</li>

                <li>
                    <a href="{{route('admin:calendar')}}" class="waves-effect">
                        <i class="bx bx-calendar"></i>
                        <span key="t-calendar">Calendar</span>
                    </a>
                </li>
                
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-task"></i>
                        <span key="t-tasks">Tickets</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{route('admin:tickets.list')}}" key="t-task-list">Tickets</a></li>
                    
                        <li><a href="{{route('admin:tickets.create')}}" key="t-create-task">Create Ticket</a></li>

                    </ul>
                </li>
               
                @auth('technicien')
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-task"></i>
                        <span key="t-diagnostic">Mes diagnostiques</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{route('admin:diagnostic.index')}}" key="t-diagnostic-list">Mes diagnostiques</a></li>
                       
                
                    </ul>
                </li>
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-task"></i>
                        <span key="t-diagnostic">Réparation</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{route('admin:reparations.index')}}" key="t-diagnostic-list">Mes Réparation</a></li>
                    </ul>
                </li>
                @endauth
                @auth('admin')
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-task"></i>
                        <span key="t-diagnostic">Diagnostiques</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{route('admin:diagnostic.index')}}" key="t-diagnostic-list">Diagnostiques</a></li>
                       
                    </ul>
                </li>
                @endauth
                @auth('reception')
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bxs-user-detail"></i>
                        <span key="t-clients">Clients</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{route('admin:clients.index')}}" key="t-clients-list">Clients</a></li>
                        <li><a href="{{route('admin:clients.create')}}" key="t-create-clients">Ajouter Client</a></li>
                       
                    </ul>
                </li>
                @endauth
            
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bxs-user-detail"></i>
                        <span key="t-clients">Clients</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{route('admin:clients.index')}}" key="t-clients-list">Clients</a></li>
                        <li><a href="{{route('admin:clients.create')}}" key="t-create-clients">Ajouter Client</a></li>
                       
                    </ul>
                </li>
               

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-file"></i>
                        <span key="t-clients">Catégories</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{route('admin:categories.index')}}" key="t-categories-list">Catégories</a></li>
                        <li><a href="{{route('admin:categories.index')}}" key="t-create-categories">Ajouter Categorie</a></li>
                       
                    </ul>
                </li>
                {{--<li>
                    <a href="{{route('admin:chat.index')}}" class="waves-effect">
                        <i class="bx bx-chat"></i>
                        <span key="t-chat">Chat</span>
                    </a>
                </li>--}}
            
                {{--<li>
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
                </li>--}}
               
                <li>
                    <a href="{{route('admin:contacts')}}" class="waves-effect">
                        <i class="bx bxs-user-detail"></i>
                        <span key="t-contacts">Contacts</span>
                    </a>
                </li>
               
             
                <li class="menu-title" key="t-pages">Authentication</li>

                <li>
                    <a href="javascript: void(0);" class="waves-effect">
                        <span class="badge rounded-pill bg-success float-end" key="t-new">New</span>
                        <i class="bx bx-user-circle"></i>
                        <span key="t-authentication">Authentication</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{route('admin:admins')}}" key="t-login">Admins</a></li>
                        <li><a href="{{route('admin:techniciens.list')}}" key="t-login">Techniciens</a></li>
                        <li><a href="{{route('admin:receptions.list')}}" key="t-login">Receptions</a></li>
                    </ul>
                </li>
              
                <li class="menu-title" key="t-components">Advanced</li>
                <li>
                    <a href="javascript: void(0);" class="waves-effect">
                        <span class="badge rounded-pill bg-success float-end" key="t-new">New</span>
                        <i class="bx bx-user-circle"></i>
                        <span key="t-authentication">Permissions et roles</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{route('admin:permissions-roles.index')}}" key="t-login">Roles</a></li>
                        <li><a href="{{route('admin:permissions-roles.permissions')}}" key="t-login">Permissions</a></li>
                    </ul>
                </li>
            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>