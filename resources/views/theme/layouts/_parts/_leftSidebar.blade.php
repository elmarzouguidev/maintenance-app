@php
    // Ensure variables are available with defaults
    $new_tickets = $new_tickets ?? 0;
    $tickets_livrable = $tickets_livrable ?? 0;
    $new_tickets_diagnostic_tech = $new_tickets_diagnostic_tech ?? 0;
    $new_tickets_diagnostic = $new_tickets_diagnostic ?? 0;
    $estimates_not_send = $estimates_not_send ?? 0;
@endphp

<x-sidebar.main-sidebar 
    :new_tickets="$new_tickets"
    :tickets_livrable="$tickets_livrable"
    :new_tickets_diagnostic_tech="$new_tickets_diagnostic_tech"
    :new_tickets_diagnostic="$new_tickets_diagnostic"
    :estimates_not_send="$estimates_not_send" />
