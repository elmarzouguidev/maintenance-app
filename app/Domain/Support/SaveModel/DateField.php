<?php


namespace App\Domain\Support\SaveModel;


use Illuminate\Support\Carbon;

class DateField extends Field
{

    public function execute(): mixed
    {
        if (! $this->value) {
            return $this->value;
        }

        return Carbon::parse($this->value)->toDateString();
    }
}
