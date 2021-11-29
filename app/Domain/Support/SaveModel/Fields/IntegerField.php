<?php


namespace App\Domain\Support\SaveModel\Fields;


class IntegerField extends Field
{

    public function execute(): int
    {
        return  (integer) $this->value;
    }
}
