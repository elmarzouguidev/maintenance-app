<x-app.page-header
    :title="'Avoir ' . $invoice->code"
    :breadcrumbs="[['label' => 'Avoirs', 'route' => 'commercial:invoices.index.avoir'], ['label' => $invoice->code]]" />
