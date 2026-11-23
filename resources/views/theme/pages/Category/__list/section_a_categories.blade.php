<div class="grid grid-cols-12 gap-x-6">
    <div class="col-span-12 lg:col-span-6">
        <x-card>
            <x-table light-head nowrap hover>
                <x-slot:head>
                    <tr>
                        <th scope="col" class="w-[70px]">#</th>
                        <th scope="col">Nom</th>
                        <th scope="col">Logo</th>
                        <th scope="col">Active</th>
                        <th scope="col">Action</th>
                    </tr>
                </x-slot:head>

                @foreach ($categories as $category)
                    <tr>
                        <td>
                            <h5 class="mb-1 text-14"><span class="text-dark">{{ $category->id }}</span></h5>
                        </td>
                        <td>{{ $category->name }}</td>
                        <td>{{ $category->logo }}</td>
                        <td>{{ $category->is_published }}</td>
                        <td>
                            <form method="post" action="{{ route('admin:categories.delete') }}">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="categoryId" value="{{ $category->id }}">
                                <button type="submit" class="text-danger transition-colors hover:text-danger-hover" aria-label="Supprimer la catégorie {{ $category->name }}">
                                    <i class="mdi mdi-delete text-18" aria-hidden="true"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </x-table>
        </x-card>
    </div>

    <div class="col-span-12 lg:col-span-6">
        <x-card title="Ajouter une catégorie">
            @if (session('success'))
                <x-alert variant="success" class="mb-4">{{ session('success') }}</x-alert>
            @endif

            <form id="categoryForm" action="{{ route('admin:categories.store') }}" method="post" class="flex flex-col gap-4">
                @csrf

                <x-input name="name" label="Nom *" />

                <x-textarea name="description" label="Description" rows="5" />

                <div class="flex flex-wrap gap-2">
                    <x-button>Enregistrer</x-button>
                </div>
            </form>
        </x-card>
    </div>
</div>
