<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-xl-6">
                        <div class="product-detai-imgs">
                            <div class="row">
                                <div class="col-md-2 col-sm-3 col-4">
                                    <div class="nav flex-column nav-pills " id="v-pills-tab" role="tablist"
                                        aria-orientation="vertical">

                                        @foreach ($ticket->getMedia('tickets-images') as $image)
                                            <a class="nav-link {{ $loop->first ? 'active' : '' }}"
                                                id="product-{{ $loop->index + 1 }}-tab" data-bs-toggle="pill"
                                                href="#product-{{ $loop->index + 1 }}" role="tab"
                                                aria-controls="product-{{ $loop->index + 1 }}"
                                                aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                                                <img src="{{ $image->getFullUrl('normal') }}" alt=""
                                                    class="img-fluid mx-auto d-block rounded">
                                            </a>
                                        @endforeach

                                    </div>
                                </div>
                                <div class="col-md-7 offset-md-1 col-sm-9 col-8">
                                    <div class="tab-content" id="v-pills-tabContent">
                                        @foreach ($ticket->getMedia('tickets-images') as $image)
                                            <div class="tab-pane {{ $loop->first ? 'fade show active' : '' }}"
                                                id="product-{{ $loop->index + 1 }}" role="tabpanel"
                                                aria-labelledby="product-{{ $loop->index + 1 }}-tab">
                                                <div>
                                                    <img src="{{ $image->getFullUrl('normal') }}" alt=""
                                                        class="img-fluid mx-auto d-block">
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-6">
                        <div class="mt-4 mt-xl-3">
                            {{-- <a href="javascript: void(0);" class="text-primary">Headphone</a> --}}
                            <h4 class="mt-1 mb-3">{{ $ticket->article }}</h4>

                            <p class="text-muted float-start me-3">
                                <span class="bx bxs-star text-warning"></span>
                                <span class="bx bxs-star text-warning"></span>
                                <span class="bx bxs-star text-warning"></span>
                                <span class="bx bxs-star text-warning"></span>
                                <span class="bx bxs-star"></span>
                            </p>
                            <p class="text-muted mb-4">( 152 Customers Review )</p>
                            <p class="text-muted mb-4"> {!! $ticket->description !!}</p>
                            <div class="row mb-3">
                                {{-- <div class="col-md-6">
                                    <div>
                                        <p class="text-muted"><i
                                                class="bx bx-unlink font-size-16 align-middle text-primary me-1"></i>
                                            Wireless</p>
                                        <p class="text-muted"><i
                                                class="bx bx-shape-triangle font-size-16 align-middle text-primary me-1"></i>
                                            Wireless Range : 10m</p>
                                        <p class="text-muted"><i
                                                class="bx bx-battery font-size-16 align-middle text-primary me-1"></i>
                                            Battery life : 6hrs</p>
                                    </div>
                                </div> --}}
                                <div class="col-md-6">
                                    <div>
                                        {{--<p class="text-muted"><i
                                                class="bx bx-user-voice font-size-16 align-middle text-primary me-1"></i>
                                            Bass</p>--}}
                                        <p class="text-muted"><i
                                                class="bx bx-cog font-size-16 align-middle text-primary me-1"></i>
                                            Garantie : 3 mois</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end row -->

                <div class="mt-5">
                    <div class="text-left mb-5">
                        <a href="{{ $ticket->edit }}" type="button" class="btn btn-primary">
                            Editer L'article
                        </a>

                        <a href="{{ $ticket->media_url }}" type="button" class="btn btn-success">
                            <i class="bx bx-image-alt font-size-16 align-middle me-2"></i>
                            Ajouter des photos
                        </a>
                    </div>
                    <h5 class="mb-3">Informations :</h5>
                    <div class="row">

                        <div class="col-lg-6">
                            <div class="table-responsive">
                                <table class="table mb-0 table-bordered">
                                    <tbody>
                                        <tr>
                                            <th scope="row" style="width: 400px;">Technicien</th>
                                            <td>{{ $ticket->technicien->full_name ?? 'No' }}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Agent de reception</th>
                                            <td>{{ $ticket->reception->full_name ?? 'No' }}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Client</th>
                                            <td>{{ $ticket->client->entreprise ?? '' }}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Etat</th>
                                            <td>{{ $ticket->etat }}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Status</th>
                                            <td>{{ $ticket->stat }}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Code</th>
                                            <td>{{ $ticket->unique_code }}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Date de création</th>
                                            <td>{{ $ticket->full_date }}</td>
                                        </tr>

                                        <tr>
                                            <th scope="row">fichiers attachés</th>
                                            <td>{{ $ticket->full_date }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="table-responsive">
                                <table class="table mb-0 table-bordered">
                                    <tbody>
                                        <tr>
                                            <th scope="row">fichiers attachés</th>
                                            <th scope="row">Nom</th>
                                            <th scope="row">Télécharger</th>
                                        </tr>
                                        <tr>
                                            <td style="width: 45px;">
                                                <div class="avatar-sm">
                                                    <span
                                                        class="avatar-title rounded-circle bg-primary bg-soft text-primary font-size-24">
                                                        <i class="bx bxs-file-doc"></i>
                                                    </span>
                                                </div>
                                            </td>
                                            <td>
                                                <h5 class="font-size-14 mb-1"><a href="javascript: void(0);"
                                                        class="text-dark">Facture-00125.Zip</a></h5>
                                                <small>Size : 3.25 MB</small>
                                            </td>
                                            <td>
                                                <div class="text-center">
                                                    <a href="javascript: void(0);" class="text-dark"><i
                                                            class="bx bx-download h3 m-0"></i></a>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
                <!-- end Specifications -->

                <div class="mt-5">
                    <h5>Historique :</h5>
                    <div class="d-flex py-3 border-bottom">
                        <div class="flex-shrink-0 me-3">
                            <div class="avatar-xs">
                                <span class="avatar-title bg-primary bg-soft text-primary rounded-circle font-size-16">
                                    N
                                </span>
                            </div>
                        </div>

                        <div class="flex-grow-1">
                            <h5 class="mb-1 font-size-15">Neal</h5>
                            <p class="text-muted">Everyone realizes why a new common language would be desirable.
                            </p>
                            <ul class="list-inline float-sm-end mb-sm-0">
                                <li class="list-inline-item">
                                    <a href="javascript: void(0);"><i class="far fa-thumbs-up me-1"></i> Like</a>
                                </li>
                                <li class="list-inline-item">
                                    <a href="javascript: void(0);"><i class="far fa-comment-dots me-1"></i> Comment</a>
                                </li>
                            </ul>
                            <div class="text-muted font-size-12"><i class="far fa-calendar-alt text-primary me-1"></i>
                                05 Oct, 2019</div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <!-- end card -->
    </div>
</div>
