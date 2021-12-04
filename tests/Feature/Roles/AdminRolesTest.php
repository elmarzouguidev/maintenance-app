<?php

namespace Tests\Feature\Roles;

use App\Models\Authentification\Admin;
use Elmarzougui\Roles\Helpers\Permissions;
use Elmarzougui\Roles\Helpers\Roles;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AdminRolesTest extends TestCase
{

    public function test_give_role_to_admin()
    {
        $role =  Roles::new()->firstOrCreate(['name' => 'writer'], ['name' => 'writer', 'guard_name' => 'admin']);

        $admin = Admin::factory()->create();

        $admin->assignRole('writer');

        $this->assertDatabaseHas('model_has_roles', ['model_id' => $admin->id, 'role_id' => $role->id]);
    }

    public function test_give_permission_to_admin()
    {

        $admin = Admin::factory()->create();

        $permission = Permissions::new()->firstOrCreate(['name' => 'add tickets'], ['name' => 'add tickets', 'guard_name' => 'admin']);

        $admin->givePermissionTo('add tickets');

        $this->assertDatabaseHas('model_has_permissions', ['model_id' => $admin->id, 'permission_id' => $permission->id]);
    }

    public function test_check_if_admin_has_as_permission()
    {

        $admin = Admin::factory()->create();

        $permission = Permissions::new()->firstOrCreate(['name' => 'add tickets'], ['name' => 'add tickets', 'guard_name' => 'admin']);

        $admin->givePermissionTo($permission->name);

        $check =  $admin->hasDirectPermission($permission->name);

        $this->assertTrue($check == true, "the admin has permission");
    }
}
