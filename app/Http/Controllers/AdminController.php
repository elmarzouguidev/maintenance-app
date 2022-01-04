<?php

namespace App\Http\Controllers;

use App\Repositories\Admin\AdminInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AdminController extends Controller
{

    public function index()
    {
        return app(AdminInterface::class)->getAdmins();
    }

    public function appIndex(): string
    {
        return "Hello World";
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(): Response
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request): Response
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Authenctification\Admin $admin
     * @return Response
     */
    public function show(Admin $admin): Response
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Authenctification\Admin $admin
     * @return Response
     */
    public function edit(Admin $admin)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param \App\Models\Authenctification\Admin $admin
     * @return Response
     */
    public function update(Request $request, Admin $admin)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Authenctification\Admin $admin
     * @return Response
     */
    public function destroy(Admin $admin)
    {
        //
    }
}
