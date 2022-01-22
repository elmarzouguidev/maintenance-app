<?php

namespace App\Http\Controllers\Administration\Diagnostique;

use App\Http\Controllers\Controller;
use App\Http\Requests\Application\Report\ReportFormRequest;
use App\Models\Ticket;

use Illuminate\Http\Request;

class DiagnostiqueController extends Controller
{

    public function index()
    {
        $user = \ticketApp::activeGuard();

        if ($user === 'technicien') {

            $tickets = auth()->user()->tickets()->get()->groupByStatus();

            return view('theme.pages.Diagnostic.index', compact('tickets'));
        }

        if ($user === 'admin') {

            $tickets = Ticket::whereIn('status', ['en-attent-de-devis', 'retour-non-reparable'])
                ->whereIn('etat', ['reparable', 'non-reparable'])
                ->with('technicien')
                ->get();

            return view('theme.pages.Diagnostic.__admin.index', compact('tickets'));
        }
    }

    public function diagnose(string $slug)
    {

        $user = \ticketApp::activeGuard();

        $tickett = Ticket::whereUuid($slug)->firstOrFail();

        $tickett->update([$user . '_id'  => auth($user)->id()]);

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
       // dd('Oui',$request->response);
        $request->validate([

            'ticketId' => 'required|uuid',
            'report' => 'required|integer',
            'response' => 'required|string|in:devis-confirme,retour-devis-non-confirme'
        ]);

        $ticket = Ticket::whereUuid($request->ticketId)->firstOrFail();

        if ($request->response === 'devis-confirme') {
            $status = 'a-preparer';
        } elseif ($request->response === 'retour-devis-non-confirme') {
            $status = 'retour-devis-non-confirme';
        }
        $ticket->update(['status' => $status]);

        return redirect()->back()->with('success', "Le Ticket a éte Traité  avec success");
    }
}
