<?php

namespace Tests\Feature\Site;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
class SiteTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    /*public function test_example()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }*/

    public function testThatIndexReturnHello()
    {
        $res = $this->get('/tester');

        $res->assertSeeText('40');
    }

    public function testThatUrlNotExists()
    {

        $res = $this->get('/hee');

        $res->assertNotFound();
    }

}
