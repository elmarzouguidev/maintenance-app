<?php

namespace App\Http\Controllers\Administration\Diagnostic;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\Utilities\Report;
use Illuminate\Http\Request;

class DiagnosticController extends Controller
{

    public function index()
    {
        $user = \ticketApp::activeGuard();

        if ($user === 'technicien') {

            $tickets = auth()->user()->tickets()->get()->groupByStatus();

            return view('theme.pages.Diagnostic.index', compact('tickets'));
        }

        if ($user === 'admin') {

            $tickets = Ticket::whereIn('status', ['ouvert', 'envoyer', 'annuler', 'attent-devis', 'confirme', 'encours-reparation', 'finalizer-reparation'])
            ->with('technicien')
            ->get();

            return view('theme.pages.Diagnostic.__admin.index', compact('tickets'));
        }
    }
}
