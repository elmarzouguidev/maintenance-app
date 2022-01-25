<?php

namespace App\Http\Controllers\Commercial\Company;

use App\Http\Controllers\Controller;
use App\Http\Requests\Commercial\Company\CompanyFormRequest;
use App\Http\Requests\Commercial\Company\CompanyUpdateFormRequest;
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
    public function edit(Company $company)
    {
        return view('theme.pages.Commercial.Company.__edit.index', compact('company'));
    }


    public function update(CompanyUpdateFormRequest $request, $company)
    {
        $company =  Company::whereUuid($company)->firstOrFail();
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

        $company->save();

        return redirect()->back()->with('success', "La modification a éte effectuer avec success");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $request->validate(['companyId' => 'required|integer']);

        $company = Company::findOrFail($request->companyId);

        if ($company) {

            // $company->delete();

            return redirect()->back()->with('success', "La Société  a éte supprimer  avec success");
        }
        return redirect()->back()->with('success', "un problem a été détécter ... ");
    }
}