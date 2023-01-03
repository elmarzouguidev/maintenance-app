<?php

namespace App\Helpers;

trait FrontHelpers
{
    public function tryHello(): string
    {
        return 'Hello Ticket Application';
    }

    public function calculateNumber()
    {
        return 5 * 5;
    }
}
