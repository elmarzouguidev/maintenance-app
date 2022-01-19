<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table align-middle table-nowrap table-hover">
                        <thead class="table-light">
                            <tr>
                                <th scope="col" style="width: 70px;">#</th>
                                <th scope="col">Name</th>
                                <th scope="col">Email</th>
                                <th scope="col">Phone</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($techniciens as $technicien)
                            
                                <tr>
                                    <td>
                                        <div class="avatar-xs">
                                            <span class="avatar-title rounded-circle">
                                                {{$technicien->full_name[0]}}
                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        <h5 class="font-size-14 mb-1"><a href="javascript: void(0);" class="text-dark">{{$technicien->full_name}}</a></h5>
                                        {{--<p class="text-muted mb-0">UI/UX Designer</p>--}}
                                    </td>
                                    <td>{{$technicien->email}}</td>

                                    <td>
                                        {{$technicien->telephone}}
                                    </td>
                                    <td>
                                        <div class="d-flex gap-3">
                                            <a href="{{route('admin:techniciens.edit',$technicien->id)}}" class="text-success"><i class="mdi mdi-pencil font-size-18"></i></a>
                                            <a 
                                                href="#" 
                                                class="text-danger"
                                                onclick="document.getElementById('delete-techniciens-{{$technicien->id}}').submit();"
                                            >
                                                <i class="mdi mdi-delete font-size-18"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <form id="delete-techniciens-{{$technicien->id}}" method="post" action="{{route('admin:techniciens.delete')}}">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="techId" value="{{$technicien->id}}">
                                </form>
                            @endforeach

                        </tbody>
                    </table>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <ul class="pagination pagination-rounded justify-content-center mt-4">
                            <li class="page-item disabled">
                                <a href="javascript: void(0);" class="page-link"><i class="mdi mdi-chevron-left"></i></a>
                            </li>
                            <li class="page-item">
                                <a href="javascript: void(0);" class="page-link">1</a>
                            </li>
                            <li class="page-item active">
                                <a href="javascript: void(0);" class="page-link">2</a>
                            </li>
                            <li class="page-item">
                                <a href="javascript: void(0);" class="page-link">3</a>
                            </li>
                            <li class="page-item">
                                <a href="javascript: void(0);" class="page-link">4</a>
                            </li>
                            <li class="page-item">
                                <a href="javascript: void(0);" class="page-link">5</a>
                            </li>
                            <li class="page-item">
                                <a href="javascript: void(0);" class="page-link"><i class="mdi mdi-chevron-right"></i></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>