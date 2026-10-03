<x-app.page-header
    :title="'Ticket ' . $ticket->code"
    :breadcrumbs="[['label' => 'Tickets', 'route' => 'admin:tickets.list'], ['label' => $ticket->code]]" />
