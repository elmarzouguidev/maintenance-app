@props([
    'new_tickets' => 0,
    'tickets_livrable' => 0, 
    'new_tickets_diagnostic_tech' => 0,
    'new_tickets_diagnostic' => 0,
    'estimates_not_send' => 0
])

@php
    // Ensure all variables are set with safe defaults
    $new_tickets = $new_tickets ?? 0;
    $tickets_livrable = $tickets_livrable ?? 0;
    $new_tickets_diagnostic_tech = $new_tickets_diagnostic_tech ?? 0;
    $new_tickets_diagnostic = $new_tickets_diagnostic ?? 0;
    $estimates_not_send = $estimates_not_send ?? 0;
@endphp

<div class="vertical-menu">
    <div data-simplebar class="h-100">
        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title" key="t-menu">Menu</li>

                <!-- Dashboard - Available to all users -->
                <x-sidebar.dashboard-item />

                <!-- Reception Menu - Only for Reception role -->
                <x-sidebar.reception-menu :tickets_livrable="$tickets_livrable" />

                <!-- Commercial Menu - Based on permissions -->
                <x-sidebar.commercial-menu :estimates_not_send="$estimates_not_send" />

                @can('categories.browse')
                    <li>
                        <a href="{{ route('admin:categories.index') }}" class="waves-effect">
                            <i class="bx bx-file"></i>
                            <span>{{ __('navbar.categories') }}</span>
                        </a>
                    </li>
                @endcan

                @can('warranty.browse')
                    <li>
                        <a href="{{ route('admin:warranty.index') }}" class="waves-effect">
                            <i class="bx bx-check-shield"></i>
                            <span>Garanties</span>
                        </a>
                    </li>
                @endcan

                <!-- Ticket Management - Based on permissions and roles -->
                <x-sidebar.ticket-menu 
                    :new_tickets="$new_tickets" 
                    :tickets_livrable="$tickets_livrable" />

                <!-- Diagnostic entry - screen selection is handled by the controller's permissions -->
                <x-sidebar.diagnostic-menu
                    :new_tickets_diagnostic_tech="$new_tickets_diagnostic_tech"
                    :new_tickets_diagnostic="$new_tickets_diagnostic" />

                <!-- Reports Menu - Based on permissions -->
                <x-sidebar.reports-menu />

                <!-- Settings Menu - Only for Admin/SuperAdmin roles -->
                <x-sidebar.settings-menu />

                <!-- Developer Menu - Only for Developper role -->
                <x-sidebar.developer-menu />

            </ul>
        </div>
    </div>
</div>
<!--------- Elmarzougui Abdelghafour ------->
