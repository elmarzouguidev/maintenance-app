<?php


namespace App\Domain\Support\SaveModel\Fields;


use Illuminate\Support\Carbon;

class DatetimeField extends Field
{

    public function execute(): string
    {
        if(!$this->value) {

            return $this->value;

        }

        return Carbon::parse($this->value)->toDateTimeString();
    }
}
