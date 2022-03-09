<?php

namespace App\Http\Controllers\Administration\PDF;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class GenerateReportController extends Controller
{


    public function ticketReport(Ticket $ticket)
    {
        $ticket->load(['client', 'technicien','diagnoseReports','reparationReports','delivery'])->loadCount('delivery');
        $image = Media::whereModelType('App\Models\Ticket')->whereModelId($ticket->id)->first()->getPath('normal');
        $data = [
            'images' => Media::whereModelType('App\Models\Ticket')->whereModelId($ticket->id)->first()->getPath('normal'),
            'logo'=>"data:image/jpg;base64," . base64_encode(file_get_contents($image))
        ];

        $companyLogo = "data:image/jpg;base64," . base64_encode(file_get_contents($image));

        //dd($data,  $image,$companyLogo);

        $pdf = \PDF::loadView('theme.pages.Ticket.__pdf.Report.index', compact('ticket','data'));

        $fileName = $ticket->code . 'RAPPORT.pdf';

        return $pdf->stream($fileName);

        // return view('theme.pages.Ticket.__pdf.Report.index', compact('ticket'));
    }

}
