{{--
    ApexCharts chart with the vendored library (public/assets/libs/apexcharts), styled like the
    dashboard chart. Framework-neutral markup: works on Bootstrap and Tailwind pages.
    :chart="['title' => 'Encaissements', 'type' => 'line'|'bar', 'labels' => [...], 'values' => [...]]"
    Amounts are formatted as "0.00 MAD".
--}}
@props([
    'chart',
    'height' => 300,
    'color' => '#556ee6',
])

@php
    $chartId = 'apex-chart-'.\Illuminate\Support\Str::uuid();
    $isLine = $chart['type'] === 'line';
@endphp

<div id="{{ $chartId }}" {{ $attributes }}></div>

@once
    @push('scripts')
        <script src="{{ asset('assets/libs/apexcharts/apexcharts.min.js') }}"></script>
    @endpush
@endonce

@push('scripts')
    <script>
        new ApexCharts(document.getElementById(@js($chartId)), {
            chart: { type: @js($chart['type']), height: @js($height), toolbar: { show: false }, zoom: { enabled: false } },
            series: [{ name: @js($chart['title']), data: @js($chart['values']) }],
            xaxis: { categories: @js($chart['labels']) },
            colors: [@js($color)],
            dataLabels: { enabled: false },
            stroke: { width: @js($isLine ? 3 : 0), curve: 'smooth' },
            markers: { size: @js($isLine ? 4 : 0) },
            grid: { borderColor: '#f1f1f1' },
            yaxis: { labels: { formatter: (value) => value.toFixed(2) + ' MAD' } },
            tooltip: { y: { formatter: (value) => value.toFixed(2) + ' MAD' } },
            noData: { text: 'Aucune donnée' },
        }).render();
    </script>
@endpush
