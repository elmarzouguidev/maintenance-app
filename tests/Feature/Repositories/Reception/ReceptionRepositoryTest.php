<?php

namespace Tests\Feature\Repositories\Reception;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ReceptionRepositoryTest extends TestCase
{


    public function test_hello()
    {
        $rep =   $this->get('/');
        $rep->assertOk();
    }
}
