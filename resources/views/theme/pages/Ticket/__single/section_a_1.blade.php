<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1 overflow-hidden">
                        <h5 class="text-truncate font-size-15">{{ $ticket->article }}</h5>

                    </div>
                </div>

                @include('theme.pages.Ticket.__single.__images')

                <h5 class="font-size-15 mt-4">Article Détails :</h5>

                <div class="mt-4">
                    {!! $ticket->description !!}
                </div>

                <div class="row task-dates">
                    <div class="col-sm-4 col-6">
                        <div class="mt-4">
                            <h5 class="font-size-14"><i class="bx bx-calendar me-1 text-primary"></i>Date de Création
                            </h5>
                            <p class="text-muted mb-0">{{ $ticket->full_date }}</p>
                        </div>
                    </div>

                    {{-- <div class="col-sm-4 col-6">
                        <div class="mt-4">
                            <h5 class="font-size-14"><i class="bx bx-calendar-check me-1 text-primary"></i> Date de sortie</h5>
                            <p class="text-muted mb-0">{{$ticket->full_date}}</p>
                        </div>
                    </div> --}}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ $ticket->edit }}" type="button"
                            class="btn btn-primary">
                            Edit
                        </a>

                        <a href="{{ $ticket->media_url }}" type="button"
                            class="btn btn-info">
                            <i class="bx bx-image-alt font-size-16 align-middle me-2"></i>
                            ajouter des photos
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">

                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Attached Files</h4>
                        <div class="table-responsive">
                            <table class="table table-nowrap align-middle table-hover mb-0">
                                <tbody>

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
                                                    class="text-dark">Skote Landing.Zip</a></h5>
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

            <div class="col-md-6">

            </div>
        
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Historique</h4>

                <ul class="verti-timeline list-unstyled">
                    @foreach ($ticket->statuses as $status)
                        <li class="event-list">
                            <div class="event-timeline-dot">
                                <i class="bx bx-right-arrow-circle"></i>
                            </div>
                            <div class="event-date">
                                <div class="text-primary mb-1">{{ $status->created_at->format('d-m-Y') }}</div>
                            </div>
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <i class="bx bx-copy-alt h2 text-primary"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div>
                                        <h5>{{ $status->name }}</h5>
                                        <p class="text-muted">
                                            {{ $status->reason }}
                                        </p>

                                    </div>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>

                <div class="text-center mt-4 pt-2">
                    <a href="javascript: void(0);" class="btn btn-primary btn-sm">View more</a>
                </div>
            </div>
        </div>
    </div>

</div>
