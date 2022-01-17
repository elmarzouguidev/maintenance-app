<?php

namespace App\Http\Controllers\Administration\Report;

use App\Domain\Support\SaveModel\SaveModel;
use App\Http\Controllers\Controller;
use App\Http\Requests\Application\Report\ReportFormRequest;
use App\Models\Ticket;
use App\Models\Utilities\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{


    public function index()
    {
    }

    public function store(ReportFormRequest $request, $slug)
    {
        //dd($request->send);

        $data = $request->withoutHoneypot();

        $ticket = Ticket::whereExternalId($slug)->firstOrFail();

        $report = $ticket->reports()->updateOrCreate(
            [
                'external_id' => $ticket->external_id,
                'ticket' => $ticket->product,
                'technicien_id' => auth('technicien')->user()->id,
                'type' => $data['type'],
                // 'etat' => $data['etat'],
                //'ouvert_at' => now()
            ],
            [
                'ticket' => $ticket->product,
                'content' => $data['content'],
                'type' => $data['type'],
                'etat' => $data['etat'],
                'technicien_id' => auth('technicien')->user()->id,
                'ouvert_at' => now()
            ]
        );

        if ($report) {
            $ticket->technicien()->associate(auth('technicien')->user()->id)->save();
            $ticket->update(['etat' => $data['etat']]);
        }

        return redirect()->back()->with('success', "Le rapport a éte crée  avec success");
    }

    public function sendReport(Request $request, $slug)
    {
        $request->validate(['ticketId' => 'required|integer']);

        $ticket = Ticket::whereExternalId($slug)->firstOrFail();

        $request->whenFilled('ticketId', function ($input) use ($ticket) {
            $ticket->diagnoseReports()->update(['status' => 'envoyer', 'envoyer_at' => now()]);
        });

        return redirect()->back()->with('success', "Le rapport a éte envoyer  avec success");
    }
}
