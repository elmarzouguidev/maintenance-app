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

        if (auth()->user()->hasRole('Technicien') && $ticket->user_id === auth()->id() && $ticket->status !== Status::EN_COURS_DE_REPARATION) {

            $ticket->update(['status' => Status::EN_COURS_DE_REPARATION]);

            $ticket->statuses()->attach(Status::EN_COURS_DE_REPARATION, ['user_id' => auth()->id(), 'changed_at' => now()]);

        }

        return view('theme.pages.Reparation.__single.index', compact('ticket'));
    }

    public function store(ReparationFormRequest $request, Ticket $ticket)
    {
        $ticket->reparationReports()->updateOrCreate(
            [
                'ticket_id' => $ticket->id,
                'type' => 'reparation',
            ],
            [
                'content' => $request->content,
                'type' => 'reparation',
                'user_id' => auth()->id(),
            ]
        );

        $message = "Le rapport a éte enregistrer  avec success";

        if ($request->has('reparation_done') && $request->filled('reparation_done') && $request->reparation_done === 'reparation_done') {

            $ticket->update(['status' => Status::PRET_A_ETRE_LIVRE, 'can_invoiced' => true]);

            $ticket->statuses()->attach([Status::PRET_A_ETRE_LIVRE, Status::PRET_A_ETRE_FACTURE], ['user_id' => auth()->id(), 'changed_at' => now()]);

            $message = "La réparation a éte terminé  avec success";

            $ticket->reparationReports()->update(['close_report' => true]);

            return redirect()->back()->with('success', $message);
        }
        return redirect()->back()->with('success', $message);
    }
}
