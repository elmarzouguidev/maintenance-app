<?php

namespace App\Http\Controllers\Administration\Warranty;

use App\Exports\WarrantiesExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Application\Warranty\WarrantyFormRequest;
use App\Models\Ticket;
use App\Models\Utilities\Warranty;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Excel as ExcelWriter;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class WarrantyController extends Controller
{
    private const WARRANTIES_PER_PAGE = 30;

    /**
     * The warranties list (UI kit): search by ticket code in the query string ("q"), 30 per page.
     */
    public function index(Request $request): View
    {
        $search = $this->search($request);

        $warranties = Warranty::query()
            ->listing($search)
            ->paginate(self::WARRANTIES_PER_PAGE)
            ->withQueryString();

        // For the add-warranty modal, which nothing opens yet (BL-40).
        $tickets = Ticket::select(['id', 'uuid', 'code'])->whereEtat('reparable')->where('livrable', true)
            ->where('can_invoiced', true)->get();

        return view('theme.pages.Warranty.index', compact('warranties', 'search', 'tickets'));
    }

    /**
     * The listed warranties, with the same search, as an Excel file (xlsx) or a PDF.
     * mPDF writes the PDF: it is the PDF library the app installs (config/excel.php names Dompdf).
     */
    public function export(Request $request, string $format): BinaryFileResponse
    {
        $export = new WarrantiesExport($this->search($request));

        return $format === 'pdf'
            ? Excel::download($export, 'garanties.pdf', ExcelWriter::MPDF)
            : Excel::download($export, 'garanties.xlsx', ExcelWriter::XLSX);
    }

    public function create() {}

    public function store(WarrantyFormRequest $request, Ticket $ticket) {}

    private function search(Request $request): string
    {
        return trim((string) $request->query('q', ''));
    }
}
