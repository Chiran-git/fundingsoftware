<?php

namespace App\Http\Controllers\Api\Donor;

use Auth;
use Mail;
use App\Donor;
use App\Donation;
use App\Organization;
use App\CampaignUser;
use App\Mail\EmailDonor;
use Illuminate\Http\Request;
use Illuminate\Bus\Queueable;
use App\Http\Controllers\Controller;
use App\Http\Resources\DonorResource;
use App\Http\Resources\DonationResource;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Jobs\Donor\Create as CreateDonorJob;
use App\Http\Requests\Donor\EmailDonorRequest;
use App\Http\Requests\Donor\CreateDonationRequest;
use App\Repositories\Contracts\DonorRepositoryInterface;
use App\Repositories\Contracts\DonationRepositoryInterface;

class DonorsController extends Controller
{
    use Queueable;

    /**
    * @var DonationRepositoryInterface
    */
    protected $donationRepo;

    public function __construct (DonationRepositoryInterface $donationRepo)
    {
        $this->donationRepo = $donationRepo;
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
        DonorRepositoryInterface $repo
    ) {
        $this->authorize('view', $organization);

        return DonorResource::collection(
            $repo->getDonorsList($organization->id, config('pagination.limit'))
        );
    }

    /**
     * Method to create campaign for organization
     *
     * @param Organization $organization
     * @param CreateDonationRequest $request
     * @return void
     */
    public function store(Organization $organization, CreateDonationRequest $request)
    {
        $this->authorize('view', $organization);

        if ($donor = dispatch_now(new CreateDonorJob($organization, $request->all()))) {
            return response()->json(new DonorResource($donor->refresh()));
        }

        return response()->json(['message' => __('Unable to save the campaign.')], 400);
    }

    /**
     * Method to get recent donors
     *
     * @param Organization $organization
     * @return void
     */
    public function recentDonors(Organization $organization)
    {
        $this->authorize('view', $organization);

        if (Auth::user()->currentRole() == 'campaign-admin') {
            // Getting the campaigns assigned to the User
            $whereIn = $this->getAssignedCampaigns($organization);

            // Returning results of that particular user only
            return DonationResource::collection(
                $this->donationRepo->findAllWhere([
                    'organization_id' => $organization->id,
                ])
                ->whereIn('campaign_id', $whereIn)
                ->sortByDesc('created_at')
            );
        } else {
            return DonationResource::collection(
                $this->donationRepo->findAllWhere([
                    'organization_id' => $organization->id
                ])->sortByDesc('created_at')
            );
        }
    }

    /**
     * Method to get top donors
     *
     * @param Organization $organization
     * @return void
     */
    public function topDonors(Organization $organization)
    {
        $this->authorize('view', $organization);

        if (Auth::user()->currentRole() == 'campaign-admin') {
            // Getting the campaigns assigned to the User
            $whereIn = $this->getAssignedCampaigns($organization);

            return DonationResource::collection(
                $this->donationRepo->findAllWhere([
                    'organization_id' => $organization->id
                ])
                ->whereIn('campaign_id', $whereIn)
                ->sortByDesc('gross_amount')
            );

        } else {
            return DonationResource::collection(
                $this->donationRepo->findAllWhere([
                    'organization_id' => $organization->id
                ])->sortByDesc('gross_amount')
            );
        }
    }

    /**
     * Method to get assigned campaigns
     *
     * @param Organization $organization
     * @return array
     */
    public function getAssignedCampaigns(Organization $organization)
    {
        $whereIn = [];

        $campaigns = CampaignUser::where([
            'organization_id' => $organization->id,
            'user_id' => Auth::user()->id
        ])->select('campaign_id')->get();

        foreach ($campaigns as $campaign) {
            $whereIn[] = $campaign->campaign_id;
        }

        return $whereIn;
    }

    /**
     * Method to send email to the donor
     *
     * @param Organization $organization
     * @param Donor        $donor
     *
     * @return array
     */
    public function emailDonor(Organization $organization, Donor $donor, EmailDonorRequest $request)
    {
        $this->authorize('view', $donor);

        $content = $request->get('message');
        $subject = $request->get('subject');

        $message = (new EmailDonor($organization, $content, $subject, $request->user(), $donor))
                    ->onQueue('email');

        $result = Mail::to($donor->email)
                    ->queue($message);
    }
}
