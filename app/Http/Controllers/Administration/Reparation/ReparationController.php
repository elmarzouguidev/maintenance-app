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

            $reports = auth()->user()->reports()
                ->whereEtat('reparable')
               // ->whereStatus('confirme')
                //->whereNotNull(['envoyer_at', 'confirme_at'])
                ->get()->groupByConfirm();

            return view('theme.pages.Reparation.index', compact('reports'));
        }
    }

    public function single(string $slug)
    {

        $ticket = Ticket::whereExternalId($slug)->with('reparationReports', 'diagnoseReports', 'technicien')->firstOrFail();

        return view('theme.pages.Reparation.__single.index', compact('ticket'));
    }

    public function store(ReparationFormRequest $request, $slug)
    {


        $data = $request->withoutHoneypot();

        $ticket = Ticket::whereExternalId($slug)->firstOrFail();

        $ticket->reparationReports()->updateOrCreate(
            [
                //'external_id' => $ticket->external_id,
                'ticket' => $ticket->product,
                // 'technicien_id' => auth('technicien')->user()->id,
                // 'type' => $data['type'],
                //'etat' => $data['etat'],
                //'ouvert_at' => now()
            ],
            [
                'external_id' => $ticket->external_id,
                //'ticket' => $ticket->product,
                'content' => $data['content'],
                'type' => 'reparation',
                'etat' => 'reparable',
                'status' => 'encours-reparation',
                //'reparation_status' => 'encours',
                'technicien_id' => auth('technicien')->user()->id,
                //'ouvert_at' => now()
            ]
        );

        return redirect()->back()->with('success', "Le rapport a éte crée  avec success");
    }
}
