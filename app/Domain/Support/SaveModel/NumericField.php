<?php


namespace App\Domain\Support\SaveModel;


class NumericField extends Field
{

    public function execute(): int
    {
        return (integer) $this->value;
    }
}
