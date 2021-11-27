<?php

namespace App\Http\Controllers\Site;

use App\Domain\Support\SaveModel\SaveModel;
use App\Http\Controllers\Controller;
use App\Http\Requests\Application\Client\ClientFormRequest;
use App\Models\Client;

use Illuminate\Support\Str;

class ClientController extends Controller
{


    public function index()
    {
        return view('form');
    }

    public function create(ClientFormRequest $request): string
    {

        $data = $request->except('_token');

        $data['slug'] = Str::slug($request->nom.$request->prenom);

        (new SaveModel(new Client(),$data))->execute();

        return 'Yes';
    }
}
