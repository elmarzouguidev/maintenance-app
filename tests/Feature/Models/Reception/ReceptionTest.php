<?php

namespace Tests\Feature\Models\Reception;

use App\Models\Authentification\Reception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ReceptionTest extends TestCase
{

    public function testCanAddReception()
    {
        $user = new Reception();
        $user->nom = "abdo";
        $user->prenom = "merz";
        $user->telephone = "0677512753";
        $user->email = "abdo@gmail.com";
        $user->password = "123456789@";

        $this->assertTrue($user->nom === "abdo");
        $this->assertTrue($user->prenom === "merz");
        $this->assertTrue($user->telephone === "0677512753");
        $this->assertTrue($user->email === "abdo@gmail.com");
        $this->assertTrue($user->password === "123456789@");
    }
}
