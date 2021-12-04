<?php

namespace App\Domain\Support\Helpers;

class DomainHelpers
{


    public static function new()
    {
        return new self;
    }

    
    public function getHelpers()
    {
        return "Hello Helpers";
    }


}
