<?php

namespace App\Exports;

use App\Models\Utilities\Warranty;
use App\Support\UiKit\Format;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

/**
 * The warranties list (same search and order as the page) as a spreadsheet or a PDF. Replaces
 * the DataTables Excel and PDF buttons of the former page.
 *
 * @implements WithMapping<Warranty>
 */
class WarrantiesExport implements FromQuery, ShouldAutoSize, WithHeadings, WithMapping
{
    use Exportable;

    public function __construct(public string $search = '') {}

    /**
     * @return Builder<Warranty>
     */
    public function query(): Builder
    {
        return Warranty::query()->listing($this->search);
    }

    /**
     * @return list<string>
     */
    public function headings(): array
    {
        return ['Ticket', 'Statut du ticket', 'Date de début', 'Date de fin'];
    }

    /**
     * @param  Warranty  $warranty
     * @return array{0: string, 1: string, 2: string, 3: string}
     */
    public function map($warranty): array
    {
        return [
            $warranty->ticket?->code ?? Format::FALLBACK,
            $warranty->ticket ? __('status.statuses.'.$warranty->ticket->status) : Format::FALLBACK,
            Format::date($warranty->start_at),
            Format::date($warranty->end_at),
        ];
    }
}
