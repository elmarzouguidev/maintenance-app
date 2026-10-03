<x-app.page-header
    :title="'Devis ' . $estimate->code"
    :breadcrumbs="[['label' => 'Devis', 'route' => 'commercial:estimates.index'], ['label' => $estimate->code]]" />
