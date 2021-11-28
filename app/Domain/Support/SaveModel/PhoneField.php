<?php

namespace App\Domain\Support\SaveModel;

use App\Domain\Support\SaveModel\Exception\PhoneFieldException;
use Illuminate\Support\Facades\Validator;

class PhoneField extends Field
{

    public function execute()
    {
        if (!$this->value) {
            return $this->value;
        }

        $validValue = Validator::make([$this->value], ['phone:MA']);

        if ($validValue->fails()) {
            throw new PhoneFieldException('Sorry the phone number is not a moroccan number');
        }

        return $this->value;
    }
}
