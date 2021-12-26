<?php

namespace App\Http\Controllers\Administration\Admin;

use App\Domain\Support\SaveModel\SaveModel;
use App\Http\Controllers\Controller;
use App\Http\Requests\Application\Reception\ReceptionFormRequest;
use App\Models\Authentification\Reception;
use App\Repositories\Reception\ReceptionInterface;
use Illuminate\Http\Request;

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
}
