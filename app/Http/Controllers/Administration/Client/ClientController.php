<?php

namespace App\Http\Controllers\Administration\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Application\Client\ClientFormRequest;
use App\Http\Requests\Application\Client\ClientUpdateFormRequest;
use App\Models\Client;
use App\Models\Utilities\Telephone;
use App\Repositories\Category\CategoryInterface;
use App\Repositories\Client\ClientInterface;
use Illuminate\Http\Request;

class ClientController extends Controller
{

    public function index()
    {
        $clients = app(ClientInterface::class)->__instance()->withCount('tickets')->get();

        return view('theme.pages.Client.__datatable.index', compact('clients'));
    }

    public function create()
    {
        $categories = app(CategoryInterface::class)->getCategories(['id', 'name']);
        return view('theme.pages.Client.__create.index', compact('categories'));
    }

    public function store(ClientFormRequest $request)
    {

        $telephones = $request->collect('telephones');
   
        $client = Client::create($request->validated());

        if ($telephones->count() > 1 & is_array($telephones)) {
            $client->telephones()->createMany($telephones);
        }

        if ($request->hasFile('logo')) {

            $client->addMediaFromRequest('logo')
                ->toMediaCollection('clients-logo');
        }

        $request->whenFilled('category', function ($input) use ($client) {

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

        $telephones = $request->collect('telephones');

        $client =  Client::findOrFail($id);
        $client->entreprise = $request->entreprise;
        $client->contact = $request->contact;
        $client->telephone = $request->telephone;
        $client->email = $request->email;
        $client->addresse = $request->addresse;
        $client->rc = $request->rc;
        $client->ice = $request->ice;
        $client->save();

        if ($telephones->count() > 1& is_array($telephones)) {
            $client->telephones()->createMany($telephones);
        }

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

        $client = app(ClientInterface::class)->getClientByUuid($slug)->withCount('tickets')->firstOrFail();

        return view('theme.pages.Client.__profile.index', compact('client'));
    }

    public function delete(Request $request)
    {
        $request->validate(['clientId' => 'required|uuid']);

        $client = Client::whereUuid($request->clientId)->firstOrFail();

        if ($client) {
            // dd('Oui client');

            // $client->delete();

            return redirect()->back()->with('success', "Le client a été supprimer avec success");
        }
    }

    public function deletePhone(Request $request)
    {

        $request->validate(['client' => 'required|uuid', 'phone' => 'required|uuid']);

        $client = Client::whereUuid($request->client)->firstOrFail();

        $phone = Telephone::whereUuid($request->phone)->firstOrFail();

        if ($client && $phone) {

            $client->telephones()

                ->whereUuid($request->phone)
                ->where('telephoneable_id', $client->id)
                ->delete();
            return response()->json([
                'success' => 'Record deleted successfully!'
            ]);
        }

        return response()->json([
            'error' => 'problem detected !'
        ]);
    }
}
