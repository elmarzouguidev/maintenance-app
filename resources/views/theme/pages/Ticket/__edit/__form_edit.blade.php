<div class="row g-3 align-items-start">
    <div class="col-12">@include('theme.layouts._parts.__messages')</div>
    <div class="col-12 col-xl-8">
        <form  action="{{ $ticket->update_url}}" method="post" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <section class="card mb-4" aria-labelledby="ticket-edit-context-title">
                <div class="card-body">
                    <div class="mb-3">
                        <h2 id="ticket-edit-context-title" class="h5 mb-1">Informations du ticket</h2>
                        <p class="text-muted mb-0">Modifiez les données d’entrée et la description du ticket.</p>
                    </div>

                    <div class="row g-3">
                        <div class="col-lg-6">

                            @include('theme.pages.Ticket.__edit.__info')

                            <div>
                                <label class="form-label" for="ticket-code">Numéro du ticket</label>
                                <div class="input-group mb-4">
                                    <input id="ticket-code" type="text" class="form-control"
                                            value="{{ $ticket->code }}"
                                           aria-describedby="code" readonly>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="row">
                                    <div class="col-lg-6 mb-4">
                                        <label class="form-label" for="ticket-created-at">Date d’entrée <span class="text-danger" aria-hidden="true">*</span></label>
                                        <div class="input-group" id="datepicker1">
                                            <input id="ticket-created-at" type="text"
                                                   class="form-control"
                                                   name="created_at"
                                                   value="{{$ticket->created_at->format('d-m-Y')}}"
                                                   data-date-format="dd-mm-yyyy"
                                                   data-date-container='#datepicker1' data-provide="datepicker">

                                            <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                        </div>
                                    </div>

                                    <div class="col-lg-6 mb-4">
                                        <label class="form-label" for="ticket-exit-date">Date de sortie</label>
                                        <div class="input-group" id="datepicker2">
                                            <input id="ticket-exit-date" type="text" disabled
                                                   class="form-control"
                                                   value=""
                                                   data-date-format="mm-dd-yyyy" data-date-container='#datepicker2'
                                                   data-provide="datepicker" data-date-autoclose="true">
                                            <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="mb-4">
                                <p class="form-label">Photo de l’appareil</p>
                                <img src="{{ $ticket->getFirstMediaUrl('tickets-images', 'normal') }}" alt="{{ $ticket->article }}" class="img-fluid rounded w-100">
                            </div>
                        </div>
                    </div>

                </div>
            </section>
            <section class="card mb-4" aria-labelledby="ticket-edit-description-title">
                <div class="card-body">
                    <h2 id="ticket-edit-description-title" class="h5 mb-3">Description du problème</h2>
                    <textarea class="form-control @error('description') is-invalid @enderror" name="description" id="ticketdesc-editor" rows="7">{{ $ticket->description }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </section>

            <div class="d-flex flex-wrap gap-2 justify-content-end mb-4">
                <div class="d-flex flex-wrap gap-2">
                    <button type="submit" class="btn btn-primary waves-effect waves-light">
                        Update
                    </button>
                    <button type="submit" class="btn btn-secondary waves-effect waves-light">
                        Sauvegarder en tant que brouillon
                    </button>
                </div>
            </div>

        </form>
    </div>

    <aside class="col-12 col-xl-4">
        @include('theme.pages.Ticket.__edit.__ticket_actions')
    </aside>

</div>
