<?php


namespace App\Domain\Support\SaveModel;


use Illuminate\Support\Facades\Hash;

class PhoneField extends Field
{

    public function execute()
    {
        if (! $this->value) {
            return $this->value;
        }

        return Hash::make($this->value);
    }
}
