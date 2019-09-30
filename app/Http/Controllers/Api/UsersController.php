<?php

namespace App\Http\Controllers\Api;

use App\User;
use App\Support\RJ;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Requests\User\ChangePasswordRequest;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Jobs\User\ChangePassword as ChangeUserPasswordJob;
use App\Jobs\User\UpdateUserProfile as UpdateUserProfileJob;

class UsersController extends Controller
{
	/**
     * @var UserRepositoryInterface
     */
    protected $repo;

    /**
     * @param UserRepositoryInterface $userRepo
     */
    public function __construct( UserRepositoryInterface $userRepo)
    {
        $this->repo = $userRepo;
    }

    /**
     * Method to get the current logged in user data
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        $user = auth()->user();
        $user->image = $user->image ? $user->image : null;
        return response()->json(new UserResource($user));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  User  $user
     * @param  \App\Http\Requests\User\UpdateUserRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(User $user, UpdateUserRequest $request)
    {
        $attributes = $request->only(
            [
                'first_name',
                'last_name',
                'email',
                'job_title',
                'image',
            ]
        );

        $userUpdate = dispatch_now(new UpdateUserProfileJob($user, $attributes));
        if ($userUpdate !== false ) {
            return response()->json(new UserResource($user->refresh()));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  User  $user
     * @param  \App\Http\Requests\User\ChangePasswordRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changePassword(User $user, ChangePasswordRequest $request)
    {
        $changePassword = dispatch_now(new ChangeUserPasswordJob($user, $request));
        if ($changePassword !== false ) {
            return response()->json(new UserResource($user->refresh()));
        }

        return response()->json(['message' => __('Unable to change password.')], 400);
    }


}
