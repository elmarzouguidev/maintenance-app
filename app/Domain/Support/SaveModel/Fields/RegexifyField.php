<?php

namespace App\Domain\Support\SaveModel\Fields;

use Illuminate\Support\Carbon;

class RegexifyField extends Field
{

    public function execute(): string
    {
        if (! $this->value) {
            return $this->value;
        }

        return Carbon::parse($this->value)->toTimeString();
    }
}
