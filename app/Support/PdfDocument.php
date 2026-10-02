<?php

namespace App\Support;

use Illuminate\Http\Response;
use Mpdf\Mpdf;
use Mpdf\Output\Destination;

class PdfDocument
{
    public function __construct(private readonly Mpdf $pdf)
    {
    }

    public function output(): string
    {
        return $this->pdf->Output('', Destination::STRING_RETURN);
    }

    public function stream(string $filename): Response
    {
        $filename = str_replace(["\r", "\n", '"'], '', $filename);

        return response($this->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.$filename.'"',
        ]);
    }
}
