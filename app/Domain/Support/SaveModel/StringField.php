<?php


namespace App\Domain\Support\SaveModel;


class StringField extends Field
{

    public function execute()
    {
        return $this->value;
    }

}
