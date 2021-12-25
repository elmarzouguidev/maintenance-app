<?php

namespace App\Http\Controllers\Administration\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Admin\AdminInterface;
use Illuminate\Http\Request;

class AdminController extends Controller
{


    public function index()
    {
        $admins = app(AdminInterface::class)->getAdmins();

        return view('theme.pages.Admin.index', compact('admins'));
    }

    public function create()
    {
        return view('theme.pages.Admin.__create.index');
    }
}
