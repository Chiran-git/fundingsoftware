<?php

namespace App\Http\Controllers\Api\Organization;

use App\User;
use App\Support\RJ;
use App\Organization;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Repositories\Contracts\OrganizationRepositoryInterface;
use App\Jobs\Organization\UpdateUser as UpdateAccountUserJob;

class UserController extends Controller
{
	/**
     * @var UserRepositoryInterface
     */
    protected $repo;

    /**
     * @param UserRepositoryInterface $userRepo
     */
    public function __construct (OrganizationRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Organization $organization
     * @param  User  $user
     * @param  \App\Http\Requests\User\UpdateUserRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update (Organization $organization, User $user, Request $request)
    {
        $userUpdate = dispatch_now(new UpdateAccountUserJob($organization, $user, $request));
        if ($userUpdate !== false) {
            return response()->json(new UserResource($user->refresh()));
        }

        return response()->json(['message' => __('Bad Request')], 400);
    }
}
