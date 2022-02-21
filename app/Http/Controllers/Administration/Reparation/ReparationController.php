<?php

namespace App\Http\Controllers\Administration\Reparation;

use App\Constants\Status;
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

    public function single(Ticket $ticket)
    {

        $ticket->with(['diagnoseReports:id,content', 'reparationReports:id,content', 'technicien:id,nom,prenom']);
        if (auth()->user()->hasRole('Technicien') && $ticket->user_id === auth()->user()) {

            $ticket->update(['status' => 'encours-de-reparation']);

            $ticket->statuses()->attach(Status::TICKET_STATUS['encours-de-reparation'], ['user_id' => auth()->id(), 'changed_at' => now()]);

        }

        return view('theme.pages.Reparation.__single.index', compact('ticket'));
    }

    public function store(ReparationFormRequest $request, Ticket $ticket)
    {

        //dd($request->all());
        $report = $ticket->reparationReports()->updateOrCreate(
            [
                'ticket_id' => $ticket->id,
                'type' => 'reparation',
            ],
            [
                'content' => $request->content,
                'type' => 'reparation',
                'user_id' => auth()->user()->id,
            ]
        );

        if ($report) {

            if ($request->etat === 'reparable') {

                $status = 'encours-de-reparation';
                $ticket->statuses()->attach(Status::TICKET_STATUS['encours-de-reparation'], ['user_id' => auth()->id(), 'changed_at' => now()]);

                $ticket->update(['status' => $status]);

            } elseif ($request->etat === 'non-reparable') {
                $status = 'retour-non-reparable';
            }

            $ticket->update(['status' => $status]);
        }

        $message = "Le rapport a éte crée  avec success";

        if ($request->has('reparation_done') && $request->filled('reparation_done') && $request->reparation_done === 'reparation_done') {

            if ($request->etat === 'reparable') {

                $status = 'pret-a-etre-livre';
                $ticket->statuses()->attach(Status::TICKET_STATUS['pret-a-etre-livre'], ['user_id' => auth()->id(), 'changed_at' => now()]);
                $ticket->statuses()->attach(Status::TICKET_STATUS['pret-a-etre-facture'], ['user_id' => auth()->id(), 'changed_at' => now()]);

            } elseif ($request->etat === 'non-reparable') {
                $status = 'retour-non-reparable';
            }

            $message = "La réparation a éte terminé  avec success";

            $ticket->update(['status' => $status, 'can_invoiced' => true]);
        }

        return redirect()->back()->with('success', $message);
    }
}
