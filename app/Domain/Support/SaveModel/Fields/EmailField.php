<?php


namespace App\Domain\Support\SaveModel\Fields;

use Illuminate\Support\Facades\Validator;

class EmailField extends Field
{

    public function execute()
    {
        if (!$this->value) {

            return $this->value;
        }

        $validValue = Validator::make([$this->value], ['email']);

        if ($validValue->fails()) {

            return null;
        }

        return $this->value;
    }
}
