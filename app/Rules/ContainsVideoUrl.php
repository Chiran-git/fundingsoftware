<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ContainsVideoUrl implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
        $parts = parse_url($value);

        if (! isset($parts['host'])) {
            return false;
        }

        $host = $parts['host'];

        if ($host == 'youtube.com' || $host == 'www.youtube.com' || $host == 'vimeo.com' || $host == 'www.vimeo.com') {
            return true;
        }
        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('Only youtube and vimeo urls are allowed.');
    }
}
