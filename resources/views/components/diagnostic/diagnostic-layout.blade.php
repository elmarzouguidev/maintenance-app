@props(['tickets'])

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">List de Diagnostique</h4>

                <!-- Nav tabs -->
                <x-diagnostic.tab-nav :tickets="$tickets" />

                <!-- Tab panes -->
                <x-diagnostic.tab-content :tickets="$tickets" />
            </div>
        </div>
    </div>
</div>
