<?php

namespace App\Http\Controllers\Site;

use App\Domain\Support\SaveModel\SaveModel;
use App\Http\Controllers\Controller;
use App\Http\Requests\Application\Technicien\TechnicienFormRequest;
use App\Models\Authentification\Technicien;
use Illuminate\Http\Request;

class TechnicienController extends Controller
{
    public function index()
    {
        return view('form-3');
    }

    public function create(TechnicienFormRequest $request): string
    {

        $data = $request->except('_token');

        $data = (new SaveModel(new Technicien(), $data))->execute();

        dd($data);
        /***Test update model**/

        // (new SaveModel(Technicien::find(1),$request->only(['logo'])))->execute();

        return 'Yes';
    }
}
