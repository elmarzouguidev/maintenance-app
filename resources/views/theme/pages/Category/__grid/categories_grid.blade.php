<div class="row g-3">
    @forelse ($categories as $category)
        <div class="col-12 col-md-6 col-xl-4">
            <article class="card h-100">
                <div class="card-body d-flex align-items-start gap-3">
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title rounded-circle bg-primary-subtle text-primary fw-semibold">
                            {{ mb_substr($category->name, 0, 1) }}
                        </span>
                    </div>
                    <div class="min-w-0">
                        <h2 class="h6 text-truncate mb-1">{{ $category->name }}</h2>
                        <p class="text-muted mb-2">{{ $category->description ?: 'Aucune description.' }}</p>
                        <span class="badge rounded-pill {{ $category->is_published === 'Oui' ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }}">
                            {{ $category->is_published }}
                        </span>
                    </div>
                </div>
            </article>
        </div>
    @empty
        <div class="col-12">
            <div class="card"><div class="card-body text-center text-muted py-5">Aucune catégorie enregistrée.</div></div>
        </div>
    @endforelse
</div>
