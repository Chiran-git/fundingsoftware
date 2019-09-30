<?php

namespace App\Rules;

use App\User;
use Illuminate\Contracts\Validation\Rule;
use App\Repositories\Contracts\UserRepositoryInterface;

class IsOrganizationAdmin implements Rule
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


    private $message;


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
        $userRepo = app(UserRepositoryInterface::class);

        if ($user = $userRepo->findWhere(['email' => $value])) {

            //get current organization of user
            if (! $userOrganization = $user->currentOrganization()) {
                return true;
            }
            //get user role in the organization
            $role = $userOrganization->pivot->role;

            //check assigned organization
            if ($userOrganization->id != $this->organization->id) {
                $this->message = "User belongs to other organization!";
                return false;

            } /*elseif (($role == 'admin' || $role == 'owner') && ($userOrganization->id == $this->organization->id)) {
                $this->message = 'User already has admin access';
                return false;
            }*/
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
        if ($this->message) {
            return $this->message;
        }
        return __('The validation error message.');
    }
}
