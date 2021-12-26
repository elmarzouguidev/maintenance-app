<?php

namespace App\Domain\Support\SaveModel\Fields;

use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class UuidField extends Field
{

    public function execute(): string
    {
        if (!$this->value) {
            return $this->value;
        }

        return (string) $this->value = Str::uuid();
    }
}
