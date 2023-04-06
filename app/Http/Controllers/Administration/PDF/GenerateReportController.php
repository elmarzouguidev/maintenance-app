<?php

namespace App\Http\Controllers\Administration\PDF;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Support\Facades\File;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class GenerateReportController extends Controller
{
    public function ticketReport(Ticket $ticket)
    {

        $ticket->load(['client', 'technicien', 'diagnoseReports', 'reparationReports', 'delivery'])->loadCount('delivery');
        $image = Media::whereModelType('App\Models\Ticket')->whereModelId($ticket->id)->first()->getPath('normal');
        $images = Media::whereModelType('App\Models\Ticket')->whereModelId($ticket->id)->get();

        $imagesPaths = $images->map(function ($item, $key) {
            if (File::exists($item->getPath('normal'))) {
                return 'data:image/jpg;base64,' . base64_encode(file_get_contents($item->getPath('normal')));
            }
        });

        $imagesPaths->all();

        $data = [
            'firstImage' => File::exists($image) ? 'data:image/jpg;base64,' . base64_encode(file_get_contents($image)) : null,
            'allImages' => $imagesPaths,

        ];
        $pdf = \PDF::loadView('theme.pages.Ticket.__pdf.Report.index', compact('ticket', 'data'));

        $fileName = $ticket->code . 'RAPPORT.pdf';

        return $pdf->stream($fileName);
    }
}
