<x-app.page-header
    :title="__('estimate.estimates')"
    :breadcrumbs="[['label' => 'Commercial'], ['label' => __('estimate.estimates')]]">
    @can('estimates.create')
        <a href="{{ route('commercial:estimates.create') }}" class="btn btn-primary d-inline-flex align-items-center gap-2">
            <i class="bx bx-plus" aria-hidden="true"></i>
            <span>{{ __('estimate.estimates_add') }}</span>
        </a>
    @endcan
</x-app.page-header>
