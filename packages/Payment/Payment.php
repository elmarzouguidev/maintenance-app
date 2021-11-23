<?php

namespace Elmarzougui\Payment;

class Payment
{

    /***** Call Static *****/
    public static function _payment()
    {
        return new self;
    }

    public function getPayment()
    {
        return "Hello from pyament class";
    }
}
