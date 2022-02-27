<?php

namespace App\Http\Controllers\Administration\Diagnostique;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Http\Requests\Application\Report\ReportFormRequest;
use App\Models\Ticket;
use Illuminate\Http\Request;

class DiagnostiqueController extends Controller
{

    public function index()
    {

        if (auth()->user()->hasRole('Technicien')) {

            $tickets = auth()->user()->tickets()->with('client:id,entreprise')->get()->groupByStatus();

            return view('theme.pages.Diagnostic.index', compact('tickets'));
        }

        if (auth()->user()->hasAnyRole('SuperAdmin', 'Admin')) {

            $tickets = Ticket::whereIn('status', [Status::EN_ATTENTE_DE_DEVIS, Status::RETOUR_NON_REPARABLE, Status::EN_ATTENTE_DE_BON_DE_COMMAND])
                ->whereIn('etat', ['reparable', 'non-reparable'])
                ->whereNotNull('user_id')
                ->with('technicien:id,nom,prenom', 'client:id,entreprise')
                ->get()->groupByReparEtat();
            return view('theme.pages.Diagnostic.__admin.index', compact('tickets'));
        }

    }

    public function diagnose(Ticket $ticket)
    {

        if (auth()->user()->hasAnyRole('SuperAdmin', 'Admin')) {

            $ticket->loadCount('estimate');
        }

        if (auth()->user()->hasRole('Technicien') && $ticket->user_id === null) {
            //dd('Oui  here');
            $ticket->technicien()->associate(auth()->user()->id)->save();

            $ticket->update(['status' => Status::EN_COURS_DE_DIAGNOSTIC]);

            $ticket->statuses()->attach(
                Status::EN_COURS_DE_DIAGNOSTIC,
                [
                    'user_id' => auth()->id(),
                    'start_at' => now(),
                    'description' => __('status.history.' . Status::EN_COURS_DE_DIAGNOSTIC, ['user' => auth()->user()->full_name])
                ]);

        }

        return view('theme.pages.Ticket.__diagnostic.index', compact('ticket'));
    }

    public function storeDiagnose(ReportFormRequest $request, Ticket $ticket)
    {
        //dd($request->all());
        //dd($ticket->diagnoseReports()->count()<= 0);

        if ($ticket->diagnoseReports()->count() <= 0) {

            $ticket->statuses()->attach(
                Status::EN_COURS_DE_DIAGNOSTIC,
                [
                    'user_id' => auth()->id(),
                    'start_at' => now(),
                    'description' => __('status.history.rediger_le_rapport', ['user' => auth()->user()->full_name])
                ]);
        }

        $ticket->reports()->updateOrCreate(
            [
                'ticket_id' => $ticket->id,
                'type' => $request->type,
            ],
            [
                'content' => $request->content,
                'type' => $request->type,
                'user_id' => auth()->id()
            ]
        );

        $ticket->update(['etat' => $request->etat]);

        $message = "Le rapport a éte crée  avec success";

        if ($request->has('sendreport') && $request->filled('sendreport') && $request->sendreport === 'yessendit') {

            if ($request->etat === 'reparable') {

                // $ticket->statuses()->attach(Status::EN_ATTENTE_DE_BON_DE_COMMAND, ['user_id' => auth()->id(), 'start_at' => now()]);

                $ticket->update(['status' => Status::EN_ATTENTE_DE_DEVIS]);

                $ticket->statuses()->attach(
                    Status::EN_ATTENTE_DE_DEVIS,
                    [
                        'user_id' => auth()->id(),
                        'start_at' => now(),
                        'description' => __('status.history.' . Status::EN_ATTENTE_DE_DEVIS, ['user' => auth()->user()->full_name])
                    ]);

                $ticket->diagnoseReports()->update(['close_report' => true]);

                $message = "Le rapport a éte envoyer  avec success";

            }

            if ($request->etat === 'non-reparable') {

                $ticket->update(['etat' => $request->etat, 'status' => Status::RETOUR_NON_REPARABLE]);

                $ticket->statuses()->attach(
                    Status::RETOUR_NON_REPARABLE,
                    [
                        'user_id' => auth()->id(),
                        'start_at' => now(),
                        'description' => __('status.history.' . Status::RETOUR_NON_REPARABLE, ['user' => auth()->user()->full_name])
                    ]);

                $ticket->diagnoseReports()->update(['close_report' => true]);
            }
        }

        return redirect()->back()->with('success', $message);
    }

    public function sendConfirm(Request $request, Ticket $ticket)
    {
        //dd('Oui',$request->response);
        $request->validate([
            'response' => 'required|string|in:devis-confirme,retour-devis-non-confirme'
        ]);

        if ($request->response === 'devis-confirme') {
            // dd('Ouiiiii');

            $ticket->statuses()->attach(Status::A_REPARER, ['user_id' => auth()->id(), 'changed_at' => now()]);

            $ticket->update(['status' => Status::A_REPARER]);

            $ticket->diagnoseReports()->update(['close_report' => true]);

        } elseif ($request->response === 'retour-devis-non-confirme') {
            //  dd('Noooooo');
            $ticket->statuses()->attach(Status::RETOUR_DEVIS_NON_CONFIRME, ['user_id' => auth()->id(), 'changed_at' => now()]);

            $ticket->update(['status' => Status::RETOUR_DEVIS_NON_CONFIRME]);

            $ticket->diagnoseReports()->update(['close_report' => true]);
        }

        return redirect()->back()->with('success', "Le Ticket a éte Traité  avec success");
    }
}
