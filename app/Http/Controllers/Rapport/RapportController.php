<?php

declare(strict_types=1);

namespace App\Http\Controllers\Rapport;

use App\Http\Controllers\Controller;
use App\Http\Requests\Application\Report\UpdateReportFormRequest;
use App\Models\Ticket;
use App\Models\Utilities\Report;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;

class RapportController extends Controller
{
    public function index()
    {
        $reportes = Report::with(['ticket', 'technicien:id,nom,prenom'])->latest()->get();

        return view('theme.pages.TicketRapport.__datatable.index', compact('reportes'));
    }

    public function generateRepport($ticket)
    {

        $ticket =  Ticket::whereUuid($ticket)->firstOrFail();

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


    public function editions()
    {
        //$reportes = Report::with(['ticket', 'technicien'])->latest()->get();

        $tickets = Ticket::has('reports')->with(['diagnoseReports', 'reparationReports', 'client:id,entreprise', 'technicien:id,nom,prenom'])->latest()->get();

        return view('theme.pages.TicketRapport.Edition.__datatable.index', compact('tickets'));
    }

    public function edit($ticket)
    {
        $ticket =  Ticket::whereUuid($ticket)->firstOrFail();

        $ticket->load(['diagnoseReports', 'reparationReports']);

        return view('theme.pages.TicketRapport.Edition.Edit.index', compact('ticket'));
    }

    public function update(UpdateReportFormRequest $request, Ticket $ticket)
    {
        if ($ticket->diagnoseReports || $ticket->reparationReports) {

            if ($request->report_diagnose) {
                $ticket->diagnoseReports()->update(['content' => $request->report_diagnose]);
            }

            if ($request->report_reparation) {
                $ticket->reparationReports()->update(['content' => $request->report_reparation]);
            }

            return redirect()->back()->with('success', "Le rapport a été modifier avec success");
        }

        return redirect()->back()->with('success', 'un problem a été détécter ... ');
    }
}
