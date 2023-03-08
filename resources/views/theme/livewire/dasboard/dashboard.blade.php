<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-8">
                        <h4 class="card-title mb-4">Chiffre d'affaire par client (automatiquement trier par le trimestre actuel d'année)
                            <span class="mt-2 text-danger"> {{ $startDate }} / {{ $endDate }}
                            </span>
                        </h4>
                    </div>
                    <div class="col-4 mb-3">
                        <div class="input-daterange input-group" id="datepicker67" data-date-format="yyyy-mm-dd"
                            data-date-autoclose="true" data-provide="datepicker" data-date-container='#datepicker67'>
                            <input type="text" class="form-control" name="start" wire:model="startDate"
                                placeholder="date de début" onchange="this.dispatchEvent(new InputEvent('input'))" />
                            <input type="text" class="form-control" name="end" wire:model="endDate"
                                placeholder="date de fin" onchange="this.dispatchEvent(new InputEvent('input'))" />
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table align-middle table-nowrap mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 20px;">
                                    <div class="form-check font-size-16 align-middle">
                                        <input class="form-check-input" type="checkbox" id="transactionCheck01">
                                        <label class="form-check-label" for="transactionCheck01"></label>
                                    </div>
                                </th>
                                <th class="align-middle">position</th>
                                <th class="align-middle">client</th>
                                <th class="align-middle">chiffre d'affaire Total</th>
                                <th class="align-middle">chiffre d'affaire encaissé</th>
                                <th class="align-middle">action</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($clients as $client)
                                <tr>
                                    <td>
                                        <div class="form-check font-size-16">
                                            <input class="form-check-input" type="checkbox"
                                                id="transactionCheck{{ $client->id }}">
                                            <label class="form-check-label"
                                                for="transactionCheck{{ $client->id }}"></label>
                                        </div>
                                    </td>
                                    <td><a href="{{ $client->url }}"
                                            class="text-body fw-bold">{{ $loop->index + 1 }}</a>
                                    </td>
                                    <td>{{ $client->entreprise }}</td>
                                    <td>
                                        {{ number_format($client->invoices_sum_price_total, 2) }}
                                    </td>
                                    <td>
                                        {{ number_format($client->price_total_paid, 2) }}
                                    </td>
                                    <td>
                                        <a href="{{ $client->url }}"
                                            class="btn btn-primary btn-sm btn-rounded waves-effect waves-light">
                                            Détails
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
</div>
