<?php

namespace Tests\Feature\Repositories\Admin;

use App\Repositories\Admin\AdminInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AdminRepositoryTest extends TestCase
{


    public function testAdminRepositoryCanReturnData()
    {
        $admins = app(AdminInterface::class)->getAdmins();

        $this->assertNotEmpty($admins);
    }

    public function testAdminRepositoryCanReturnOneObject()
    {
        $admin = app(AdminInterface::class)->getAdmin(1);

        $this->assertModelExists($admin);
    }

    public function testAdminRepositoryCanAddObject()
    {
        $admin = app(AdminInterface::class)->__instance();

        $user = $admin->factory()->create();

        $this->assertModelExists($user);
    }

    public function testAdminRepositoryCanDeleteObject()
    {
        $admin = app(AdminInterface::class)->getAdmin(rand(1, 100));

        $admin->delete();

        $this->assertDeleted($admin);
    }
}
