<?php

namespace App\Http\Controllers\Api\Admin;

use App\User;
use App\OrganizationUser;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Http\Controllers\Controller;
use App\Jobs\Admin\Create as CreateAdminJob;
use App\Jobs\Admin\Delete as DeleteAdminJob;
use App\Jobs\Admin\Update as UpdateAdminJob;
use App\Http\Requests\Admin\CreateAdminRequest;
use App\Http\Requests\Admin\UpdateAdminRequest;
use App\Repositories\Contracts\UserRepositoryInterface;

class AdminController extends Controller
{
    /**
     * @var UserRepositoryInterface
     */
    private $repo;

    /**
     * @param UserRepositoryInterface $repo
     */
    public function __construct (UserRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

	/**
     * Method to list admins
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index (Request $request)
    {
    	$this->authorize('viewAny', $request->user());

        return $this->repo->getAdminUsersList(config('pagination.limit'));
    }

    /**
     * Method to create admin
     *
     * @param CreateAdminRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateAdminRequest $request)
    {
        $this->authorize('viewAny', $request->user());
        $admin = dispatch_now(new CreateAdminJob($request->all()));

        return response()->json(new UserResource($admin));
    }

    /**
     * Method to show single admin
     *
     * @param User $user
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(User $user, Request $request)
    {
        $this->authorize('viewAny', $request->user());
        return response()->json(new UserResource($user));
    }

    /**
     * Method to update admin
     *
     * @param User $user
     * @param UpdateAdminRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(User $user, UpdateAdminRequest $request)
    {
        $attributes = $request->only(
            [
                'first_name',
                'last_name',
                'email',
                'image',
            ]
        );

        $this->authorize('viewAny', $request->user());
        $adminUpdate = dispatch_now(new UpdateAdminJob($user, $attributes));

        if ($adminUpdate !== false ) {
            return response()->json(new UserResource($user->refresh()));
        }
    }

    /**
     * Method to delete admin
     *
     * @param User $user
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(User $user, Request $request)
    {
        $this->authorize('viewAny', $request->user());
        $admin = dispatch_now(new DeleteAdminJob($user));
        if ($admin) {
            return response()->json([]);
        } else {
            return response()->json(['message' => __('Unable to delete admin.')], 400);
        }
    }
}
