<?php

namespace App\Http\Controllers\Api\DonorQuestion;

use App\Organization;
use App\DonorQuestion;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\DonorQuestionResource;
use App\Http\Requests\DonorQuestion\StoreDonorQuestionRequest;
use App\Repositories\Contracts\DonorQuestionRepositoryInterface;

class DonorQuestionsController extends Controller
{
    /**
     * Method to add a new donor question
     *
     * @param Organization $organization
     * @param StoreDonorQuestionRequest $request
     * @param DonorQuestionRepositoryInterface $repo
     *
     * @return mixed
     */
    public function store(
        Organization $organization,
        StoreDonorQuestionRequest $request,
        DonorQuestionRepositoryInterface $repo
    ) {
        $attributes = [
            'organization_id' => $organization->id,
            'question' => $request->question,
            'type' => 'text',
            'sort_order' => $repo->getMaxSortOrder($organization) + 1,
        ];

        if ($question = $repo->store($attributes)) {
            return new DonorQuestionResource($question);
        }

        return response()->json(['message' => __('Unable to save custom field')], 400);
    }

    /**
     * Method to get a list of all donor questions for the given organization
     *
     * @param Organization $organization
     * @param Request $request
     *
     * @return AnonymousResourceCollection
     */
    public function index(
        Organization $organization,
        Request $request,
        DonorQuestionRepositoryInterface $repo
    ) {
        $this->authorize('view', $organization);

        return DonorQuestionResource::collection(
            $repo->findAllWhere(['organization_id' => $organization->id])
        );
    }

    /**
     * Method to delete donor question for the given organization
     *
     * @param Organization $organization
     * @param DonorQuestion $donorQuestion
     * @param DonorQuestionRepositoryInterface $repo
     *
     * @return void
     */
    public function destroy(
        Organization $organization,
        DonorQuestion $donorQuestion,
        DonorQuestionRepositoryInterface $repo
    ) {
        $this->authorize('delete', $organization);

        //delete  donor question
        if ($repo->delete($donorQuestion->id)) {
            return response()->json([]);
        }
        return response()->json(['message' => __('Unable to delete custom field')], 400);
    }


    /**
     * Method to update donor question for the given organization
     *
     * @param Organization $organization
     * @param DonorQuestion $donorQuestion
     * @param DonorQuestionRepositoryInterface $repo
     * @return void
     */
    public function update(
        Organization $organization,
        DonorQuestion $donorQuestion,
        StoreDonorQuestionRequest $request,
        DonorQuestionRepositoryInterface $repo
    ) {
        //set attribute
        $attributes = [
            'question' => $request->question,
            'is_required' => $request->is_required ? $request->is_required : false,
            'disabled_at' => $request->enabled ? null : now(),
            'disabled_by_id' => $request->enabled ? null : auth()->user()->id,
        ];

        //update donor question
        if ($repo->update($donorQuestion->id, $attributes) !== false) {
            //find and return updated donor
            return new DonorQuestionResource($repo->find($donorQuestion->id));
        }

        return response()->json(['message' => __('Unable to save custom field')], 400);
    }
}
