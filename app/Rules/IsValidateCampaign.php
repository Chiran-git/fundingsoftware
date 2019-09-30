<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class IsValidateCampaign implements Rule
{
    /**
     * All request data
     *
     * @var array
     */
    private $requestData;

    /**
     * Organization
     *
     * @var array
     */
    private $organization;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($requestData, $organization)
    {
        $this->requestData = $requestData;
        $this->organization = $organization;
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
        $role = strtolower($this->requestData['role']);

        if ($role == 'campaign-admin' && empty($value)) {
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Please assign campaign.';
    }
}
