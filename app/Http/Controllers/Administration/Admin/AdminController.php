<?php

namespace App\Http\Controllers\Administration\Admin;

use App\Domain\Support\SaveModel\SaveModel;
use App\Http\Controllers\Controller;
use App\Http\Requests\Application\Admin\AdminFormRequest;
use App\Http\Requests\Application\Admin\AdminPermissionFormRequest;
use App\Http\Requests\Application\Admin\AdminUpdateFormRequest;
use App\Models\Authentification\Admin;
use App\Repositories\Admin\AdminInterface;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

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

    public function edit(Admin $admin)
    {

        $permissions = Permission::all();

        return view('theme.pages.Admin.__profile.index', compact('admin', 'permissions'));
    }

    public function update(AdminUpdateFormRequest $request, $admin)
    {

        //dd($request->all(),"####");

        $admin = Admin::findOrFail($admin);

        $admin->nom = $request->nom;
        $admin->prenom = $request->prenom;
        $admin->telephone = $request->telephone;
        $admin->email = $request->email;
        $admin->save();

        $admin->syncPermissions($request->permissions);

        return redirect()->back()->with('success', "Update  a éte effectuer avec success");
    }

    public function syncPermission(AdminPermissionFormRequest $request, $admin)
    {

        $admin = Admin::findOrFail($admin);

        $admin->syncPermissions($request->permissions);

        return redirect()->back()->with('success', "Syn permissions   a éte effectuer avec success");
    }

    public function delete(Request $request)
    {

        $admin = Admin::find($request->adminId);

        if ($admin) {

            // $admin->delete();

            return redirect()->back()->with('success', "L' Admin  a éte supprimer  avec success");
        }
        return redirect()->back()->with('success', "un problem a été détécter ... ");
    }
}
