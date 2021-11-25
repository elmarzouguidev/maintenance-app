<?php

namespace Tests\Feature\Repositories\Admin;

use App\Collections\Admin\AdminCollection;
use App\Models\Authentification\Admin;
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

        $this->assertIsObject($admins);
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
