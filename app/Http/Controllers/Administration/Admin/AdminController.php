<?php

namespace App\Http\Controllers\Administration\Admin;

use App\Domain\Support\SaveModel\SaveModel;
use App\Http\Controllers\Controller;
use App\Http\Requests\Application\Admin\AdminFormRequest;
use App\Models\Authentification\Admin;
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

    public function store(AdminFormRequest $request)
    {

        $data = $request->withoutHoneypot();

        (new SaveModel(new Admin(), $data))->execute();

        return redirect()->back()->with('success', "L'ajoute a éte effectuer avec success");
    }

    public function delete(Request $request)
    {
        $admin = Admin::find($request->adminId);
        $admin->delete();
        return redirect()->back()->with('success', "La suppression a éte effectuer avec success");
    }
}
