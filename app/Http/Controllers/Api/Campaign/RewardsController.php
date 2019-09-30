<?php

namespace App\Http\Controllers\Api\Campaign;

use App\Campaign;
use App\Organization;
use App\CampaignReward;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CampaignRewardResource;
use App\Http\Requests\Campaign\StoreRewardRequest;
use App\Jobs\Campaign\CreateReward as CreateCampaignRewardJob;
use App\Jobs\Campaign\UpdateReward as UpdateCampaignRewardJob;
use App\Repositories\Contracts\CampaignRewardRepositoryInterface;

class RewardsController extends Controller
{
    /**
     * @var CampaignRewardRepositoryInterface
     */
    private $repo;

    /**
     * @param CampaignRewardRepositoryInterface $campaignRewardRepo
     */
    public function __construct(CampaignRewardRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    /**
     * Display a listing of the rewards.
     *
     * @param \App\Organization $organization
     * @param \App\Campaign $campaign
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Organization $organization, Campaign $campaign)
    {
        $this->authorize('view', [$campaign, $organization]);

        return CampaignRewardResource::collection(
            $this->repo->findAllWhere([
                'organization_id' => $organization->id, 
                'campaign_id' => $campaign->id
            ])
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created reward in campaign.
     *
     * @param \App\Organization $organization
     * @param \App\Campaign $campaign
     * @param \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Organization $organization, Campaign $campaign, StoreRewardRequest $request)
    {
        $campaignReward = dispatch_now(new CreateCampaignRewardJob($organization, $campaign, $request->all()));

        return response()->json(new CampaignRewardResource($campaignReward->refresh()));
    }

    /**
     * Display the specified resource.
     *
     * @param  Organization $organization
     * @param  Campaign $campaign
     * @param  CampaignReward $reward
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(
        Organization $organization, 
        Campaign $campaign, 
        CampaignReward $reward
    ) {
        $this->authorize('view', [$campaign, $organization]);

        return response()->json(new CampaignRewardResource($reward));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the campaign reward.
     *
     * @param  Organization $organization
     * @param  Campaign $campaign
     * @param  CampaignReward $reward
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(
        Organization $organization, 
        Campaign $campaign, 
        CampaignReward $reward, 
        StoreRewardRequest $request
    ) {   

        $attributes = $request->only(
            [
                'title',
                'description',
                'min_amount',
                'quantity',
                'image',
            ]
        );  

        $updateReward = dispatch_now(new UpdateCampaignRewardJob($organization, $reward, $attributes));

        if ($updateReward !== false) {
            return response()->json(
                new CampaignRewardResource($reward->refresh())
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Organization $organization
     * @param  Campaign $campaign
     * @param  $reward
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(
        Organization $organization, 
        Campaign $campaign, 
        $reward
    ) {
        $this->authorize('update', [$campaign, $organization]);

        // Removing route model binding to tackle updation anomaly 
        $reward = CampaignReward::find($reward);
        
        if ($reward) {
            if ($reward->quantity_rewarded) {
                return response()->json(['message' => __('Unable to delete reward')], 400);
            } else {
                //delete  reward
                if ($this->repo->delete($reward->id)) {
                    return response()->json([]);
                }
            }
        } else {
            return response()->json([]);
        }
    }
}
