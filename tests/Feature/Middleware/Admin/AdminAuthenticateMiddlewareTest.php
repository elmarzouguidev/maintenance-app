<?php

namespace Tests\Feature\Middleware\Admin;

use App\Models\Authentification\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class AdminAuthenticateMiddlewareTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Route::get('login')->name('login');

        $this->withExceptionHandling();
    }

    /** @test */
    public function when_not_authenticated_it_redirects_to_the_login_route()
    {
        $this->get(route('dashboard'))->assertRedirect(route('login'));
    }

    /** @test */
    public function when_authenticated_it_can_view_the_dashboard_ui()
    {
        $this->authenticate();

        $this->get(route('dashboard'))->assertSuccessful();
    }

    /** @test */
    public function it_will_redirect_to_the_login_page_when_authenticated_with_the_wrong_guard()
    {
       // config()->set('mailcoach.guard', 'api');

        $this->authenticate('web');

        $this->get(route('technicien'))->assertRedirect(route('login'));
    }

    /** @test */
    public function when_authenticated_with_the_right_guard_it_can_view_the_dashboard_ui()
    {
       // config()->set('mailcoach.guard', 'api');

        $this->authenticate('admin');

        $this->get(route('admins'))->assertSuccessful();
    }

    public function authenticate(string $guard = 'admin')
    {
        $user = Admin::factory()->create();

        $this->actingAs($user, $guard);
    }
}
