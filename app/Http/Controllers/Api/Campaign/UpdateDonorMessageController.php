<?php

namespace App\Http\Controllers\Api\Campaign;

use App\Campaign;
use App\Organization;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Campaign\StoreDonorMessageRequest;
use App\Repositories\Contracts\CampaignRepositoryInterface;

class UpdateDonorMessageController extends Controller
{
    /**
     * @var CampaignRepositoryInterface
     */
    private $repo;

    /**
     * @param \App\Repositories\Contracts\CampaignRepositoryInterface $repo
     */
    public function __construct(CampaignRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    /**
     * Method to update the campaign's donor message
     *
     * @param Organization             $organization
     * @param Campaign                 $campaign
     * @param StoreDonorMessageRequest $request
     * @return void
     */
    public function update(
        Organization $organization,
        Campaign $campaign,
        StoreDonorMessageRequest $request
    )
    {
        //set attribute
        $attributes = [
            'donor_message' => $request->donor_message
        ];

        //update donor message
        if ($this->repo->update($campaign->id, $attributes) !== false) {

            return response()->json([]);
        }

        return response()->json(['message' => __('Unable to save donor message')], 400);
    }
}
