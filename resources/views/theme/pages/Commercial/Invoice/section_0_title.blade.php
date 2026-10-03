<x-app.page-header
    :title="__('invoice.invoices')"
    :breadcrumbs="[['label' => 'Commercial'], ['label' => __('invoice.invoices')]]">
    @can('invoices.create')
        <a href="{{ route('commercial:invoices.create') }}" class="btn btn-primary d-inline-flex align-items-center gap-2">
            <i class="bx bx-plus" aria-hidden="true"></i>
            <span>{{ __('invoice.invoices_add') }}</span>
        </a>
    @endcan
</x-app.page-header>
