<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                @include('theme.layouts._parts.__messages')

                <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th scope="col">Ticket</th>
                            <th scope="col">Type</th>
                            <th scope="col">Technicien</th>
                            <th scope="col">Date de creation</th>
                            <th scope="col">Date de modification</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach ($reportes as $rapport)
                            <tr>
                                <td>
                                    <a href="{{ $rapport->ticket?->url }}" class="text-body fw-bold"
                                        style="color:#556ee6 !important">

                                        {{ $rapport->ticket?->code }}

                                    </a>
                                </td>
                                <td style="white-space:normal;">

                                    {{ $rapport->type }}

                                </td>
                                <td>
                                    {{ $rapport->technicien?->full_name }}
                                </td>
                                <td>
                                    {{ $rapport->created_at?->format('d-m-Y') }}
                                </td>
                                <td>
                                    {{ $rapport->updated_at?->format('d-m-Y') }}
                                </td>
                                <td>
                                    <div class="d-flex gap-3">

                                        <a href="{{ route('admin:rapports.editions.edit', $rapport->uuid) }}"
                                            class="text-success">
                                            <i class="mdi mdi-pencil font-size-18"></i>
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
