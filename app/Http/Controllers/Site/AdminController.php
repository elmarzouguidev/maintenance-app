<?php

namespace App\Http\Controllers\Site;

use App\Domain\Support\SaveModel\SaveModel;
use App\Http\Controllers\Controller;
use App\Http\Requests\Application\Admin\AdminFormRequest;
use App\Models\Authentification\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    //

    public function index()
    {
        return view('form-2');
    }

    public function create(AdminFormRequest $request): string
    {
        $data = $request->except('_token');

       // $saveModel = new SaveModel(new Admin(),$data);

       // $saveModel->execute();

        (new SaveModel(new Admin(),$data))->execute();

        /***Test update model**/

        // (new SaveModel(Admin::find(1),$request->only(['logo'])))->execute();

        return 'Yes';
    }
}
