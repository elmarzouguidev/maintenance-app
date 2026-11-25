<?php

namespace App\Http\Controllers\Administration\PDF;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Support\TicketReportPdf;
use Symfony\Component\HttpFoundation\Response;

class GenerateReportController extends Controller
{
    public function ticketReport(Ticket $ticket, TicketReportPdf $report): Response
    {
        return $report->build($ticket)->stream($report->fileName($ticket));
    }
}
