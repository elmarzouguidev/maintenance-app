<?php

namespace App\Http\Controllers\Administration\Admin;

use App\Domain\Support\SaveModel\SaveModel;
use App\Http\Controllers\Controller;
use App\Http\Requests\Application\Technicien\TechnicienFormRequest;
use App\Models\Authentification\Technicien;
use App\Repositories\Technicien\TechnicienInterface;
use Illuminate\Http\Request;

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

    public function delete(Request $request)
    {
        $admin = Technicien::find($request->techId);
        $admin->delete();
        return redirect()->back()->with('success', "La suppression a éte effectuer avec success");
    }
}
