<?php

namespace App\Domain\Support\SaveModel\Fields;


class NumericField extends Field
{

    public function execute(): int
    {
        return (integer) $this->value;
    }
}
