<x-app.page-header
    :title="'Bon de commande ' . $command->code"
    :breadcrumbs="[['label' => 'Bons de commande', 'route' => 'commercial:bcommandes.index'], ['label' => $command->code]]" />
