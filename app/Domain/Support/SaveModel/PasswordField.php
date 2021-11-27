<?php


namespace App\Domain\Support\SaveModel;


use Illuminate\Support\Facades\Hash;

class PasswordField extends Field
{

    public function execute(): string
    {
        if (! $this->value) {
            return $this->value;
        }

        return Hash::make($this->value);
    }
}
