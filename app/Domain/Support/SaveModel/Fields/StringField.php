<?php

namespace App\Domain\Support\SaveModel\Fields;

use Illuminate\Support\Str;

class StringField extends Field
{

    private bool $isColumnSlug = false;


    public function execute(): string
    {
        if($this->isColumnSlug)
        {

            return (string) Str::slug($this->value);
        }
        return (string) $this->value;
    }

    public function isSlug(): StringField
    {
        $this->isColumnSlug = true;

        return $this;
    }

}
