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
}
