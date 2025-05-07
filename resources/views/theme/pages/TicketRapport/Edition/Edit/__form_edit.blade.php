<div class="row">
    @include('theme.layouts._parts.__messages')
    <div class="col-lg-12">
        <form action="{{ route('admin:rapports.editions.update', $report->uuid) }}" method="post"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="card mb-4">
                <div class="card-body">

                    <p class="card-title-desc">Modifier les information de Rapport</p>

                    <div class="row">
                        <div class="col-lg-6">

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="mb-4">
                                        <label class="form-label">Ticket *</label>
                                        <input id="article" name="article" type="text"
                                               class="form-control @error('article') is-invalid @enderror"
                                               value="{{$report->ticket?->article}}">
                                        @error('article')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                            
                                    </div>
                                </div>
                            
                            </div>

                            <div class="docs-options">
                                <label class="form-label">Numéro de Ticket</label>
                                <div class="input-group mb-4">
                                    <input type="text" class="form-control" value="{{ $report->ticket?->code }}"
                                        aria-describedby="code" readonly>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="row">
                                    <div class="col-lg-6 mb-4">
                                        <label>* Date de creation</label>
                                        <div class="input-group" id="datepicker1">
                                            <input type="text" class="form-control"
                                                value="{{ $report->created_at?->format('d-m-Y') }}"
                                                data-date-format="dd-mm-yyyy" data-date-container='#datepicker1'
                                                data-provide="datepicker" disabled>

                                            <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                        </div>
                                    </div>

                                    <div class="col-lg-6 mb-4">
                                        <label> Date de modification</label>
                                        <div class="input-group" id="datepicker2">
                                            <input type="text" disabled class="form-control"
                                                value="{{ $report->updated_at?->format('d-m-Y') }}"
                                                data-date-format="mm-dd-yyyy" data-date-container='#datepicker2'
                                                data-provide="datepicker" data-date-autoclose="true">
                                            <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="card mb-4">
                <div class="card-body">
                    <p class="card-title-desc"> Détails de rapport</p>
                    <div class="row">
                        <div class="col-lg-4 mb-4">

                        </div>
                    </div>
                    <div class="row" id="articles_list">

                        <div class="col-lg-12">
                            <div class="justify-content-end">
                                <div class="card border border-primary">
                                    <div class="card-header bg-transparent border-primary">

                                        <textarea class="form-control @error('report') is-invalid @enderror" name="report" id="ticketdesc-editor"
                                            rows="6">
                                                {{ $report->content }}
                                            </textarea>

                                        @error('report')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="d-flex flex-wrap gap-2 justify-content-end mb-4">
                <div class="">
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
</div>
