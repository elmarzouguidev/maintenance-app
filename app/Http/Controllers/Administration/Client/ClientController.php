<?php

namespace App\Http\Controllers\Administration\Client;

use App\Domain\Support\SaveModel\SaveModel;
use App\Http\Controllers\Controller;
use App\Http\Requests\Application\Client\ClientFormRequest;
use App\Http\Requests\Application\Client\ClientUpdateFormRequest;
use App\Models\Client;
use App\Repositories\Category\CategoryInterface;
use App\Repositories\Client\ClientInterface;
use Illuminate\Http\Request;

class ClientController extends Controller
{


    public function index()
    {
        $clients = app(ClientInterface::class)->__instance()->withCount('tickets')->get();
        return view('theme.pages.Client.index', compact('clients'));
    }

    public function create()
    {
        $categories = app(CategoryInterface::class)->getCategories(['id', 'name']);
        return view('theme.pages.Client.__create.index', compact('categories'));
    }

    public function store(ClientFormRequest $request)
    {

        $telephones = $request->input('clients.*.telephones.*.telephone');

        //dd($request->all(),"###",$telephones);

        $data = $request->withoutHoneypot();

        $client = (new SaveModel(new Client(), $data))->ignoreFields(['category', 'clients'])->execute();

        foreach ($telephones as $tel) {
            $client->telephones()->create(['telephone' => $tel]);
        }

        if ($request->hasFile('logo')) {

            $client->addMediaFromRequest('logo')
                ->toMediaCollection('clients-logo');
        }

        $request->whenFilled('category', function ($input) use ($client) {
            // dd($input);
            $client->category()->associate($input)->save();
        });

        return redirect()->back()->with('success', "L'ajoute a éte effectuer avec success");
    }

    public function edit($id)
    {

        $client = Client::findOrFail($id);
        $categories = app(CategoryInterface::class)->getCategories(['id', 'name']);
        return view('theme.pages.Client.__edit.index', compact('client', 'categories'));
    }

    public function update(ClientUpdateFormRequest $request, $id)
    {
        $data = $request->withoutHoneypot();
        $client = (new SaveModel(Client::find($id), $data))->ignoreFields(['category'])->execute();

        if ($request->hasFile('photo')) {

            $client->addMediaFromRequest('photo')
                ->toMediaCollection('clients-logo');
        }

        $request->whenFilled('category', function ($input) use ($client) {

            $client->category()->associate($input)->save();
        });

        return redirect()->back()->with('success', "L' Update a éte effectuer avec success");
    }

    public function show(string $slug)
    {

        $client = app(ClientInterface::class)->getClientByExternalId($slug)->withCount('tickets')->firstOrFail();

        return view('theme.pages.Client.__profile.index', compact('client'));
    }
}
