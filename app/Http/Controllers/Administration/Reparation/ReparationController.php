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

        $ticket = Ticket::whereExternalId($slug)->with('reparationReports', 'diagnoseReports', 'technicien')->firstOrFail();

        return view('theme.pages.Reparation.__single.index', compact('ticket'));
    }

    public function store(ReparationFormRequest $request, $slug)
    {


        $data = $request->withoutHoneypot();

        $ticket = Ticket::whereExternalId($slug)->firstOrFail();

        $ticket->reparationReports()->updateOrCreate(
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
        $ticket->update(['status' => 'encours-reparation']);

        return redirect()->back()->with('success', "Le rapport a éte crée  avec success");
    }

    public function repearComplet(Request $request, $slug)
    {
      
        $data = $request->withoutHoneypot();

        $ticket = Ticket::whereExternalId($slug)->firstOrFail();

        $ticket->update(['status' => 'finalizer-reparation']);

        //return redirect()->back()->with('success', "La réparation  a éte terminé  avec success");
        return redirect()->route('admin:receptions.list');
    }
}
