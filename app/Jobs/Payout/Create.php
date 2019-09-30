<?php

namespace App\Jobs\Payout;

use DB;
use Log;
use App\Payout;
use App\Donation;
use App\Campaign;
use Carbon\Carbon;
use App\Support\RJ;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Repositories\Contracts\PayoutRepositoryInterface;

class Create implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Payout Request data
     *
     * @var array
     */
    public $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(PayoutRepositoryInterface $repo)
    {
        $issue_date = Carbon::createFromFormat('Y-m-d', $this->data['issue_date'], $this->data['timezone'])->setTimezone('UTC');
        $start_date = Carbon::createFromFormat('Y-m-d', $this->data['start_date'], $this->data['timezone'])->setTimezone('UTC');
        $end_date = Carbon::createFromFormat('Y-m-d', $this->data['end_date'], $this->data['timezone'])->setTimezone('UTC');

        $campaign = $this->checkCampaign($this->data);

        $query = Donation::where(
                [
                    ['campaign_id', '=', $this->data['campaign_id']],
                    ['entry_type', '=', 'online'],
                ])
                ->whereNull('payout_id')
                ->whereNull('payout_connected_account_id')
                ->whereBetween('created_at', [$start_date, $end_date]) ;

        $donations = $query->get();

        $totalAmount = $query->sum('net_amount');

        $grossAmount = $query->sum('gross_amount');

        $donationIds = [];

        foreach ($donations as $donation) {
            array_push($donationIds, $donation->id);
        }

        if ($campaign && sizeof($donationIds) > 0) {
            DB::beginTransaction();
            try {
                $payout = new Payout;
                $payout->organization_id = $this->data['organization_id'];
                $payout->campaign_id = $this->data['campaign_id'];
                $payout->issue_date = $issue_date;
                $payout->start_date = $start_date;
                $payout->end_date = $end_date;
                $payout->deposit_amount = RJ::convertToWholeUnit($totalAmount);
                $payout->gross_amount = RJ::convertToWholeUnit($grossAmount);
                $payout->payout_name = $campaign->payout_name;
                $payout->payout_address1 = $campaign->payout_address1;
                $payout->payout_address2 = $campaign->payout_address2;
                $payout->payout_city = $campaign->payout_city;
                $payout->payout_state = $campaign->payout_state;
                $payout->payout_zipcode = $campaign->payout_zipcode;
                $payout->payout_country_id = $campaign->payout_country_id;
                $payout->payout_payable_to = $campaign->payout_payable_to;
                $payout->save();

                Donation::whereIn('id', $donationIds)
                        ->update(['payout_id' => $payout->id]);

                DB::commit();
            }
            catch (Exception $e) {
                DB::rollBack();
                Log::info($e->getErrors());
                return false;
            }

            return $payout;
        } else {
            return false;
        }
    }

    /**
     * Method to check campaign
     */
    public function checkCampaign($param)
    {
        if ($campaign = Campaign::where('id', $param['campaign_id'])
                    ->where(
                        function ($query) {
                            $query->whereNull('payout_method')
                                ->orWhere('payout_method', '!=', 'bank');
                        }
                    )->first()) {
            return $campaign;
        }
        return false;
    }
}
