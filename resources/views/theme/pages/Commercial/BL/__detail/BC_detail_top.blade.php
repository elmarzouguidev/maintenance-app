<x-app.page-header
    :title="'Bon de livraison ' . $command->code"
    :breadcrumbs="[['label' => 'Bons de livraison', 'route' => 'commercial:blivraison.index'], ['label' => $command->code]]" />
