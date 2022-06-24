<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-8">

                        <div class="col-lg-12 mb-4">
                            <a href="{{ route('admin:clients.create') }}" type="button" class="btn btn-info">
                                {{ __('navbar.clients_add') }}
                            </a>

                        </div>
                    </div>
                </div>

                @include('theme.layouts._parts.__messages')

                <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            {{-- <th style="width: 20px;" class="align-middle">
                            <div class="form-check font-size-16">
                                <input class="form-check-input" type="checkbox" id="checkAll">
                                <label class="form-check-label" for="checkAll"></label>
                            </div>
                        </th> --}}
                            <th scope="col">Code Client</th>
                            <th scope="col">Entreprise</th>
                            <th scope="col">Tickets</th>
                            @if (auth()->user()->hasAnyRole('Admin', 'SuperAdmin'))
                                <th scope="col">Action</th>
                            @endif
                        </tr>
                    </thead>

                    <tbody>

                        @foreach ($clients as $client)
                            <tr>
                                {{-- <td>
                                <div class="form-check font-size-16">
                                    <input class="form-check-input" type="checkbox"
                                        id="client-{{ $client->id }}">
                                    <label class="form-check-label" for="client-{{ $client->id }}"></label>
                                </div>
                            </td> --}}
                                <td>
                                    <a href="{{ $client->url }}" class="text-body fw-bold">
                                        {{ $client->code }}
                                    </a>
                                </td>
                                <td>
                                    {{ $client->entreprise }}
                                    <p class="text-muted mb-0">{{ $client->contact }}</p>
                                </td>

                                <td>
                                    {{ $client->tickets_count }}
                                </td>
                                @if (auth()->user()->hasAnyRole('Admin', 'SuperAdmin'))
                                    <td>
                                        <div class="d-flex gap-3">

                                            <a href="{{ $client->edit }}" class="text-success">
                                                <i class="mdi mdi-pencil font-size-18"></i>
                                            </a>

                                        </div>
                                    </td>
                                @endif
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
