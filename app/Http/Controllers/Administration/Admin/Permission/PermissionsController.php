<?php

namespace App\Http\Controllers\Administration\Admin\Permission;

use App\Http\Controllers\Controller;
use Elmarzougui\Roles\Helpers\Permissions;
use Illuminate\Http\Request;

class PermissionsController extends Controller
{


    public function create()
    {

        $permissions = [

            ['name' => 'edit articles'],
            ['name' => 'delete articles'],
            ['name' => 'add articles'],

        ];

        foreach ($permissions as $permission) {
            Permissions::new()->create($permission);
        }
    }
}
