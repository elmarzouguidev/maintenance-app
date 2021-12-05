<?php

namespace App\Helpers;


class Helper
{

    use FrontHelpers;
    use BackHelpers;
    use CalculatorHelpers;


    public static function new()
    {
        return new self;
    }

    public function getName(): string
    {
        return "Abdelghafour Elmarzougui";
    }

}
