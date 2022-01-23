<?php

namespace App\Http\Controllers\Commercial\Company;

use App\Http\Controllers\Controller;
use App\Http\Requests\Commercial\Company\CompanyFormRequest;
use App\Models\Finance\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{


    public function index()
    {
        $companies = Company::paginate(20);

        return view('theme.pages.Commercial.Company.index', compact('companies'));
    }


    public function create()
    {
        return view('theme.pages.Commercial.Company.__create.index');
    }


    public function store(CompanyFormRequest $request)
    {

        //dd($request->all());
        $company =  new Company();
        $company->name = $request->name;
        $company->website = $request->website;
        $company->description = $request->description;
        $company->city = $request->city;
        $company->addresse = $request->addresse;
        $company->telephone = $request->telephone;
        $company->email = $request->email;
        $company->rc = $request->rc;
        $company->ice = $request->ice;
        $company->cnss = $request->cnss;

        if ($request->hasFile('logo')) {

        }

        $company->save();

        return redirect()->back()->with('success', "L'ajoute a éte effectuer avec success");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
