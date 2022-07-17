<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Decimal implements Rule
{
    protected $precision, $scale;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($precision, $scale)
    {
        $this->precision = $precision;
        $this->scale = $scale;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return preg_match("/^[0-9]{1,{$this->precision}}(\.[0-9]{1,{$this->scale}})$/", $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute must be a number with two decimal places.';
    }
}
