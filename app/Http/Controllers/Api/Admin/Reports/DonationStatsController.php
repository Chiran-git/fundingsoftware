<?php

namespace App\Http\Controllers\Api\Admin\Reports;

use DB;
use App\Donor;
use App\Donation;
use Carbon\Carbon;
use App\Support\RJ;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DonationStatsController extends Controller
{
    /**
     * Method to load the donation stats
     */
    public function stats (Request $request)
    {
        $this->authorize('viewReport', $request->user());

        $totalGrossDonation = Donation::sum('gross_amount');
        $totalNetDonation = Donation::sum('net_amount');
        $totalNoOfDonation = Donation::count();
        $uniqueDonors = Donor::count();

        $data = [
            'totalGrossDonation' => RJ::donationMoney($totalGrossDonation/100),
            'totalNetDonation' => RJ::donationMoney($totalNetDonation/100),
            'totalNoOfDonation' => $totalNoOfDonation,
            'uniqueDonor' => $uniqueDonors
        ];

        return response()->json($data, 200);
    }

    /**
     * Method to get the monthly donations for chart
     */
    public function monthlyDonations(Request $request)
    {
        $this->authorize('viewReport', $request->user());

        $months = [];
        for ($i = 11; $i >= 1; $i--) {
            $months[] = date("Y-m-d", strtotime( date( 'Y-m-01' )." -$i months"));
        }

        $data = [];
        for ($i = 1; $i <= 12; $i++) {
            if ($i == 12) {
                $date = Carbon::now()->firstOfMonth();
                $date_end = Carbon::now();
            } else {
                $date = Carbon::create($months[$i-1] );
                $date_end = $date->copy()->endOfMonth();
            }

            $donations = Donation::where('created_at', '>=', $date)
                        ->where('created_at', '<=', $date_end)
                        ->sum('gross_amount');

            // Save the count of donations for the current month in the output array
            $data[$date->shortEnglishMonth." ".$date->year] = (float)($donations/100);
        }

        return response()->json(['bpi' => $data]);
    }

}
