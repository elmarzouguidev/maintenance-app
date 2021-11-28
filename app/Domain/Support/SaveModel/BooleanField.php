<?php


namespace App\Domain\Support\SaveModel;


class BooleanField extends Field
{

    public function execute(): bool
    {
        return in_array($this->value, [1, '1', true, 'on', 'yes']);
    }
}