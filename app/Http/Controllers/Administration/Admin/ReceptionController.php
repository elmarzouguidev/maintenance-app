<?php

namespace App\Http\Controllers\Administration\Admin;

use App\Domain\Support\SaveModel\SaveModel;
use App\Http\Controllers\Controller;
use App\Http\Requests\Application\Reception\ReceptionFormRequest;
use App\Http\Requests\Application\Reception\ReceptionPermissionFormRequest;
use App\Http\Requests\Application\Reception\ReceptionUpdateFormRequest;
use App\Models\Authentification\Reception;
use App\Repositories\Reception\ReceptionInterface;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class ReceptionController extends Controller
{


    public function index()
    {

        $receptions = app(ReceptionInterface::class)->getReceptions();

        return view('theme.pages.Auth.Reception.index', compact('receptions'));
    }

    public function create()
    {
        return view('theme.pages.Auth.Reception.__create.index');
    }

    public function store(ReceptionFormRequest $request)
    {
        $data = $request->withoutHoneypot();

        (new SaveModel(new Reception(), $data))->execute();

        return redirect()->back()->with('success', "L'ajoute a éte effectuer avec success");
    }

    public function edit(Reception $reception)
    {

        //dd($reception, "###");
        $permissions = Permission::all();

        return view('theme.pages.Auth.Reception.__profile.index', compact('reception', 'permissions'));
    }

    public function update(ReceptionUpdateFormRequest $request, $reception)
    {

        //dd($request->all(),"YYEEES");

        $reception = Reception::findOrFail($reception);

        $reception->nom = $request->nom;
        $reception->prenom = $request->prenom;
        $reception->telephone = $request->telephone;
        $reception->email = $request->email;
        $reception->save();

        $reception->syncPermissions($request->permissions);

        return redirect()->back()->with('success', "Update  a éte effectuer avec success");
    }

    public function syncPermission(ReceptionPermissionFormRequest $request, $reception)
    {

        //dd('Oui reception ',$request->permissions);
        $reception = Reception::findOrFail($reception);

        $reception->syncPermissions($request->permissions);

        return redirect()->back()->with('success', "Syn permissions a éte effectuer avec success");
    }

    public function delete(Request $request)
    {
        $reception = Reception::find($request->receptionId);

        if ($reception) {

            // $reception->delete();

            return redirect()->back()->with('success', "Le reception  a éte supprimer  avec success");
        }
        return redirect()->back()->with('success', "un problem a été détécter ... ");
    }
}
