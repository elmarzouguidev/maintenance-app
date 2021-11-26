<?php

namespace Tests\Feature\Config;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class AppConfigTest extends TestCase
{


    public function testIfConfigFileExists()
    {
        $path = config_path('app-config.php');

        $this->assertFileExists($path);
    }

    public function testConfigFileHasSameKeys()
    {
        $path = config_path('app-config.php');

        if(File::exists($path))
        {
           $this->assertArrayHasKey('cache',config('app-config'));
        }else {
            $this->assertFileDoesNotExist($path);
        }

    }

    public function testConfigFileDoesntHasAKey()
    {
        $path = config_path('app-config.php');

        if(File::exists($path))
        {
            $this->assertArrayNotHasKey('balabla',config('app-config'));
        }
        else {
            $this->assertFileDoesNotExist($path);
        }
    }

}
