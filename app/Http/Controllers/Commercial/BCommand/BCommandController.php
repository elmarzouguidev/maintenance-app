<?php

namespace App\Http\Controllers\Commercial\BCommand;

use App\Http\Controllers\Controller;
use App\Models\Finance\BCommand;
use Illuminate\Http\Request;

class BCommandController extends Controller
{

    public function index()
    {

        $commandes = BCommand::all();

        return view('theme.pages.Commercial.BC.index', compact('commandes'));
    }
    
    public function create()
    {
        return view('theme.pages.Commercial.BC.__create.index');
    }
}
