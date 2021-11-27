<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Http\Requests\Application\Client\ClientFormRequest;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
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

        $client = new Client();
        foreach ($data as $column => $value) {

            $fields = $client->saveableFields()[$column];

            switch ($fields) {

                case 'datetime':

                    $client->{$column} = Carbon::parse($value)->toDateTimeString();

                    break;
                case 'image':

                    $client->{$column} = $value->store('clients');

                    break;

                default:
                    $client->{$column} = $value;
                    break;
            }

        }

        $client->save();

        return 'Yes';
    }
}
