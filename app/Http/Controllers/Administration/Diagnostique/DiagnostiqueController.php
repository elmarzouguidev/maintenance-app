<?php

namespace App\Http\Controllers\Administration\Diagnostique;

use App\Http\Controllers\Controller;
use App\Http\Requests\Application\Report\ReportFormRequest;
use App\Models\Ticket;

use Illuminate\Http\Request;

class DiagnostiqueController extends Controller
{



    public function diagnose(string $slug)
    {

        $tickett = Ticket::whereUuid($slug)->firstOrFail();

        $tickett->update(['technicien_id' => auth('technicien')->id()]);

        return view('theme.pages.Ticket.__diagnostic.index', compact('tickett'));
    }

    public function storeDiagnose(ReportFormRequest $request, $slug)
    {
        //dd($request->all());

        $data = $request->withoutHoneypot();

        $ticket = Ticket::whereUuid($slug)->firstOrFail();

        $report = $ticket->reports()->updateOrCreate(
            [

                'ticket_id' => $ticket->id,
                'type' => $data['type'],
            ],
            [
                'content' => $data['content'],
                'type' => $data['type'],
                'technicien_id' => auth('technicien')->id()
            ]
        );

        if ($report) {

            if ($data['etat'] === 'reparable') {
                $status = 'encours-diagnostique';
            } elseif ($data['etat'] === 'non-reparable') {
                $status = 'retour-non-reparable';
            }

            $ticket->update(['etat' => $data['etat'], 'status' => $status]);
        }

        if ($request->has('sendreport') && $request->filled('sendreport') && $request->sendreport === 'yessendit') {

            if ($data['etat'] === 'reparable') {
                $status = 'en-attent-de-devis';
            } elseif ($data['etat'] === 'non-reparable') {
                $status = 'retour-non-reparable';
            }

            $ticket->update(['status' => $status]);
        }

        return redirect()->back()->with('success', "Le rapport a éte crée  avec success");
    }


    public function sendReport(Request $request, $slug)
    {
        $request->validate(['ticketId' => 'required|integer']);

        $ticket = Ticket::whereUuid($slug)->firstOrFail();

        $request->whenFilled('ticketId', function ($input) use ($ticket) {
            $ticket->update(['status' => 'en-attent-de-devis']);
        });

        return redirect()->back()->with('success', "Le rapport a éte envoyer  avec success");
    }

    public function sendConfirm(Request $request, $slug)
    {
        //dd('Oui');
        $request->validate([

            'ticketId' => 'required|integer',
            'report' => 'required|integer',
            'response' => 'required|string|in:confirme,annuler'
        ]);

        $ticket = Ticket::whereId($request->ticketId)->firstOrFail();

        $ticket->update(['status' => $request->response]);

        return redirect()->back()->with('success', "Le rapport a éte confirmé  avec success");
    }
}
