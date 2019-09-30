<?php

namespace App\Jobs\Donation;

use App\Donor;
use App\Payout;
use App\Campaign;
use App\Donation;
use App\Support\RJ;
use App\DonationReward;
use Illuminate\Bus\Queueable;
use App\DonationQuestionAnswer;
use Illuminate\Queue\SerializesModels;
use App\Events\Donation\RewardWasGiven;
use App\Events\Donation\DonationWasMade;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class MakeDonation implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Campaign for which the donation is made
     *
     * @var \App\Campaign
     */
    public $campaign;

    /**
     * Donation Request data
     *
     * @var array
     */
    public $data;

    /**
     * Reward to be given on donation
     *
     * @var \App\CampaignReward
     */
    public $reward = null;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Campaign $campaign, $reward, $data)
    {
        $this->campaign = $campaign;
        $this->reward = $reward;
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // If we have a reward, then get the amount from the
        // reward rather than from the request
        if (! empty($this->reward)) {
            $this->data['amount'] = $this->reward->min_amount;
        }

        // Convert the amount to cents (fractional unit)
        $amount = RJ::converToFractionUnit($this->data['amount']);

        // Create the charge
        $charge = $this->createCharge($amount);

        // If $charge is string then it means it is error string
        // Oops, we were not able to create the charge, return with
        // what stripe responded with
        if (is_string($charge)) {
            return $this->returnErrors(['card' => [$charge]]);
        }

        if ($charge->status !== 'succeeded') {
            return $this->returnErrors(['card' => [__('Unable to process the payment.')]]);
        }

        // The charge succeeded, lets get the balance transaction
        $transaction = $this->getBalanceTransaction($charge, $this->campaign);
        // If $transaction is string then it means an error occurred
        if (is_string($transaction)) {
            return $this->returnErrors(['card' => [$transaction]]);
        }

        $donation = $this->storeDonation($this->data, $this->campaign, $charge, $transaction);

        // If there is a reward to be given, process it now
        if (! empty($this->reward)) {
            $this->addReward($donation, $this->reward);
        }

        // Call to the function to store payouts.
        $this->createPayout($donation);

        event(new DonationWasMade($donation));

        return $donation;
    }

    /**
     * Method to get the application fee for the given amount
     *
     * @param integer $amount Amount in fractional units
     *
     * @return integer Application fee in fractional units
     */
    private function getApplicationFee($amount)
    {
        $applicationFeePercent = config('app.application_fee_percent');
        $applicationFeeFixed = config('app.application_fee_fixed');

        return (int)round(($amount * $applicationFeePercent) + $applicationFeeFixed);
    }

    /**
     * Create a charge for the donation on Stripe
     *
     * @param integer $amount Amount in fractional units i.e. cents
     *
     * @return mixed \Stripe\Charge if success else the error string
     */
    private function createCharge($amount)
    {
        $campaign = $this->campaign;

        // Find if the campaign's payout method is bank or not.
        // If bank then we need to create the charge against organization's stripe (connected)
        // account. Else create charge against RocketJar's stripe account
        if ($campaign->payout_method == Campaign::PAYOUT_METHOD_BANK && ! empty($campaign->connected_account)) {
            return $this->charge(
                $amount,
                $this->data,
                $this->campaign,
                $campaign->connected_account
            );
        } else {
            // If no connected account, then we will send the money to rocketjar's stripe account
            return $this->charge($amount, $this->data, $this->campaign);
        }
    }

    /**
     * Create a charge for the donation on Stripe either for the connected
     * account or for rocketjar account depending on whether
     * connected account has been sent as argument or not
     *
     * @param integer       $amount   Amount in fractional units i.e. cents
     * @param array         $data     Donor data
     * @param \App\Campaign $campaign Campaign object
     * @param \App\OrganizationConnectedAccount $connectedAccount
     *
     * @return mixed \Stripe\Charge if success else the error string
     */
    private function charge($amount, $data, $campaign, $connectedAccount = null)
    {
        // Get the application fee
        $applicationFee = $this->getApplicationFee($amount);

        $chargeData = [
            'amount' => $amount,
            'currency' => strtolower($campaign->organization->currency->iso_code),
            'source' => $data['stripe_token'],
            'description' => $campaign->organization->name,
            'statement_descriptor' => substr('ROCKETJAR*' . $campaign->organization->name, 0, 22),
            'metadata' => [
                'donor_name' => $data['first_name'] . ' ' . $data['last_name'],
                'donor_email' => $data['email'],
            ]
        ];

        $options = [];

        // If we have a connected account
        if (! empty($connectedAccount)) {
            // Add the application fee to the charge data
            $chargeData['application_fee_amount'] = $applicationFee;

            // Also send the connected account's stripe account id in the options
            // so that the money is directly desposited to the connected account and
            // not to RocketJar's account
            $options['stripe_account'] = $connectedAccount->stripe_user_id;
        }

        try {
            return \Stripe\Charge::create($chargeData, $options);
        } catch (\Stripe\Error\Base $e) {
            $exception = $e->getJsonBody();
            return $exception['error']['message'];
        }
    }

    /**
     * Get the balance transaction for the given charge from Stripe
     *
     * @param \Stripe\Charge $charge
     * @param \App\Campaign $campaign
     *
     * @return mixed \Stripe\BalanceTransaction or error string
     */
    private function getBalanceTransaction($charge, $campaign)
    {
        // If the charge has been made against a connected account,
        // then set the api key for the connected account which is its access_token
        if ($campaign->payout_method == Campaign::PAYOUT_METHOD_BANK && ! empty($campaign->connected_account)) {
            \Stripe\Stripe::setApiKey($campaign->connected_account->stripe_access_token);
        }

        try {
            return \Stripe\BalanceTransaction::retrieve($charge->balance_transaction);
        } catch (\Stripe\Error\Base $e) {
            $exception = $e->getJsonBody();
            return $exception['error']['message'];
        }
    }

    /**
     * Get the fee details from the transaction object
     *
     * @param \Stripe\BalanceTransaction $transaction
     *
     * @return array Array with stripe fee and application fee details
     */
    private function getFees($transaction)
    {
        $fees = array();

        if ($transaction->fee_details) {
            foreach ($transaction->fee_details as $fee) {
                $fees[$fee['type']] = [
                    'amount' => $fee['amount'],
                    'currency' => $fee['currency'],
                ];
            }
        }

        if (! isset($fees['application_fee'])) {
            $fees['application_fee'] = [
                'amount' => $this->getApplicationFee($transaction->amount),
                'currency' => $transaction->currency,
            ];
        }

        return $fees;
    }

    /**
     * Store the donation in database
     *
     * @param array         $data     Donor data
     * @param \App\Campaign  $campaign Campaign object
     * @param \Stripe\Charge $charge
     * @param \Stripe\BalanceTransaction $transaction
     *
     * @return mixed True on success else error string
     */
    private function storeDonation($data, $campaign, $charge, $transaction)
    {
        // First create the donor
        $donor = $this->createDonor($data);
        // Now create the donation
        $donation = $this->createDonation(
            $donor,
            $data,
            $campaign,
            $charge,
            $transaction
        );

        // Store the answers for the donor questions
        if (! empty($data['questions']) && is_array($data['questions'])) {
            $this->storeDonorAnswers($donation, $data);
        }

        return $donation;
    }

    /**
     * Create the donor if it doesn't exists.
     * Email is used as the primary key to check existence
     *
     * @param array $data Donor data
     *
     * @return \App\Donor
     */
    private function createDonor($data)
    {
        return Donor::updateOrCreate(
            ['email' => $data['email']],
            [
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
            ]
        );
    }

    /**
     * Method to save the donation data in database
     *
     * @param \App\Donor                 $donor
     * @param array                      $data     Donor data
     * @param \App\Campaign              $campaign Campaign object
     * @param \Stripe\Charge             $charge
     * @param \Stripe\BalanceTransaction $transaction
     *
     * @return mixed True on success else error string
     */
    private function createDonation($donor, $data, $campaign, $charge, $transaction)
    {
        // Gather the fee details
        $fees = $this->getFees($transaction);

        // Create a new Donation object
        $donation = new Donation();
        $donation->organization_id = $campaign->organization_id;
        $donation->campaign_id = $campaign->id;
        $donation->donor_id = $donor->id;
        $donation->currency_id = $campaign->organization->currency_id;
        $donation->gross_amount = RJ::convertToWholeUnit($transaction->amount);
        $donation->stripe_fee = RJ::convertToWholeUnit($fees['stripe_fee']['amount']);
        $donation->stripe_fee_currency = $fees['stripe_fee']['currency'];
        $donation->application_fee = RJ::convertToWholeUnit($fees['application_fee']['amount']);
        $donation->application_fee_currency = $fees['application_fee']['currency'];
        $totalFee = $fees['stripe_fee']['amount'] + $fees['application_fee']['amount'];
        $donation->net_amount = RJ::convertToWholeUnit($transaction->amount - $totalFee);
        $donation->stripe_charge_id = $charge->id;
        $donation->stripe_transaction_id = $transaction->id;

        $stripeAccountId = null;
        if ($campaign->payout_method == Campaign::PAYOUT_METHOD_BANK
            && ! empty($campaign->connected_account)) {
            $stripeAccountId = $campaign->connected_account->stripe_user_id;
        }

        $donation->stripe_account_id = $stripeAccountId;
        $donation->stripe_payment_status = $charge->status;
        $donation->live_mode = $charge->livemode;
        $donation->card_name = $data['card_name'];
        $donation->card_brand = $charge->source->brand;
        $donation->card_last_four = $charge->source->last4;
        $donation->payout_method = $stripeAccountId ?
            Campaign::PAYOUT_METHOD_BANK : Campaign::PAYOUT_METHOD_CHECK;
        $donation->payout_connected_account_id = $stripeAccountId ? $campaign->connected_account->id : null;
        $donation->entry_type = 'online';
        // System donor question answers
        $systemDonorAnswerFields = ['mailing_address1', 'mailing_address2', 'mailing_city', 'mailing_state', 'mailing_zipcode', 'mailing_country_id', 'comments'];
        foreach ($systemDonorAnswerFields as $field) {
            if (! empty($data[$field])) {
                $donation->$field = $data[$field];
            }
        }

        // TODO: If donation saving fails then we should probably refund the money on stripe
        return $donation->save() ? $donation : __('Unable to save the donation record in the database.');
    }

    /**
     * Method to store the answers for the custom donor questions
     *
     * @param \App\Donation $donation Donation for which has been made
     * @param array $data Donation data
     *
     * @return boolean
     */
    private function storeDonorAnswers($donation, $data)
    {
        if (! is_array($data['questions'])) {
            return false;
        }

        foreach ($data['questions'] as $questionId) {
            // Load the DonorQuestion
            $donorQuestion = $donation->organization->donorQuestions()
                ->where('id', $questionId)
                ->first();
            // If no question found or no answer key found in data,
            // then simply continue without doing anything

            if (! $donorQuestion || ! isset($data['question_' . $questionId])) {
                continue;
            }

            // Save the donation question answer
            $answer = new DonationQuestionAnswer();
            $answer->organization_id = $donation->organization_id;
            $answer->campaign_id = $donation->campaign_id;
            $answer->donation_id = $donation->id;
            $answer->donor_question_id = $donorQuestion->id;
            $answer->answer = trim($data['question_' . $questionId]);
            $answer->save();
        }
    }

    /**
     * Associate the donation with the given reward
     *
     * @param \App\Donation       $donation
     * @param \App\CampaignReward $reward
     *
     * @return void
     */
    private function addReward($donation, $reward)
    {
        // First make sure that the donation and reward belong to same campaign
        if ($donation->campaign_id != $reward->campaign_id) {
            return false;
        }

        // The donation amount should be more than or equal to the reward's min amount
        if ($donation->gross_amount < $reward->min_amount) {
            return false;
        }

        $donationReward = new DonationReward();
        $donationReward->organization_id = $donation->organization_id;
        $donationReward->campaign_id = $donation->campaign_id;
        $donationReward->donation_id = $donation->id;
        $donationReward->campaign_reward_id = $reward->id;

        $result = $donationReward->save();

        event(new RewardWasGiven($reward));

        return $result;
    }

    /**
     * Method to return erros in a format that is understood by
     * Vue frontend
     *
     * @param array $errors
     * @return array
     */
    private function returnErrors($errors)
    {
        return [
            'errors' => $errors
        ];
    }

    /**
     * Method to store the answers for the custom donor questions
     *
     * @param \App\Donation $donation
     *
     * @return boolean
     */
    private function createPayout($donation)
    {
        if (isset($donation->payout_connected_account_id) && ! empty($donation->payout_connected_account_id)) {
            $payout = new Payout();
            $payout->organization_id = $donation->organization_id;
            $payout->campaign_id = $donation->campaign_id;
            $payout->organization_connected_account_id = $donation->payout_connected_account_id;
            $payout->issue_date = now();
            $payout->start_date = now();
            $payout->deposit_amount = RJ::convertToWholeUnit($donation->net_amount);
            $payout->gross_amount = RJ::convertToWholeUnit($donation->gross_amount);

            if ($result = $payout->save()) {
                Donation::where('id', $donation->id)
                    ->update(['payout_id' => $payout->id]);
            }

            return $result;
        }
    }
}
