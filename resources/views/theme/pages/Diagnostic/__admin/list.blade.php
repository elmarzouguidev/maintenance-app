<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-sm-4">
                        <div class="search-box me-2 mb-2 d-inline-block">
                            <div class="position-relative">
                                <input type="text" class="form-control" placeholder="Search...">
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
                                <th class="align-middle"> Etat</th>
                                <th class="align-middle"> Technicien</th>
                                <th class="align-middle">Traiter le ticket</th>

                                @auth('admin')
                                <th class="align-middle">Action</th>
                                @endauth
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reports as $report)
                                <tr>
                                    <td>
                                        <div class="form-check font-size-16">
                                            <input class="form-check-input" type="checkbox" id="orderidcheck01">
                                            <label class="form-check-label" for="orderidcheck01"></label>
                                        </div>
                                    </td>
                                    {{--<td>{{$report->id}} </td>--}}
                                    <td> <a href="{{$report->ticket_url}}" class="text-body fw-bold">{{$report->ticket}}</a></td>
                                    <td>
                                        {{$report->ouvert_at}}
                                    </td>
                                    <td>
                                        {{$report->envoyer_at}}
                                    </td>
            
                                    <td>
                                        <span class="badge badge-pill badge-soft-success font-size-12">{{$report->etat}}</span>
                                    </td>
                                    <td>
                                        <i class="fas fas fa-building me-1"></i> {{$report->technicien->full_name ?? ''}}
                                    </td>
                                    <td>
                                        <!-- Button trigger modal -->
                                        <a href="{{$report->ticket_url}}" type="button" class="btn btn-primary btn-sm btn-rounded">
                                            Traiter le ticket
                                        </a>
                                    </td>
                                    @auth('admin')
                                    <td>
                                        <div class="d-flex gap-3">
                                            <a href="{{$report->edit}}" class="text-success"><i class="mdi mdi-pencil font-size-18"></i></a>
                                            <a 
                                                href="#" 
                                                class="text-danger"
                                                onclick="document.getElementById('delete-ticket-{{$report->id}}').submit();"
                                            >
                                                <i class="mdi mdi-delete font-size-18"></i>
                                            </a>
                                        </div>
                                    </td>
                                    @endauth
                                </tr>
                                <form id="delete-ticket-{{$report->id}}" method="post" action="{{route('admin:tickets.delete')}}">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="ticket" value="{{$report->id}}">
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