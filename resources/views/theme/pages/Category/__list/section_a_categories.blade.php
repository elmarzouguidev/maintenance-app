<div class="row g-3">
    <div class="col-12 col-xxl-8">
        <section class="card h-100" aria-labelledby="categories-list-title">
            <div class="card-body">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                    <div>
                        <h2 id="categories-list-title" class="h5 mb-1">Catégories enregistrées</h2>
                        <p class="text-muted mb-0">Les catégories utilisées dans les dossiers clients.</p>
                    </div>
                    <span class="badge bg-primary-subtle text-primary rounded-pill">{{ $categories->count() }}</span>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Nom</th>
                                <th scope="col">Logo</th>
                                <th scope="col">Publication</th>
                                <th scope="col" class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($categories as $category)
                                <tr>
                                    <td class="text-muted">{{ $category->id }}</td>
                                    <td class="fw-medium">{{ $category->name }}</td>
                                    <td class="text-muted">{{ $category->logo ?: '—' }}</td>
                                    <td>
                                        <span class="badge rounded-pill {{ $category->is_published === 'Oui' ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }}">
                                            {{ $category->is_published }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <form id="delete-category-{{ $category->id }}" method="post" action="{{ route('admin:categories.delete') }}" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="categoryId" value="{{ $category->id }}">
                                            <button type="submit" class="btn btn-sm btn-outline-danger" aria-label="Supprimer la catégorie {{ $category->name }}">
                                                <i class="mdi mdi-delete-outline" aria-hidden="true"></i>
                                                <span class="d-none d-sm-inline">Supprimer</span>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-5 text-center">
                                        <i class="mdi mdi-shape-outline d-block fs-2 text-muted mb-2" aria-hidden="true"></i>
                                        <span class="text-muted">Aucune catégorie enregistrée.</span>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>

    <div class="col-12 col-xxl-4">
        <section class="card" aria-labelledby="category-create-title">
            <div class="card-body">
                <div class="mb-4">
                    <h2 id="category-create-title" class="h5 mb-1">Ajouter une catégorie</h2>
                    <p class="text-muted mb-0">Renseignez les informations de la nouvelle catégorie.</p>
                </div>

                @if (session('success'))
                    <div class="alert alert-success" role="status">{{ session('success') }}</div>
                @endif

                <form id="categoryForm" action="{{ route('admin:categories.store') }}" method="post">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Nom <span class="text-danger" aria-hidden="true">*</span></label>
                        <input id="name" name="name" type="text" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" rows="4" name="description">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary d-inline-flex align-items-center gap-2">
                        <i class="bx bx-plus" aria-hidden="true"></i>
                        <span>Enregistrer</span>
                    </button>
                </form>
            </div>
        </section>
    </div>
</div>
