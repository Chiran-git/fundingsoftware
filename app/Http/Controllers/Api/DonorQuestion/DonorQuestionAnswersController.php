<?php

namespace App\Http\Controllers\Api\DonorQuestion;

use App\Donor;
use App\Donation;
use App\Organization;
use App\DonorQuestion;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\DonorQuestionResource;
use App\Repositories\Contracts\DonorQuestionRepositoryInterface;


class DonorQuestionAnswersController extends Controller
{
    /**
     * Method to get a list of all donor questions for the given organization
     *
     * @param Organization $organization
     * @param Donor $donor
     * @param Request $request
     * @param DonorQuestionRepositoryInterface $repo
     *
     * @return AnonymousResourceCollection
     */
    public function index(
        Organization $organization,
        Donor $donor,
        Request $request,
        DonorQuestionRepositoryInterface $repo
    ) {
        $this->authorize('view', $organization);

        // Get the last donation id for which donation is made.
        $donation = Donation::select('id')
            ->where('donor_id', $donor->id)
            ->where('organization_id', $organization->id)
            ->orderBy('created_at', 'desc')
            ->first();
        if (isset($donation->id) && ! empty($donation->id)) {
            return DonorQuestionResource::collection(
                $repo->getDonorQuestionAnswers($organization->id, $donation->id)
            );
        }

        return response()->json([]);
    }


}
