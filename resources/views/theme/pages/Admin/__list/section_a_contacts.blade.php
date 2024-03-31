<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table align-middle table-nowrap table-hover">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">Nom Complet</th>
                                <th scope="col">Status</th>
                                <th scope="col">Email</th>
                                <th scope="col">Date d'ajout</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($admins as $admin)
                                <tr>
                                    <td>
                                        <h5 class="font-size-14 mb-1"><a href="javascript: void(0);"
                                                class="text-dark">{{ $admin->full_name }}</a></h5>
                                        <p class="text-muted mb-0">

                                            @foreach ($admin->getRoleNames() as $roleName)
                                                <span class="badge rounded-pill bg-primary"> {{ $roleName }} </span>
                                                {{ $loop->last ? '' : ' ,' }}
                                            @endforeach
                                        </p>
                                    </td>
                                    <td>
                                        {{ $admin->active ? 'Activé' : 'Désactivé (Ce compte ne peut être connecter)' }}
                                    </td>
                                    <td>
                                        @php
                                            $email = $admin->email;
                                            if ($email == 'abdelgha4or@gmail.com') {
                                                $email = \Str::mask($email, '*', 2, 8);
                                            }
                                        @endphp
                                        {{ $email }}
                                    </td>

                                    <td>
                                        {{ $admin->created_at?->format('d-m-Y') }}
                                    </td>
                                    <td>
                                        <div class="d-flex gap-3">
                                            @if ($admin->email !== 'abdelgha4or@gmail.com')
                                                <a href="{{ route('admin:admins.edit', $admin->uuid) }}"
                                                    class="text-success"><i class="mdi mdi-pencil font-size-18"></i></a>

                                                <a href="#" class="text-danger"
                                                    onclick="document.getElementById('delete-admin-{{ $admin->uuid }}').submit();">
                                                    <i class="mdi mdi-delete font-size-18"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @if ($admin->email !== 'abdelgha4or@gmail.com')
                                    <form id="delete-admin-{{ $admin->uuid }}" method="post"
                                        action="{{ route('admin:admins.delete') }}">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="adminId" value="{{ $admin->uuid }}">
                                    </form>
                                @endif
                            @endforeach

                        </tbody>
                    </table>
                </div>
                {{-- <div class="row">
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
                </div> --}}
            </div>
        </div>
    </div>
</div>
