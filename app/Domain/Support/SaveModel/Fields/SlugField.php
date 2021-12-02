<?php

namespace App\Domain\Support\SaveModel\Fields;

use Illuminate\Support\Str;

class SlugField extends Field
{

    public function execute(): string
    {
        return (string) Str::slug($this->value);
    }
}
