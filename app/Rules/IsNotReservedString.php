<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class IsNotReservedString implements Rule
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
        $reserved = $this->getAllRouteUris();

        // If it's a reserved word (i.e. one of the app URIs)
        if (in_array(strtolower($value), $reserved)) {
            return false;
        }

        return true;
    }

    /**
     * Method to get all registered URIs
     *
     * @return array Array with all URIs
     */
    private function getAllRouteUris()
    {
        $return = [];

        $routes = app()->routes->getRoutes();

        foreach ($routes as $route) {
            $return[] = $route->uri;
        }

        return $return;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('This a reserved string. Please use something else.');
    }
}
