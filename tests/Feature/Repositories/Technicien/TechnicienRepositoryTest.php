<?php

namespace Tests\Feature\Repositories\Technicien;

use App\Models\Authentification\Technicien;
use App\Repositories\Technicien\TechnicienInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TechnicienRepositoryTest extends TestCase
{
    public function testTechnicienRepositoryCanReturnData()
    {
        $techniciens = app(TechnicienInterface::class)->getTechniciens();

        $this->assertNotEmpty($techniciens);

        $this->assertIsObject($techniciens);
    }

    public function testTechnicienRepositoryCanReturnOneObject()
    {
        $technicien = app(TechnicienInterface::class)->getFirst();

        $this->assertModelExists($technicien);
    }

    public function testTechnicienRepositoryCanAddObject()
    {
        $technicien = app(TechnicienInterface::class)->__instance();

        $user = $technicien->factory()->create();

        $this->assertModelExists($user);
    }

    public function testTechnicienRepositoryCanDeleteObject()
    {
        Technicien::factory(20)->create();

        $technicien = app(TechnicienInterface::class)->getFirst();

        $technicien->delete();

        $this->assertDeleted($technicien);
    }
}
