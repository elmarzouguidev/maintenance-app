<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-sm-4">
                        <div class="search-box me-2 mb-2 d-inline-block">
                            <div class="position-relative">
                                <input type="text" class="form-control" placeholder="Cherchez...">
                                <i class="bx bx-search-alt search-icon"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table align-middle table-nowrap table-check">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 20px;" class="align-middle">
                                    <div class="form-check font-size-16">
                                        <input class="form-check-input" type="checkbox" id="checkAll">
                                        <label class="form-check-label" for="checkAll"></label>
                                    </div>
                                </th>
                                {{--<th class="align-middle">Report ID</th>--}}
                                <th class="align-middle">Produit</th>
                                <th class="align-middle">Date d'ouverture</th>
                                <th class="align-middle">Date d'nvoyer</th>
                                <th class="align-middle">Etat</th>
                                <th class="align-middle">Status</th>
                                <th class="align-middle">Technicien</th>
                                <th class="align-middle">Traiter le ticket</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tickets as $ticket)
                                <tr>
                                    <td>
                                        <div class="form-check font-size-16">
                                            <input class="form-check-input" type="checkbox" id="orderidcheck01">
                                            <label class="form-check-label" for="orderidcheck01"></label>
                                        </div>
                                    </td>
                                    {{--<td>{{$report->id}} </td>--}}
                                    <td> <a href="{{$ticket->url}}" class="text-body fw-bold">{{$ticket->product}}</a></td>
                                    <td>
                                        {{$ticket->created_at}}
                                    </td>
                                    <td>
                                        {{$ticket->updated_at}}
                                    </td>
            
                                    <td>
                                        <span class="badge badge-pill badge-soft-success font-size-12">{{$ticket->etat}}</span>
                                    </td>
                                    <td>
                                        <span class="badge badge-pill badge-soft-success font-size-12">{{$ticket->status}}</span>
                                    </td>
                                    <td>
                                        <i class="fas fas fa-building me-1"></i> {{$ticket->technicien->full_name ?? ''}}
                                    </td>
                                    @if($ticket->status !== 'encours-reparation')
                                        <td>
                                            <!-- Button trigger modal -->
                                            <a href="{{$ticket->ticket_url}}" type="button" class="btn btn-primary btn-sm btn-rounded">
                                                Traiter le ticket
                                            </a>
                                        </td>
                                    @else
                                    <td>
                                      
                                    </td>
                                    @endif

                                </tr>
                                <form id="delete-ticket-{{$ticket->id}}" method="post" action="{{route('admin:tickets.delete')}}">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="ticket" value="{{$ticket->id}}">
                                </form>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <ul class="pagination pagination-rounded justify-content-end mb-2">
                    <li class="page-item disabled">
                        <a class="page-link" href="javascript: void(0);" aria-label="Previous">
                            <i class="mdi mdi-chevron-left"></i>
                        </a>
                    </li>
                    <li class="page-item active"><a class="page-link" href="javascript: void(0);">1</a></li>
                    <li class="page-item"><a class="page-link" href="javascript: void(0);">2</a></li>
                    <li class="page-item"><a class="page-link" href="javascript: void(0);">3</a></li>
                    <li class="page-item"><a class="page-link" href="javascript: void(0);">4</a></li>
                    <li class="page-item"><a class="page-link" href="javascript: void(0);">5</a></li>
                    <li class="page-item">
                        <a class="page-link" href="javascript: void(0);" aria-label="Next">
                            <i class="mdi mdi-chevron-right"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- end row -->