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

        $permission = $this->createPermission('add tickets');

        $admin->givePermissionTo('add tickets');

        $this->assertDatabaseHas('model_has_permissions', ['model_id' => $admin->id, 'permission_id' => $permission->id]);
    }

    public function test_check_if_admin_has_as_permission()
    {

        $admin = Admin::factory()->create();

        $permission = $this->createPermission('add tickets');

        $admin->givePermissionTo($permission->name);
       
        $this->assertTrue($admin->hasDirectPermission($permission->name), "the admin has permission");
    }

    public function test_only_admin_has_a_permission_can_access_to_url()
    {

        $admin = Admin::factory()->create();

        $permission = $this->createPermission('add.admins');

        $admin->givePermissionTo($permission->name);

        $response = $this->actingAs($admin, 'admin')

            ->get('/add-admin');
        // dd($response);
        $response->assertStatus(200);
    }

    public function test_if_admin_does_not_have_permission_can_not_access_to_url()
    {

        $admin = Admin::factory()->create();

        $permission = $this->createPermission('edit.tickes');

        $admin->givePermissionTo($permission->name);

        $response = $this->actingAs($admin, 'admin')

            ->get('/add-tickes');

        $response->assertStatus(403);
    }


    private function createPermission($name, $guard = 'admin')
    {
        return  Permissions::new()->firstOrCreate(['name' => $name], ['name' => $name, 'guard_name' => $guard]);
    }
}