<?php

namespace App\Domain\Support\SaveModel\Fields;

use Illuminate\Support\Str;

class ArrayField extends Field
{

    public function execute(): array
    {

        return (array) $this->value;
    }

}
