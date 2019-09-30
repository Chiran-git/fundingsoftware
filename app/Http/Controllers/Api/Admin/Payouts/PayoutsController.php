<?php

namespace App\Http\Controllers\Api\Admin\Payouts;

use App\Donation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\PayoutResource;
use App\Http\Resources\DonationResource;
use App\Http\Requests\Payout\RecordPayoutRequest;
use App\Jobs\Payout\Create as CreateAdminPayoutJob;
use App\Repositories\Contracts\PayoutRepositoryInterface;

class PayoutsController extends Controller
{
    /**
    * @var PayoutRepositoryInterface
    */
    protected $repo;

    public function __construct (PayoutRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    /**
     * Method to store payouts
     *
     * @param \App\Http\Requests\Payout\RecordPayoutRequest $request
     * @return void
     *
     */
    public function store(RecordPayoutRequest $request)
    {
        $this->authorize('viewAny', $request->user());

        if ($payout = dispatch_now(new CreateAdminPayoutJob($request->all()))) {
            return response()->json(new PayoutResource($payout->refresh()));
        }

        return response()->json(['message' => __('Unable to save the payout.')], 400);
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
