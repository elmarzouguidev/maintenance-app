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
            'tempDir' => $temporaryDirectory,
        ]);

        $pdf->WriteHTML(view($view, $data)->render());

        return new PdfDocument($pdf);
    }
}
