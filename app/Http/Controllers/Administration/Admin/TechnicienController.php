<?php

namespace App\Http\Controllers\Administration\Admin;

use App\Domain\Support\SaveModel\SaveModel;
use App\Http\Controllers\Controller;
use App\Http\Requests\Application\Technicien\TechnicienFormRequest;
use App\Http\Requests\Application\Technicien\TechnicienPermissionFormRequest;
use App\Http\Requests\Application\Technicien\TechnicienUpdateFormRequest;
use App\Models\Authentification\Technicien;
use App\Repositories\Technicien\TechnicienInterface;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class TechnicienController extends Controller
{


    public function index()
    {
        $techniciens = app(TechnicienInterface::class)->getTechniciens();

        return view('theme.pages.Auth.Technicien.index', compact('techniciens'));
    }

    public function create()
    {
        return view('theme.pages.Auth.Technicien.__create.index');
    }

    public function store(TechnicienFormRequest $request)
    {
        $data = $request->withoutHoneypot();

        (new SaveModel(new Technicien(), $data))->execute();

        return redirect()->back()->with('success', "L'ajoute a éte effectuer avec success");
    }

    public function edit(Technicien $technicien)
    {

        //dd($technicien, "###");
        $permissions = Permission::all();

        return view('theme.pages.Auth.Technicien.__profile.index', compact('technicien', 'permissions'));
    }

    public function update(TechnicienUpdateFormRequest $request, $technicien)
    {

        //dd($request->all());
    
        $technicien = Technicien::findOrFail($technicien);

        $technicien->nom = $request->nom;
        $technicien->prenom = $request->prenom;
        $technicien->telephone = $request->telephone;
        $technicien->email = $request->email;
        $technicien->save();

        $technicien->syncPermissions($request->permissions);

        return redirect()->back()->with('success', "Update  a éte effectuer avec success");
    }

    public function syncPermission(TechnicienPermissionFormRequest $request, $technicien)
    {

      // dd('Oui tecvh',$request->permissions);
        $technicien = Technicien::findOrFail($technicien);

        $technicien->syncPermissions($request->permissions);

        return redirect()->back()->with('success', "Syn permissions   a éte effectuer avec success");
    }

    public function delete(Request $request)
    {
        $admin = Technicien::find($request->techId);
        
        if ($admin) {

            // $admin->delete();

            return redirect()->back()->with('success', "Le technicien  a éte supprimer  avec success");
        }
        return redirect()->back()->with('success', "un problem a été détécter ... ");
    }
}
