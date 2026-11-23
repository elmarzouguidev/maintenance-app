<div class="col-xl-12">
    <div class="row">
        <div class="col-xl-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-sm-flex flex-wrap">

                        <h4 class="card-title mb-4">{{ $invoiceChart['title'] }}</h4>

                        <x-apex-chart :chart="$invoiceChart" class="w-100" />
                    </div>

                    {{-- <div id="stacked-column-chart" class="apex-charts" dir="ltr"></div> --}}
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-sm-flex flex-wrap">

                        <h4 class="card-title mb-4">{{ $billChart['title'] }}</h4>

                        <x-apex-chart :chart="$billChart" class="w-100" />
                    </div>

                    {{-- <div id="stacked-column-chart" class="apex-charts" dir="ltr"></div> --}}
                </div>
            </div>
        </div>
    </div>
</div>
