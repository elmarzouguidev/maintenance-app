<?php

declare(strict_types=1);

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class DecimalRule implements Rule
{
    /**
     * @return void
     */
    public function __construct(
        //
    ) {}

    /**
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {

        if (preg_match('/^(([0-9]*)(\.([0-9]{0,2}+))?)$/', $value)) {
            return true;
        }
        return false;
    }

    /**
     * @return string
     */
    public function message(): string
    {
        return 'The validation error message.';
    }
}
