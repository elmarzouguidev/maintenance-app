@props(['tickets', 'clients', 'techniciens'])

<!-- Filters Section -->
<x-diagnostic.admin.filters :clients="$clients" :techniciens="$techniciens" />

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">List de Diagnostique</h4>
                <p class="card-title-desc"></p>

                <!-- Nav tabs -->
                <x-diagnostic.admin.tab-nav :tickets="$tickets" />

                <!-- Tab panes -->
                <x-diagnostic.admin.tab-content :tickets="$tickets" />
            </div>
        </div>
    </div>
</div>
