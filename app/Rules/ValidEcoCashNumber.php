<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidEcoCashNumber implements Rule
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
        $ecocashNumberPattern = '/^((\+|00)?263|0)?7(7|8)\d{7}$/';
        return preg_match($ecocashNumberPattern, $value);
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
