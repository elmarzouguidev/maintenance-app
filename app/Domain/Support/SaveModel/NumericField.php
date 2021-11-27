<?php


namespace App\Domain\Support\SaveModel;


class NumericField extends Field
{

    public function execute()
    {
        return $this->value;
    }
}
