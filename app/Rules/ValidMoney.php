<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidMoney implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value) : bool
    {
        $moneyPattern = '/^\d{1,10}(.\d{1,2})?$/';
        return preg_match($moneyPattern, $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message() : string
    {
        return 'The :attribute field is not valid.';
    }
}
