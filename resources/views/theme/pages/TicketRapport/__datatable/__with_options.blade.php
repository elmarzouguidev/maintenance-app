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
                            <th scope="col">Date de génération</th>
                            <th scope="col">FIchier PDF</th>
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
                                    <a href="{{ route('admin:rapports.report.generate', [$rapport->ticket?->uuid, 'has_header' => true]) }}"
                                        target="_blank" class="btn btn-warning btn-sm">
                                        PDF
                                    </a>
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
