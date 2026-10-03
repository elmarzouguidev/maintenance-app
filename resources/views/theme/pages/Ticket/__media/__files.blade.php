@php($ticketMedia = $ticket->getMedia('tickets-images'))

<div class="row g-3">
    <div class="col-12 col-xl-8">
        <section class="card h-100" aria-labelledby="ticket-files-title">
            <div class="card-body">
                <div class="d-flex flex-wrap align-items-start justify-content-between gap-2 mb-3">
                    <div>
                        <h2 id="ticket-files-title" class="h5 mb-1">Photos du ticket</h2>
                        <p class="text-muted mb-0">{{ $ticket->article }} · Ticket {{ $ticket->code }}</p>
                    </div>
                    <span class="badge bg-primary-subtle text-primary rounded-pill">{{ $ticket->media_count }} fichier(s)</span>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="checkAll">
                                        <label class="form-check-label visually-hidden" for="checkAll">Sélectionner tous les fichiers</label>
                                    </div>
                                </th>
                                <th scope="col">Aperçu</th>
                                <th scope="col">Nom du fichier</th>
                                <th scope="col">Type</th>
                                <th scope="col">Collection</th>
                                <th scope="col">Taille</th>
                                <th scope="col" class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($ticketMedia as $image)
                                <tr>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" name="checkedFiles[]" type="checkbox" value="{{ $image->id }}" id="check-media-{{ $image->id }}">
                                            <label class="form-check-label visually-hidden" for="check-media-{{ $image->id }}">Sélectionner {{ $image->name }}</label>
                                        </div>
                                    </td>
                                    <td>
                                        <a target="_blank" rel="noopener noreferrer" href="{{ $image->getFullUrl() }}" aria-label="Ouvrir {{ $image->name }} dans un nouvel onglet">
                                            <img src="{{ $image->getFullUrl('normal') }}" alt="{{ $image->name }}" class="avatar-md rounded object-fit-cover">
                                        </a>
                                    </td>
                                    <td class="fw-medium text-break">{{ $image->name }}</td>
                                    <td><span class="text-muted">{{ $image->mime_type }}</span></td>
                                    <td><span class="badge bg-light text-body border">{{ $image->collection_name }}</span></td>
                                    <td class="text-nowrap">{{ $image->human_readable_size }}</td>
                                    <td class="text-end">
                                        <form method="post" action="{{ route('admin:tickets.media.delete', $ticket->uuid) }}" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="mediaId" value="{{ $image->id }}">
                                            <button type="submit" class="btn btn-sm btn-outline-danger" aria-label="Supprimer {{ $image->name }}">
                                                <i class="mdi mdi-trash-can-outline" aria-hidden="true"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-5 text-center">
                                        <i class="bx bx-image-alt d-block fs-2 text-muted mb-2" aria-hidden="true"></i>
                                        <span class="text-muted">Aucune photo n’est encore associée à ce ticket.</span>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    <a href="{{ $ticket->url }}" class="btn btn-outline-secondary">
                        <i class="mdi mdi-arrow-left me-1" aria-hidden="true"></i>
                        Retour au ticket
                    </a>
                </div>
            </div>
        </section>
    </div>

    <aside class="col-12 col-xl-4" aria-label="Ajouter des photos au ticket">
        <section class="card mb-3">
            <div class="card-body">
                <p class="text-muted text-uppercase small fw-semibold mb-1">Appareil</p>
                <h2 class="h5 mb-0">{{ $ticket->article }}</h2>
                <p class="text-muted mb-0">Ticket {{ $ticket->code }}</p>
            </div>
        </section>

        <section class="card">
            <div class="card-body">
                <h2 class="h5 mb-1">Ajouter des photos</h2>
                <p class="text-muted mb-3">Formats acceptés : PNG et JPEG, 2 Mo maximum par fichier.</p>

                <form id="ticketFormAttachements" action="{{ route('admin:tickets.attachements', $ticket->uuid) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="ticket-photos" class="form-label">Fichiers</label>
                        <input id="ticket-photos" class="form-control @error('photos') is-invalid @enderror @error('photos.*') is-invalid @enderror" name="photos[]" type="file" accept=".png,.jpg,.jpeg,image/png,image/jpeg" multiple>
                        @error('photos')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        @error('photos.*')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary d-inline-flex align-items-center gap-2">
                        <i class="bx bx-upload" aria-hidden="true"></i>
                        <span>Envoyer les photos</span>
                    </button>
                </form>
            </div>
        </section>
    </aside>
</div>
