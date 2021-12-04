<?php

namespace Tests\Feature\Roles;

use App\Models\Authentification\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AdminRolesTest extends TestCase
{

    public function test_give_role_to_admin()
    {
        $admin = Admin::factory()->create();

        $admin->assignRole('writer');

        $this->assertDatabaseHas('model_has_roles', ['model_id' => $admin->id, 'role_id' => 1]);
    }
}
