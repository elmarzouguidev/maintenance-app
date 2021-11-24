<?php

namespace Tests\Feature\Middleware\Admin;

use App\Models\Authentification\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AdminMiddlewareTest extends TestCase
{


    public function testAccessWithVerification()
    {
        // Create a dummy user, but this time we set the email_verified_at
        $user = Admin::factory()->verified()->create();

        // Try to access the page
        $response = $this->actingAs($user,'admin')
            ->get('/settings');
        // Assert the expected response status
        $response->assertStatus(200);
    }


    public function testAccessWithoutVerification()
    {
        //$this->withoutExceptionHandling();
        // Create a dummy user
        $userr = Admin::factory()->unverified()->create();

        // Try to access the page
        $response = $this->actingAs($userr)
            ->get('/settings');


        // Assert the expected response status
        $response->assertStatus(200);
    }

    /** @test */
    public function when_not_authenticated_it_redirects_to_the_login_route()
    {
        $this->get('/admins')->assertRedirect(route('login'));
    }


}
