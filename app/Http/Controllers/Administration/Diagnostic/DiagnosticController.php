<?php

namespace App\Http\Controllers\Administration\Diagnostic;

use App\Http\Controllers\Controller;
use App\Models\Utilities\Report;
use Illuminate\Http\Request;

class DiagnosticController extends Controller
{

    public function index()
    {
        
        $reports = auth()->user()->reports()->get()->groupByStatus();

        return view('theme.pages.Diagnostic.index', compact('reports'));
    }
}
