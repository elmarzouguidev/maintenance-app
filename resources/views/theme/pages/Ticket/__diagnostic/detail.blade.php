<div class="row">
    <div class="col-xl-4">
        <div class="card overflow-hidden">
            <div class="bg-primary bg-soft">
                <div class="row">
                    <div class="col-7">
                        <div class="text-primary p-3">
                            <h5 class="text-primary">{{ $tickett->article }}</h5>

                        </div>
                    </div>
                    <div class="col-5 align-self-end">
                        <img src="{{ asset('assets/images/profile-img.png') }}" alt="" class="img-fluid">
                    </div>
                </div>
            </div>
            <div class="card-body pt-0">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="avatar-md profile-user-wid mb-4">
                            <img src="{{ asset('assets/images/profile-img.png') }}" alt=""
                                class="img-thumbnail rounded-circle">
                        </div>
                        <h5 class="font-size-15 text-truncate">
                            {{ $tickett->technicien->full_name }}
                        </h5>
                        <p class="text-muted mb-0 text-truncate">Technicien</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- end card -->

        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-5">Détails</h4>
                <table class="table mb-0 table-bordered">
                    <tbody>
                        <tr>
                            <th scope="row" style="width: 400px;">Technicien</th>
                            <td>{{ $tickett->technicien->full_name ?? 'No' }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Agent de reception</th>
                            <td>{{ $tickett->reception->full_name ?? 'No' }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Client</th>
                            <td>{{ $tickett->client->entreprise ?? '' }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Etat</th>
                            <td>{{ $tickett->etat }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Status</th>
                            <td>{{ $tickett->stat }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Code</th>
                            <td>{{ $tickett->unique_code }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Date de création</th>
                            <td>{{ $tickett->full_date }}</td>
                        </tr>

                    </tbody>
                </table>

            </div>
        </div>
        <!-- end card -->
    </div>

    <div class="col-xl-8">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">{{ $tickett->article }} #{{ $tickett->unique_code }}</h4>
                <div class="row">
                    <div class="col-xl-6">
                        <p class="card-title-desc">{!! $tickett->description !!}</p>
                    </div>
                    <div class="col-xl-6">
                        <div class="card">
                            <div class="card-body">

                                <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
                                    <div class="carousel-inner" role="listbox">
                                        @foreach ($tickett->getMedia('tickets-images') as $image)
                                            <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                                                <img class="d-block img-fluid" src="{{ $image->getUrl() }}"
                                                    alt="Product image">
                                            </div>
                                        @endforeach

                                    </div>
                                    <a class="carousel-control-prev" href="#carouselExampleControls" role="button"
                                        data-bs-slide="prev">
                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                        <span class="sr-only">Previous</span>
                                    </a>
                                    <a class="carousel-control-next" href="#carouselExampleControls" role="button"
                                        data-bs-slide="next">
                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                        <span class="sr-only">Next</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-12">


                    @auth('admin')
                        @if ($tickett->estimate_count === 1)
                            <a href="{{ route('commercial:estimates.single', $tickett->estimate->uuid) }}"
                                class="btn btn-warning mr-auto" type="submit">
                                DEVIS deja Créer
                            </a>
                        @else
                            <a href="{{ route('commercial:estimates.create.ticket', ['ticket' => $tickett->uuid]) }}"
                                class="btn btn-primary mr-auto" type="submit">
                                Crée un DEVIS
                            </a>
                        @endif

                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <form method="post"
                            action="{{ route('admin:tickets.diagnose.send-confirm', ['slug' => $tickett->uuid]) }}">
                            @csrf
                            <div class="mt-4 mb-5">
                                <h5 class="font-size-14 mb-4">Réponse de devis</h5>
                                <div class="form-check form-check-inline mb-3">
                                    <input class="form-check-input" type="radio" name="response" id="response1"
                                        value="devis-confirme" {{ $tickett->status === 'a-preparer' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="response1">
                                        Devis accépté, commencez la réparation
                                    </label>
                                </div>
                                <input type="hidden" name="report" value="{{ optional($tickett->diagnoseReports)->id }}">
                                <input type="hidden" name="ticketId" value="{{ $tickett->uuid }}">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="response" id="response2"
                                        value="retour-devis-non-confirme"
                                        {{ $tickett->status === 'retour-devis-non-confirme' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="response2">
                                        Devis refusé, déclinez la réparation
                                    </label>
                                </div>
                            </div>

                            <button class="mb-4 btn btn-primary mr-auto" type="submit">Enregistre l'etat</button>

                            <div class="row mb-4">
                                <textarea readonly class="form-control" id="ticketdesc-editor" rows="3"
                                    readonly>{{ optional($tickett->diagnoseReports)->content }}</textarea>
                            </div>

                        </form>
                    @endauth
                    @auth('technicien')
                        <form action="{{ $tickett->diagnose_url }}" method="post">

                            @csrf
                            @honeypot

                            <div class="mt-4 mb-5">
                                <h5 class="font-size-14 mb-4">Etat</h5>
                                <div class="form-check form-check-inline mb-3">
                                    <input class="form-check-input" type="radio" name="etat" id="etat1" value="reparable"
                                        {{ $tickett->etat === 'reparable' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="etat1">
                                        Réparable
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="etat" id="etat2"
                                        value="non-reparable" {{ $tickett->etat === 'non-reparable' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="etat2">
                                        Non Réparable
                                    </label>
                                </div>
                            </div>
                            <input type="hidden" name="ticket" value="{{ $tickett->uuid }}">
                            <input type="hidden" name="type" value="diagnostique">
                            <input id="send-report" type="hidden" name="sendreport" value="no">
                            <div class="row mb-4">

                                <textarea class="form-control @error('content') is-invalid @enderror" name="content"
                                    id="ticketdesc-editor" rows="3" placeholder="Enter Rapport Description...">
                                                                        {{ optional($tickett->diagnoseReports)->content ?? old('content') }}
                                                                    </textarea>
                                @error('content')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                            </div>

                            <button class="btn btn-primary mr-auto" type="submit">Enregistre le rapport</button>

                            <button class="btn btn-danger mr-auto" type="submit"
                                onclick="document.getElementById('send-report').value='yessendit';">
                                Enregistre et envoyer
                            </button>

                        </form>
                    @endauth
                </div>

            </div>
        </div>
    </div>
</div>
