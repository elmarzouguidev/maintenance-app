{{--
    Data table in a bordered panel, scrolling horizontally when it does not fit. Pass headers
    as an array, or write the <th> cells in the default slot with an empty headers array.
--}}
@props(['headers' => []])

<div {{ $attributes->class(['admin-table-wrap']) }}>
    <div class="overflow-x-auto">
        <table class="admin-table">
            <thead>
                <tr>
                    @if (! empty($headers))
                        @foreach ($headers as $header)
                            <th scope="col" class="admin-table-head">{{ $header }}</th>
                        @endforeach
                    @else
                        {{ $slot }}
                    @endif
                </tr>
            </thead>
            <tbody>
                {{ $slot }}
            </tbody>
        </table>
    </div>
</div>
