<x-app.page-header
    :title="__('invoice.invoices') . ' d’avoir'"
    :breadcrumbs="[['label' => 'Commercial'], ['label' => __('invoice.invoices') . ' d’avoir']]">
    @can('invoices.create')
        <a href="{{ route('commercial:invoices.create.avoir') }}" class="btn btn-primary d-inline-flex align-items-center gap-2">
            <i class="bx bx-plus" aria-hidden="true"></i>
            <span>{{ __('invoice.invoices_add') }}</span>
        </a>
    @endcan
</x-app.page-header>
