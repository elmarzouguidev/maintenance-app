<x-app.page-header
    :title="'Règlement ' . $command->b_code"
    :breadcrumbs="[['label' => 'Règlements', 'route' => 'commercial:bills.index'], ['label' => $command->b_code]]" />
