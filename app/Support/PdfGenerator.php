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

        $pdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_left' => 5,
            'margin_right' => 5,
            'margin_top' => str_contains($view, 'theme.pages.Ticket.__pdf.Report') ? 21 : 16,
            'margin_bottom' => 27,
            'margin_footer' => 5,
            'tempDir' => $temporaryDirectory,
        ]);

        $pdf->WriteHTML(view($view, $data)->render());

        return new PdfDocument($pdf);
    }
}
