<?php

namespace App\Http\Controllers\Api\Admin\Donation;

use App\Campaign;
use App\Donation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\DonationResource;
use App\Repositories\Contracts\DonationRepositoryInterface;

class DonationsController extends Controller
{

    /**
     * @var DonationRepositoryInterface
     */
    private $repo;

    /**
     * @param DonationRepositoryInterface $repo
     */
    public function __construct (DonationRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    /**
     * Method to get donations for the particular campaign
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDonations(Campaign $campaign, Request $request)
    {
        $start_date = $this->convertToUtc($request->start_date, $request->timezone);
        $end_date = $this->convertToUtc($request->end_date, $request->timezone);

        return DonationResource::collection(
                Donation::where(
                [
                    ['campaign_id', '=', $campaign->id],
                    ['entry_type', '=', 'online'],
                ])
                ->whereNull('payout_id')
                ->whereNull('payout_connected_account_id')
                ->whereBetween('created_at', [$start_date, $end_date])
                ->get()
            );
    }

    /**
     * Method to convert to UTC
     *
     * @param $date
     * @param $timezone
     *
     * @return Carbon\Carbon
     */
    private function convertToUtc($date, $timezone)
    {
        $newdate = Carbon::createFromFormat('Y-m-d', $date, $timezone);
        $newdate->setTimezone('UTC');
        return $newdate;
    }
}
