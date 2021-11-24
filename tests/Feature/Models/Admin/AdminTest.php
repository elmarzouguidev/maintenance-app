<?php

namespace Tests\Feature\Models\Admin;

use App\Models\Authentification\Admin;
use App\Models\Authentification\Technicien;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AdminTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function testThatAdminCollectionsExists()
    {
        $admins = Admin::all()->groupByPosition();

        $this->assertArrayHasKey('SuperAdmins', $admins);
        $this->assertArrayHasKey('NormalAdmins', $admins);
    }

    public function testThatAdminCollectionsCountable()
    {
        $admins = Admin::all()->groupByPosition();
        $this->assertCount(10, $admins['SuperAdmins']); // take attention this number is changed when you add to table
        $this->assertCount(45, $admins['NormalAdmins']);// take attention this number is changed when you add to table
    }

    public function testThatShouldBeLoggedInToVisitUrl()
    {
        $response = $this->get('/theadmin');

        $response->assertStatus(500);
    }

    public function testThatAdminsCanBeLoggedInAsAdmin()
    {

        $admin = Admin::first();
        $this->actingAs($admin,'admin')
            ->assertAuthenticatedAs($admin,'admin');
    }
    public function testThatAdminsCanNotBeLoggedInAsAdmin()
    {

        $admin = Admin::first();
        $this->actingAs($admin,'admin')
            ->assertAuthenticated('admin');
    }

    public function testThatOnlyAdminCanVisitSpeceficUrl()
    {
        $user = Technicien::first();
        $this->actingAs($user,'technicien')
            ->get('/dashboard')
            ->assertStatus(500);
    }

    public function testThatAdminCanNotVisitSpeceficUrl()
    {
        $user = Admin::first();
        $this->actingAs($user,'admin')
            ->withMiddleware('auth:admin')

            ->get('/theadmin')

            ->assertStatus(500);
    }

    public function testThatAdminCanSeeResponse()
    {
        $user = Admin::first();
        $this->actingAs($user,'admin')
            ->get('/dashboard')
            ->assertSeeText('hello admins');
    }
}
