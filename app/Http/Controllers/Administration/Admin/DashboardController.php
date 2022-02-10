<?php

namespace App\Http\Controllers\Administration\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;

class DashboardController extends Controller
{


    public function index()
    {
        $tickets = Ticket::whereStat('finalizer-reparation')->get();
        
        return view('theme.pages.Home.index', compact('tickets'));
    }
}
