<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                @include('theme.layouts._parts.__messages')

                <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th scope="col">Ticket</th>
                            <th scope="col">Technicien</th>
                            <th scope="col">Date de creation</th>
                            <th scope="col">Date de modification</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach ($tickets as $ticket)
                            <tr>
                                <td>
                                    <a href="{{ $ticket?->url }}" class="text-body fw-bold"
                                        style="color:#556ee6 !important">

                                        {{ $ticket?->code }}

                                    </a>
                                </td>
                                <td>
                                    {{ $ticket->technicien?->full_name }}
                                </td>
                                <td>
                                    {{ $ticket->created_at?->format('d-m-Y') }}
                                </td>
                                <td>
                                    {{ $ticket->updated_at?->format('d-m-Y') }}
                                </td>
                                <td>
                                    <div class="d-flex gap-3">

                                        <a href="{{ route('admin:rapports.editions.edit', $ticket->uuid) }}"
                                            class="btn btn-info btn-sm">
                                            EDIT
                                        </a>
                                        {{-- <a href="#" class="text-danger" onclick="
                                        var result = confirm('Are you sure you want to delete this rapport ?');

                                        if(result){
                                        event.preventDefault();
                                        document.getElementById('delete-rapport-{{ $rapport->uuid }}').submit();
                                        }">
                                        <i class="mdi mdi-delete font-size-18"></i>
                                    </a> --}}
                                    </div>
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
