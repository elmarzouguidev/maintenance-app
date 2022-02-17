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

        if (auth()->user()->hasRole('Technicien')) {

            $tickets = auth()->user()->tickets()->with('client:id,entreprise')->get()->groupByStatus();

            return view('theme.pages.Diagnostic.index', compact('tickets'));
        }

        if (auth()->user()->hasRole('SuperAdmin')) {

            $tickets = Ticket::whereIn('status', ['en-attent-de-devis', 'retour-non-reparable'])
                ->whereIn('etat', ['reparable', 'non-reparable'])
                ->with('technicien:id,nom,prenom', 'client:id,entreprise')
                ->get()->groupByReparEtat();
            return view('theme.pages.Diagnostic.__admin.index', compact('tickets'));
        }

    }

    public function diagnose(Ticket $ticket)
    {
        $ticket->loadCount('estimate');

        if (auth()->user()->hasRole('Technicien') && $ticket->user_id === null) {

            $ticket->technicien()->associate(auth()->user()->id)->save();

            activity()
                ->causedBy(auth()->user())
                ->performedOn($ticket)
                ->withProperties(['status' => 'encours-diagnostique'])
                ->log('Encours de diagnostique');
        }

        return view('theme.pages.Ticket.__diagnostic.index', compact('ticket'));
    }

    public function storeDiagnose(ReportFormRequest $request, Ticket $ticket)
    {
        //dd($request->all());

        $report = $ticket->reports()->updateOrCreate(
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

        $message = '';

        if ($report) {

            if ($request->etat === 'reparable') {

                $status = 'encours-diagnostique';

                activity()
                    ->causedBy(auth()->user())
                    ->performedOn($ticket)
                    ->withProperties(['status' => 'encours-diagnostique'])
                    ->log('le produit est réparable est en train de rediger le rapport');

            } elseif ($request->etat === 'non-reparable') {
                $status = 'retour-non-reparable';

                activity()
                    ->causedBy(auth()->user())
                    ->performedOn($ticket)
                    ->withProperties(['status' => $status])
                    ->log('le produit est non réparable');
            }

            $ticket->update(['etat' => $request->etat, 'status' => $status]);

            $message = "Le rapport a éte crée  avec success";
        }

        if ($request->has('sendreport') && $request->filled('sendreport') && $request->sendreport === 'yessendit') {

            if ($request->etat === 'reparable') {

                $status = 'en-attent-de-devis';

                activity()
                    ->causedBy(auth()->user())
                    ->performedOn($ticket)
                    ->withProperties(['status' => $status])
                    ->log('le produit est réparable en attent de devis ');

            } elseif ($request->etat === 'non-reparable') {
                $status = 'retour-non-reparable';
                activity()
                    ->causedBy(auth()->user())
                    ->performedOn($ticket)
                    ->withProperties(['status' => $status])
                    ->log('le produit est non réparable');
            }

            $message = "Le rapport a éte envoyer  avec success";

            $ticket->update(['status' => $status]);
        }

        return redirect()->back()->with('success', $message);
    }

    public function sendConfirm(Request $request, Ticket $ticket)
    {
        //dd('Oui',$request->response);
        $request->validate([

            'ticketId' => 'required|uuid',
            'report' => 'required|integer',
            'response' => 'required|string|in:devis-confirme,retour-devis-non-confirme'
        ]);

        if ($request->response === 'devis-confirme') {

            $status = 'a-preparer';

            activity()
                ->causedBy(auth()->user())
                ->performedOn($ticket)
                ->withProperties(['status' => $status])
                ->log('le devie a été confirmé commencer la réparation');
            $ticket->update(['status' => $status]);
        } elseif ($request->response === 'retour-devis-non-confirme') {

            $status = 'retour-devis-non-confirme';

            activity()
                ->causedBy(auth()->user())
                ->performedOn($ticket)
                ->withProperties(['status' => $status])
                ->log('le devie a été decliner ignorer la réparation');
            $ticket->update(['status' => $status]);
        }

        return redirect()->back()->with('success', "Le Ticket a éte Traité  avec success");
    }
}
