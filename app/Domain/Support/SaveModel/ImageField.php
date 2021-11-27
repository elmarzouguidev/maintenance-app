<?php


namespace App\Domain\Support\SaveModel;


class ImageField extends Field
{

    public function execute()
    {
        if(!$this->value) {

            return $this->value;

        }
        return $this->value->store('images');
    }
}
