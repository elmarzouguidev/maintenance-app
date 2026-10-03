@if (isset($ticket))
    <x-app.page-header
        :title="'Réparation du ticket ' . $ticket->code"
        :breadcrumbs="[['label' => 'Réparations', 'route' => 'admin:reparations.index'], ['label' => $ticket->code]]" />
@else
    <x-app.page-header
        title="Suivi des réparations"
        description="Retrouvez les appareils à prendre en charge, en cours de réparation et prêts à être livrés."
        :breadcrumbs="[['label' => 'Réparations']]" />
@endif
