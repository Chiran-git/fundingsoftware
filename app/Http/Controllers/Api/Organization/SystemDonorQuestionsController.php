<?php

namespace App\Http\Controllers\Api\Organization;

use App\Organization;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Organization\SystemDonorQuestionsRequest;
use App\Repositories\Contracts\OrganizationRepositoryInterface;

class SystemDonorQuestionsController extends Controller
{
    /**
     * @var OrganizationRepositoryInterface
     */
    private $repo;

    /**
     * @param UserRepositoryInterface $userRepo
     */
    public function __construct(OrganizationRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    /**
     * Method to update the organization's system donor questions
     *
     * @param Organization $organization
     * @param SystemDonorQuestionsRequest $request
     * @return void
     */
    public function update(
        Organization $organization,
        SystemDonorQuestionsRequest $request
    ) {
        if ($this->repo->updateSystemDonorQuestions($organization->id, $request->all())) {
            return response()->json([]);
        }

        return response()->json(['message' => __('Unable to save data.')], 400);
    }
}
