<x-app.page-header
    :title="'Facture ' . $invoice->optionalcode"
    :breadcrumbs="[['label' => 'Factures', 'route' => 'commercial:invoices.index'], ['label' => $invoice->optionalcode]]" />
