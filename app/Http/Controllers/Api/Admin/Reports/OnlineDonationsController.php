<?php

namespace App\Http\Controllers\Api\Admin\Reports;

use App\Donation;
use App\Support\RJ;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\DonationResource;
use App\Repositories\Contracts\DonationRepositoryInterface;

class OnlineDonationsController extends Controller
{
    /**
     * @var DonationRepositoryInterface
     */
    private $repo;

    public function __construct (DonationRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    /**
     * Method to load the online donations listing
     */
    public function index (Request $request)
    {
        $this->authorize('viewReport', $request->user());
        return $this->repo->getOnlineDonations(config('pagination.limit'));
    }

    /**
     * Method to get the stats of donation
     */
    public function stats(Request $request)
    {
        $this->authorize('viewReport', $request->user());

        $query = Donation::where([
            ['entry_type', 'online'],
            ['donation_method', null]
        ]);

        $startDate = $request->start_date;
        $endDate = $request->end_date;

        if ($startDate != '' && $endDate != '') {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        $totalDonations = $query->sum('gross_amount');

        $noOfDonations = $query->count();

        $rocketJarFees = $query->sum('application_fee');

        $stripeFees = $query->sum('stripe_fee');

        $data = [
                    'totalDonations' => RJ::donationMoney($totalDonations/100),
                    'noOfDonations' => RJ::donationMoney($noOfDonations),
                    'rocketJarFees' => RJ::donationMoney($rocketJarFees/100),
                    'stripeFees' => RJ::donationMoney($stripeFees/100)
                ];
        return response()->json($data, 200);
    }
}
