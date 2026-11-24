<?php

namespace App\Exports;

use App\Models\Client;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

/**
 * The clients list (same search and order as the page) as a spreadsheet or a PDF. Replaces the
 * DataTables Excel and PDF buttons of the former page; the full backup export stays ClientsExport.
 *
 * @implements WithMapping<Client>
 */
class ClientsListExport implements FromQuery, ShouldAutoSize, WithHeadings, WithMapping
{
    use Exportable;

    public function __construct(public string $search = '') {}

    /**
     * @return Builder<Client>
     */
    public function query(): Builder
    {
        return Client::query()->select(['id', 'code', 'entreprise', 'telephone', 'email', 'ice'])->listing($this->search);
    }

    /**
     * @return list<string>
     */
    public function headings(): array
    {
        return ['Code client', 'Entreprise', 'Téléphone', 'E-mail', 'ICE'];
    }

    /**
     * @param  Client  $client
     * @return list<string|null>
     */
    public function map($client): array
    {
        return [$client->code, $client->entreprise, $client->telephone, $client->email, $client->ice];
    }
}
