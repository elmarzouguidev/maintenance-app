<?php

namespace App\Http\Controllers\Commercial\Estimate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EstimateController extends Controller
{
    public function index()
    {
        return view('theme.pages.Commercial.Estimate.index');
    }
}
