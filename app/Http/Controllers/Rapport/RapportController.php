<?php

declare(strict_types=1);

namespace App\Http\Controllers\Rapport;

use App\Http\Controllers\Controller;
use App\Http\Requests\Application\Report\UpdateReportFormRequest;
use App\Models\Ticket;
use App\Models\Utilities\Report;
use App\Support\TicketReportPdf;
use Symfony\Component\HttpFoundation\Response;

class RapportController extends Controller
{
    private const PER_PAGE = 30;

    public function index()
    {
        $reportes = Report::with(['ticket', 'technicien:id,nom,prenom'])->latest()->paginate(self::PER_PAGE);

        return view('theme.pages.TicketRapport.__datatable.index', compact('reportes'));
    }

    public function generateRepport(string $ticket, TicketReportPdf $report): Response
    {
        $ticket = Ticket::whereUuid($ticket)->firstOrFail();

        return $report->build($ticket)->stream($report->fileName($ticket));
    }

    public function editions()
    {
        // $reportes = Report::with(['ticket', 'technicien'])->latest()->get();

        $tickets = Ticket::has('reports')->with(['client:id,entreprise', 'technicien:id,nom,prenom'])->latest()->paginate(self::PER_PAGE);

        return view('theme.pages.TicketRapport.Edition.__datatable.index', compact('tickets'));
    }

    public function edit($ticket)
    {
        $ticket = Ticket::whereUuid($ticket)->firstOrFail();

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

            return redirect()->back()->with('success', 'Le rapport a été modifier avec success');
        }

        return redirect()->back()->with('success', 'un problem a été détécter ... ');
    }
}
