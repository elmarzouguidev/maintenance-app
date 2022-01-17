<?php

namespace App\Http\Controllers\Administration\Diagnostic;

use App\Http\Controllers\Controller;
use App\Models\Utilities\Report;
use Illuminate\Http\Request;

class DiagnosticController extends Controller
{

    public function index()
    {
        $user = \ticketApp::activeGuard();

        if ($user === 'technicien') {

            $reports = auth()->user()->reports()->get()->groupByStatus();

            return view('theme.pages.Diagnostic.index', compact('reports'));
        }

        if ($user === 'admin') {

            $reports = Report::whereStatus('envoyer')
                ->whereEtat('reparable')
                ->whereNotNull(['envoyer_at', 'ouvert_at'])
                ->with('technicien')->get();

            return view('theme.pages.Diagnostic.__admin.index', compact('reports'));
        }
    }
}
