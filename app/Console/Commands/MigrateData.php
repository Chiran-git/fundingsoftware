<?php

namespace App\Console\Commands;

use App\Donor;
use App\Donation;
use App\Organization;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MigrateData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rj:migrate-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate data from old rocketjar DB to the new v2 DB';

    /**
     * Organization being migrated to
     *
     * @var \App\Organization
     */
    public $organization;

    /**
     * Old campaign being migrated
     *
     * @var object
     */
    public $oldCampaign;

    /**
     * Old network to new org mappings
     *
     * @var array
     */
    public $orgMappings = [
        18 => 8,
        148 => 9,
        197 => 17
    ];

    /**
     * Old campaign to new campaign mappings
     *
     * @var array
     */
    public $campMappings = [
        1490057 => 17,
        1490022 => 8,
        209 => 19,
        1490010 => 21,
        1490045 => 18,
        1490014 => 20,
        1490060 => 22
    ];

    public $merchantMapping = [
        24 => 1
    ];

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (! $this->selectCampaign()) {
            // No organization selected or invalid org selected.
            // Return without doing anything
            return;
        }

        $this->info($this->oldCampaign->name);
    }

    /**
     * Method to show a prompt to select campaign
     *
     * @return void
     */
    private function selectCampaign()
    {
        $oldDB = DB::connection('old');
        $projectId = $this->ask('Please provide project (campaign) ID to migrate (ID from old database)');

        $this->oldCampaign = $oldDB->table('projects')
            ->select('*')
            ->where('id', $projectId)
            ->first();

        if (empty($this->oldCampaign)) {
            $this->error('Invalid Campaign ID');
            return false;
        }

        $continue = $this->ask($this->oldCampaign->name . ' will be migrated. Continue y or n?');

        if (strtolower($continue) == 'n') {
            return false;
        }

        if (! $this->campMappings[$projectId] && ! $this->orgMappings[$this->oldCampaign->nid]) {
            $this->info('Campaign mapping not found');
            return false;
        }
        $campaignId = $this->campMappings[$projectId];

        $this->info('New database campaign id : ' . $campaignId);

        if ($funds = $oldDB->table('funds')
            ->select('*')
            ->where('pid', $projectId)
            ->orderBy('id')
            ->get()) {

            foreach ($funds as $fund) {
                $this->saveDonation($fund, $campaignId);
            }
        }
        return true;
    }

    private function saveDonation($fund, $campaignId)
    {
        $createdAt = Carbon::createFromFormat('Y-m-d H:i:s', $fund->tx, 'America/Chicago');
        $createdAt->setTimezone('UTC');

        //create donor
        $donor = $this->saveDonor($fund, $createdAt);

        // Create a new Donation object
        $donation = new Donation();
        $donation->organization_id = $this->orgMappings[$this->oldCampaign->nid];
        $donation->campaign_id = $campaignId;
        $donation->donor_id = $donor->id;
        $donation->currency_id = 1;

        $donation->gross_amount = $fund->full_amount/100;
        $donation->stripe_fee = $fund->proc_cut/100;
        $donation->stripe_fee_currency = 'usd';
        $donation->application_fee = $fund->us_cut/100;
        $donation->application_fee_currency = 'usd';
        $donation->net_amount = $fund->net_amount/100;
        $donation->stripe_charge_id = $fund->charge_id;
        $donation->stripe_transaction_id = $fund->trans_id;

        if (! empty($fund->merchant_id)) {
            $donation->stripe_account_id = $this->merchantMapping[$fund->merchant_id];
        }
        $donation->stripe_payment_status = $fund->charge_status;
        $donation->live_mode = $fund->live_payment;
        $donation->card_name = $fund->purchaser_name;
        $donation->card_brand = $fund->cc_card_type;
        $donation->card_last_four = $fund->cc_last4;
        $donation->payout_method = $fund->merchant_id ? 'bank' : 'check';
        $donation->payout_connected_account_id = ($fund->merchant_id && isset($this->merchantMapping[$fund->merchant_id])) ? $this->merchantMapping[$fund->merchant_id] : null;
        $donation->entry_type = $fund->manual_entry ? 'manual' : 'online';
        $donation->donation_method = $fund->manual_entry_type ? $fund->manual_entry_type : NULL;
        $donation->created_at = $createdAt;
        $donation->updated_at = $createdAt;

        if ($donation->save()) {
            $this->info('Donations saved');
            //update aggregate fields for donor and campaign
            $this->updateDonorAggregateFields($donation);
            $this->updateCampaignAggregateFields($donation);
        }
    }

    /**
     * Create the donor if it doesn't exists.
     * Email is used as the primary key to check existence
     *
     * @param array $data Donor data
     *
     * @return \App\Donor
     */
    private function saveDonor($data, $createdAt)
    {
        $firstName = NULL;
        $lastName = $randomName = str_random(8);

        if (! empty($data->purchaser_name)) {
            $donorName = explode(" ", $data->purchaser_name, 2);
            $firstName = $donorName[0];
            $randomName = !empty($firstName) ? $firstName : $randomName;
            $lastName = isset($donorName[1]) ? $donorName[1] : $randomName;
        }

        return Donor::updateOrCreate(
            ['email' => $data->email],
            [
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => ! empty($data->email) ? $data->email : str_random(4) . "@rocketjar.com",
                'created_at' => $createdAt,
                'updated_at' => $createdAt
            ]
        );
    }

    /**
     * Method to update the aggregate fields in donors table
     *
     * @param \App\Donation $donation
     *
     * @return void
     */
    private function updateDonorAggregateFields($donation)
    {
        if ($donationStats = Donation::selectRaw("SUM(donations.gross_amount) as total_donation_amount")
            ->selectRaw("COUNT(donations.id) as total_donation_count")
            ->where('donor_id', $donation->donor_id)
            ->first()) {

            $this->info("Updated total donation of donor #" . $donation->donor_id);
            // Update the donor's donation count and amount
            $donation->donor->total_donation_count = $donationStats->total_donation_count;
            $donation->donor->total_donation_amount = $donationStats->total_donation_amount/100;
            return $donation->donor->save();
        }

    }

    /**
     * Method to update the aggregate fields in campaigns table
     *
     * @param \App\Donation $donation
     *
     * @return void
     */
    private function updateCampaignAggregateFields($donation)
    {
        if ($donationStats = Donation::selectRaw("SUM(donations.gross_amount) as funds_raised")
            ->selectRaw("COUNT(donations.id) as total_donations")
            ->where('campaign_id', $donation->campaign_id)
            ->first()) {

            $this->info("Updated total donation of campaign #" . $donation->campaign_id);
            $donation->campaign->total_donations = $donationStats->total_donations;
            $donation->campaign->funds_raised = $donationStats->funds_raised/100;
            return $donation->campaign->save();
        }
    }
}
