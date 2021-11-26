<?php

namespace App\Http\Controllers\Administration\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{


    /**
     * @return string
     */
    public function index(): string
    {
        return "hello from dashboard admin";
    }
}
