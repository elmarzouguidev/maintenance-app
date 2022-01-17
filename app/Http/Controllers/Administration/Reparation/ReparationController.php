<?php

namespace App\Http\Controllers\Administration\Reparation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReparationController extends Controller
{


    public function index()
    {
        return view('theme.pages.Reparation.index');
    }
}
