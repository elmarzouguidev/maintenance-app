<?php

namespace Tests\Feature\Redirection;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AdminPanelRedirectionTest extends TestCase
{
 
    public function test_app_url_redirect_to_admin_url()
    {
       $response = $this->get('/app');

       $response->assertRedirectContains('/admin');

    }

    public function test_admin_url_is_protected_with_middleware()
    {
        $response = $this->get('app/admin');
        $response->assertStatus(302);
    }

    public function test_technicien_url_redirect_to_login_url()
    {
       $response = $this->get('/technicien');

       $response->assertRedirectContains('/technicien/login');

    }

    public function test_technicien_url_is_protected_with_middleware()
    {
        $response = $this->get('technicien');
        $response->assertStatus(302);
    }


}
