<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

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
 
                            <th scope="col">Ticket</th>
                            <th scope="col">Type</th>
                            <th scope="col">Technicien</th>
                            <th scope="col">FIchier PDF</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach ($reportes as $rapport)
                            <tr>
                                {{-- <td>
                                <div class="form-check font-size-16">
                                    <input class="form-check-input" type="checkbox"
                                        id="rapport-{{ $rapport->id }}">
                                    <label class="form-check-label" for="rapport-{{ $rapport->id }}"></label>
                                </div>
                            </td> --}}
    
                                <td style="white-space:normal;">

                                    {{ $rapport->ticket?->code }}

                                </td>
                                <td style="white-space:normal;">

                                    {{ $rapport->type }}

                                </td>
                                <td>
                                    {{ $rapport->technicien?->full_name }}
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
