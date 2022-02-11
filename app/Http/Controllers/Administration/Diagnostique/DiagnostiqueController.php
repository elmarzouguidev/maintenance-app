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

            $tickets = Ticket::whereIn('stat', ['en-attent-de-devis', 'retour-non-reparable'])
                ->whereIn('etat', ['reparable', 'non-reparable'])
                ->with('technicien')
                ->get();

            return view('theme.pages.Diagnostic.__admin.index', compact('tickets'));
        }
    }

    public function diagnose(string $slug)
    {

        $user = \ticketApp::activeGuard();

        $tickett = Ticket::whereUuid($slug)
        ->with('estimate')
        ->withCount('estimate')
        ->without('statuses')
        ->firstOrFail();

        $tickett->update([$user . '_id'  => auth($user)->id()]);

        /*$statusDetail = auth($user)->user()->full_name . " a prendre le ticket";

        $tickett->setStatus('encours-diagnostique', $statusDetail);*/

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

        $message = '';

        if ($report) {

            if ($data['etat'] === 'reparable') {

                $status = 'encours-diagnostique';

                $statusDetail = auth()->user()->full_name . " dit : le produit est réparable est en train de diagnostiquer le ";

                $ticket->setStatus('encours-diagnostique', $statusDetail);
            } elseif ($data['etat'] === 'non-reparable') {

                $status = 'retour-non-reparable';

                $statusDetail = auth()->user()->full_name . " dit : le produit est non réparable";

                $ticket->setStatus('retour-non-reparable', $statusDetail);
            }

            $ticket->update(['etat' => $data['etat'], 'stat' => $status]);

            $message = "Le rapport a éte crée  avec success";
        }

        if ($request->has('sendreport') && $request->filled('sendreport') && $request->sendreport === 'yessendit') {

            if ($data['etat'] === 'reparable') {

                $status = 'en-attent-de-devis';

                $statusDetail = auth()->user()->full_name . " dit : le produit est réparable en attent de devis ";

                $ticket->setStatus('en-attent-de-devis', $statusDetail);
            } elseif ($data['etat'] === 'non-reparable') {

                $status = 'retour-non-reparable';

                $statusDetail = auth()->user()->full_name . " dit : le produit est non réparable";

                $ticket->setStatus('retour-non-reparable', $statusDetail);
            }

            $message = "Le rapport a éte envoyer  avec success";

            $ticket->update(['stat' => $status]);
        }

        return redirect()->back()->with('success', $message);
    }

    public function sendConfirm(Request $request, $slug)
    {
        //dd('Oui',$request->response);
        $request->validate([

            'ticketId' => 'required|uuid',
            'report' => 'required|integer',
            'response' => 'required|string|in:devis-confirme,retour-devis-non-confirme'
        ]);

        $ticket = Ticket::whereUuid($request->ticketId)->firstOrFail();

        if ($request->response === 'devis-confirme') {
            $status = 'a-preparer';

            $statusDetail = auth()->user()->full_name . " dit : le devie a été confirmé commencer la réparation";

            $ticket->setStatus('a-preparer', $statusDetail);
            
        } elseif ($request->response === 'retour-devis-non-confirme') {

            $status = 'retour-devis-non-confirme';

            $statusDetail = auth()->user()->full_name . " dit : le devie a été decliner ignorer la réparation";

            $ticket->setStatus('retour-devis-non-confirme', $statusDetail);
        }

        $ticket->update(['stat' => $status]);

        return redirect()->back()->with('success', "Le Ticket a éte Traité  avec success");
    }
}
