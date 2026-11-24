<?php

namespace App\Exports;

use App\Models\Finance\Provider;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

/**
 * The suppliers list (same search and order as the page) as a spreadsheet or a PDF. Replaces the
 * DataTables Excel and PDF buttons of the former page.
 *
 * @implements WithMapping<Provider>
 */
class ProvidersListExport implements FromQuery, ShouldAutoSize, WithHeadings, WithMapping
{
    use Exportable;

    public function __construct(public string $search = '') {}

    /**
     * @return Builder<Provider>
     */
    public function query(): Builder
    {
        return Provider::query()->select(['id', 'code', 'entreprise', 'contact', 'telephone', 'email', 'ice', 'rc'])->listing($this->search);
    }

    /**
     * @return list<string>
     */
    public function headings(): array
    {
        return ['Code fournisseur', 'Entreprise', 'Contact', 'Téléphone', 'E-mail', 'ICE', 'RC'];
    }

    /**
     * @param  Provider  $provider
     * @return list<string|null>
     */
    public function map($provider): array
    {
        return [$provider->code, $provider->entreprise, $provider->contact, $provider->telephone, $provider->email, $provider->ice, $provider->rc];
    }
}
