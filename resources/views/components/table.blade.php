{{--
    Table (Bootstrap .table, .table-responsive, .table-bordered, .table-hover, .table-nowrap, thead.table-light).
    Rows go in the default slot (tbody); header rows in `head`, footer rows in `foot`.
    Attributes (id, class, data-*) go on the <table>, so DataTables selectors keep working.
--}}
@props([
    'bordered' => false,
    'hover' => false,
    'striped' => false,
    'nowrap' => false,
    'lightHead' => false,
    'responsive' => true,
])

@php
    $classes = [
        'mb-4 w-full border-collapse text-start align-middle text-body',
        '[&_td]:border-b [&_td]:border-line [&_td]:p-3 [&_td]:align-middle',
        '[&_th]:border-b [&_th]:border-line [&_th]:p-3 [&_th]:text-start [&_th]:align-middle [&_th]:font-semibold',
        $bordered ? 'border border-line [&_td]:border [&_th]:border' : '',
        $hover ? '[&_tbody_tr:hover]:bg-hover' : '',
        $striped ? '[&_tbody_tr:nth-child(odd)]:bg-hover' : '',
        $nowrap ? '[&_td]:whitespace-nowrap [&_th]:whitespace-nowrap' : '',
        $lightHead ? '[&_thead_th]:bg-light [&_thead_th]:text-black' : '',
    ];
@endphp

@if ($responsive)
    <div class="w-full overflow-x-auto">
@endif

<table {{ $attributes->class($classes) }}>
    @isset($head)
        <thead>{{ $head }}</thead>
    @endisset

    <tbody>{{ $slot }}</tbody>

    @isset($foot)
        <tfoot>{{ $foot }}</tfoot>
    @endisset
</table>

@if ($responsive)
    </div>
@endif
