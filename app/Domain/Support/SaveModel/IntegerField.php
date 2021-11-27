<?php


namespace App\Domain\Support\SaveModel;


class IntegerField extends Field
{

    public function execute()
    {
        return $this->value;
    }
}
