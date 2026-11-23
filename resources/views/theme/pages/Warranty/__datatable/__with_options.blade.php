<x-card>
    <div
        x-data="{
            toggleAll(checked) { $root.querySelectorAll('[data-row-check]').forEach((box) => box.checked = checked) },
            syncAll() {
                const boxes = [...$root.querySelectorAll('[data-row-check]')];
                $refs.checkAll.checked = boxes.length > 0 && boxes.every((box) => box.checked);
            },
        }"
    >
        <x-table id="datatable-buttons" bordered nowrap :responsive="false" class="dt-responsive">
            <x-slot:head>
                <tr>
                    <th class="w-5">
                        <x-checkbox x-ref="checkAll" x-on:change="toggleAll($event.target.checked)" class="text-16" aria-label="Tout sélectionner" />
                    </th>
                    <th scope="col">Ticket</th>
                    <th scope="col">Status du Ticket</th>
                    <th scope="col">Date de début</th>
                    <th scope="col">Date de fin</th>
                </tr>
            </x-slot:head>

            @foreach ($warranties as $warranty)
                <tr>
                    <td>
                        <x-checkbox data-row-check :value="$warranty->id" x-on:change="syncAll()" class="text-16" aria-label="Sélectionner la garantie" />
                    </td>
                    <td>{{ optional($warranty->ticket)->code }}</td>
                    <td>
                        <i class="mdi mdi-circle text-10 text-info" aria-hidden="true"></i>
                        {{ __('status.statuses.'.optional($warranty->ticket)->status) }}
                    </td>
                    <td>{{ $warranty->start_at->format('d-m-Y') }}</td>
                    <td>{{ $warranty->end_at->format('d-m-Y') }}</td>
                </tr>
            @endforeach
        </x-table>
    </div>
</x-card>
