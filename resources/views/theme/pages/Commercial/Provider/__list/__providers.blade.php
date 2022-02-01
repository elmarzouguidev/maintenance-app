<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table align-middle table-nowrap table-hover">
                        <thead class="table-light">
                            <tr>
                                <th scope="col" style="width: 70px;">#</th>
                                <th scope="col">Code Fournisseur</th>
                                <th scope="col">Entreprise</th>
                                <th scope="col">Telephone</th>
                                <th scope="col">Email</th>
                                <th scope="col">Addresse</th>
                                <th scope="col">ICE</th>
                                <th scope="col">RC</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($providers as $provider)

                                <tr>
                                    <td>
                                        <div class="avatar-xs">
                                            <span class="avatar-title rounded-circle">
                                                D
                                            </span>
                                        </div>
                                        {{-- <div>
                                            <img class="rounded-circle avatar-xs" src="{{asset('assets/images/users/avatar-2.jpg')}}" alt="">
                                        </div> --}}
                                    </td>
                                    <td>{{ $provider->provider_ref }}</td>
                                    <td>
                                        <h5 class="font-size-14 mb-1"><a href="{{ $provider->url }}"
                                                class="text-dark">{{ $provider->entreprise }}</a></h5>
                                        <p class="text-muted mb-0">{{ $provider->contact }}</p>
                                    </td>
                                    <td>{{ $provider->telephone }}</td>
                                    <td>{{ $provider->email }}</td>
                                    <td>{{ $provider->addresse }}</td>
                                    <td>{{ $provider->ice }}</td>
                                    <td>{{ $provider->rc }}</td>
                                    <td>
                                        <div class="d-flex gap-3">
                                            <a href="{{ $provider->edit }}" class="text-success"><i
                                                    class="mdi mdi-pencil font-size-18"></i></a>
                                            <a href="#" class="text-danger"
                                                onclick="document.getElementById('delete-provider-{{ $provider->uuid }}').submit();">
                                                <i class="mdi mdi-delete font-size-18"></i>
                                            </a>
                                        </div>

                                        <form id="delete-provider-{{ $provider->uuid }}" action="{{ route('commercial:providers.delete') }}" method="post">
                                            @csrf
                                            @honeypot
                                            @method('DELETE')
                                            <input type="hidden" name="providerId" value="{{ $provider->uuid }}">

                                        </form>
                                    </td>
                                </tr>

                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <ul class="pagination pagination-rounded justify-content-center mt-4">
                            <li class="page-item disabled">
                                <a href="javascript: void(0);" class="page-link"><i
                                        class="mdi mdi-chevron-left"></i></a>
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
                                <a href="javascript: void(0);" class="page-link"><i
                                        class="mdi mdi-chevron-right"></i></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
