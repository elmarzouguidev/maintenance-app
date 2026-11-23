<div class="grid grid-cols-12 gap-x-6">
    <div class="col-span-12 xl:col-span-6">
        <x-card>
            <h4 class="mb-6 text-15 font-semibold">{{ $invoiceChart['title'] }}</h4>

            <x-apex-chart :chart="$invoiceChart" class="w-full" />
        </x-card>
    </div>

    <div class="col-span-12 xl:col-span-6">
        <x-card>
            <h4 class="mb-6 text-15 font-semibold">{{ $billChart['title'] }}</h4>

            <x-apex-chart :chart="$billChart" class="w-full" />
        </x-card>
    </div>
</div>
