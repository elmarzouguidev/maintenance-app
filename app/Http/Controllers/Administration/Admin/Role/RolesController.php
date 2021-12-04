<?php

namespace App\Http\Controllers\Administration\Admin\Role;

use App\Http\Controllers\Controller;
use Elmarzougui\Roles\Helpers\Roles;
use Illuminate\Http\Request;

class RolesController extends Controller
{

    public function create()
    {
        
        $roles = [

            ['name' => 'admin'],
            ['name' => 'writer'],
            ['name' => 'editor'],

        ];

        Roles::new()->create($roles);
    }
}
