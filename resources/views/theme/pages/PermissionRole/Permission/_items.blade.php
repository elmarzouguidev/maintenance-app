<div class="grid grid-cols-12 gap-x-6">
    <div class="col-span-6">
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
                <x-table light-head nowrap>
                    <x-slot:head>
                        <tr>
                            <th class="w-5">
                                <x-checkbox x-ref="checkAll" x-on:change="toggleAll($event.target.checked)" class="text-16" aria-label="Tout sélectionner" />
                            </th>
                            <th>Permission ID</th>
                            <th>Nom</th>
                            <th>Type</th>
                            <th>Action</th>
                        </tr>
                    </x-slot:head>

                    @foreach ($permissions as $permission)
                        <tr>
                            <td>
                                <x-checkbox data-row-check x-on:change="syncAll()" class="text-16" aria-label="Sélectionner la permission {{ $permission->name }}" />
                            </td>
                            <td><span class="font-bold text-body">#{{ $permission->id }}</span></td>
                            <td>{{ $permission->name }}</td>
                            <td>{{ $permission->guard_name }}</td>
                            <td></td>
                        </tr>
                    @endforeach
                </x-table>
            </div>

            {{ $permissions->links('vendor.pagination.tailwind-rounded') }}
        </x-card>
    </div>

    <div class="col-span-6">
        <x-card title="Ajouter une permission" description="Fill all information below">
            @if (session('success'))
                <x-alert variant="success" class="mb-4">{{ session('success') }}</x-alert>
            @endif

            <form id="clientForm" action="{{ route('admin:permissions-roles.add.permissions') }}" method="post" class="flex flex-col gap-4">
                @csrf

                <x-input name="name" label="Permission *" />

                <x-textarea name="description" label="Description de role" rows="5" />

                <div class="flex flex-wrap gap-2">
                    <x-button>Enregistrer</x-button>
                </div>
            </form>
        </x-card>
    </div>
</div>
