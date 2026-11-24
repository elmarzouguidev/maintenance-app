{{--
    A single-series monthly chart (ApexCharts, vendored in public/assets/libs): <x-charts.monthly :chart="..." />,
    with $chart from App\Support\MonthlyTotals::chart(). Not part of ui-kit/ (the kit has no chart
    component): colours come from the kit tokens at runtime, the series in chart-1, axis text in
    fg-muted, the grid in line, marker rings in the card surface. One series needs no legend: the
    card title names it. The data is also given as a table ("Voir les données"), as the kit's chart
    rule asks. $chart: ['title' => string, 'type' => 'line'|'bar', 'labels' => list<string>, 'values' => list<float>].
--}}
@props(['chart'])
@php($chartId = 'chart-'.\Illuminate\Support\Str::slug($chart['title']))

<section aria-labelledby="{{ $chartId }}-title" class="admin-card min-w-0">
    <div class="admin-card-header">
        <h2 id="{{ $chartId }}-title" class="admin-section-title">{{ $chart['title'] }}</h2>
    </div>

    <div class="admin-card-body space-y-4">
        @if ($chart['values'] === [])
            <p class="py-10 text-center text-small text-fg-muted">Aucune donnée pour le moment.</p>
        @else
            <div id="{{ $chartId }}" class="border-line text-fg-muted" aria-hidden="true">
                <span data-series-colour class="hidden text-chart-1"></span>
            </div>

            <details>
                <summary class="cursor-pointer rounded-control text-small font-medium text-accent hover:underline focus-visible:outline-2 focus-visible:outline-focus">Voir les données</summary>
                <x-admin.table class="mt-3" :headers="['Mois', $chart['title']]">
                    @foreach ($chart['labels'] as $index => $label)
                        <tr>
                            <td>{{ $label }}</td>
                            <td><x-admin.amount :value="$chart['values'][$index]" /></td>
                        </tr>
                    @endforeach
                </x-admin.table>
            </details>
        @endif
    </div>
</section>

@if ($chart['values'] !== [])
    @once
        @push('scripts')
            <script src="{{ asset('assets/libs/apexcharts/apexcharts.min.js') }}"></script>
        @endpush
    @endonce

    @push('scripts')
        <script>
            (function() {
                const element = document.getElementById(@js($chartId));
                const styles = getComputedStyle(element);
                const seriesColour = getComputedStyle(element.querySelector('[data-series-colour]')).color;
                const surface = getComputedStyle(element.closest('.admin-card')).backgroundColor;
                const isLine = @js($chart['type'] === 'line');
                const number = new Intl.NumberFormat('fr-FR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                const amount = (value) => number.format(value) + ' ' + @js(\App\Support\UiKit\Format::$currency);

                new ApexCharts(element, {
                    chart: {
                        type: @js($chart['type']),
                        height: 300,
                        fontFamily: styles.fontFamily,
                        foreColor: styles.color,
                        toolbar: { show: false },
                        zoom: { enabled: false },
                    },
                    series: [{ name: @js($chart['title']), data: @js($chart['values']) }],
                    xaxis: { categories: @js($chart['labels']), axisBorder: { color: styles.borderTopColor }, axisTicks: { show: false } },
                    yaxis: { labels: { formatter: amount } },
                    colors: [seriesColour],
                    dataLabels: { enabled: false },
                    legend: { show: false },
                    stroke: { width: isLine ? 2 : 0, curve: 'smooth' },
                    markers: { size: isLine ? 4 : 0, strokeColors: surface, strokeWidth: 2, hover: { sizeOffset: 2 } },
                    plotOptions: { bar: { borderRadius: 4, borderRadiusApplication: 'end', columnWidth: '50%' } },
                    grid: { borderColor: styles.borderTopColor, xaxis: { lines: { show: false } } },
                    tooltip: { y: { formatter: amount } },
                }).render();
            })();
        </script>
    @endpush
@endif
