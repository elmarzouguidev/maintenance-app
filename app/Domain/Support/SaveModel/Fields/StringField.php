<?php


namespace App\Domain\Support\SaveModel\Fields;


class StringField extends Field
{

    public function execute(): string
    {
        return (string) $this->value;
    }

}
