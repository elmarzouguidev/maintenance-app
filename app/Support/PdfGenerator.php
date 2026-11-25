<?php

namespace App\Support;

use Mpdf\Mpdf;

class PdfGenerator
{
    public function loadView(string $view, array $data = []): PdfDocument
    {
        $temporaryDirectory = storage_path('app/mpdf');

        if (! is_dir($temporaryDirectory)) {
            mkdir($temporaryDirectory, 0755, true);
        }

        // The ticket report has a running header and reading margins (App\Support\TicketReportPdf).
        $isTicketReport = str_contains($view, 'theme.pages.Ticket.__pdf.Report');

        $pdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_left' => $isTicketReport ? 14 : 5,
            'margin_right' => $isTicketReport ? 14 : 5,
            'margin_top' => $isTicketReport ? 26 : 16,
            'margin_header' => $isTicketReport ? 8 : 9,
            'margin_bottom' => 27,
            'margin_footer' => 5,
            'tempDir' => $temporaryDirectory,
        ]);

        $pdf->WriteHTML(view($view, $data)->render());

        return new PdfDocument($pdf);
    }
}
