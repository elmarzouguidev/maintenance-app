<?php


namespace App\Domain\Support\SaveModel\Fields;


class BooleanField extends Field
{

    public function execute(): bool
    {
        return (bool) in_array($this->value, [1, '1', true, 'on', 'yes']);
    }
}
