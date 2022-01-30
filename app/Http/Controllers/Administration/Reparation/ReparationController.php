<?php

namespace App\Http\Controllers\Administration\Reparation;

use App\Http\Controllers\Controller;
use App\Http\Requests\Application\Reparation\ReparationFormRequest;
use App\Models\Ticket;
use Illuminate\Http\Request;

class ReparationController extends Controller
{


    public function index()
    {
        $user = \ticketApp::activeGuard();

        if ($user === 'technicien') {

            $tickets = auth()->user()->tickets()
                ->whereEtat('reparable')
                //->whereStatus('confirme')
                ->get()->groupByReparEtat();

            return view('theme.pages.Reparation.index', compact('tickets'));
        }
    }

    public function single(string $slug)
    {

        $ticket = Ticket::whereUuid($slug)->with('reparationReports', 'diagnoseReports', 'technicien')->firstOrFail();

        //$ticket->update(['status' => 'encours-de-reparation']);
        $statusDetail = auth()->user()->full_name . " a commencé la reparation";

        $ticket->setStatus('encours-de-reparation', $statusDetail);

        return view('theme.pages.Reparation.__single.index', compact('ticket'));
    }

    public function store(ReparationFormRequest $request, $slug)
    {

        $data = $request->withoutHoneypot();

        $ticket = Ticket::whereUuid($slug)->firstOrFail();

        $report = $ticket->reparationReports()->updateOrCreate(
            [

                'ticket_id' => $ticket->id,
                'type' => 'reparation',
            ],
            [
                'content' => $data['content'],
                'type' => 'reparation',
                'technicien_id' => auth('technicien')->user()->id,
            ]
        );

        if ($report) {

            if ($data['etat'] === 'reparable') {

                $status = 'encours-de-reparation';
                $statusDetail = auth()->user()->full_name . " a commencé la reparation est en train de rédiger le rapport";

                $ticket->setStatus('encours-de-reparation', $statusDetail);
            } elseif ($data['etat'] === 'non-reparable') {
                $status = 'retour-non-reparable';
            }

            $ticket->update(['status' => $status]);
        }

        $message = "Le rapport a éte crée  avec success";

        if ($request->has('reparation_done') && $request->filled('reparation_done') && $request->reparation_done === 'reparation_done') {

            if ($data['etat'] === 'reparable') {

                $status = 'pret-a-livre';

                $statusDetail = auth()->user()->full_name . " a terminé la réparation";
                $ticket->setStatus('pret-a-livre', $statusDetail);
                
            } elseif ($data['etat'] === 'non-reparable') {
                $status = 'retour-non-reparable';
            }

            $message = "La réparation a éte terminé  avec success";

            $ticket->update(['status' => $status, 'pret_a_facture' => true]);
        }

        return redirect()->back()->with('success', $message);
    }
}
