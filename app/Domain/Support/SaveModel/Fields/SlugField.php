<?php

namespace App\Domain\Support\SaveModel\Fields;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
class SlugField extends Field
{

    public function execute(): string
    {
        if (! $this->value) {
            return $this->value;
        }

        return (string) Str::slug($this->value);
        
    }
}
