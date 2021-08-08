<?php

namespace App\Jobs\Donor;

use DB;
use App\Organization;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use App\Events\Donation\DonationWasMade;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Repositories\Contracts\DonorRepositoryInterface;
use App\Repositories\Contracts\DonationRepositoryInterface;
use App\Repositories\Contracts\CampaignRepositoryInterface;
use App\Repositories\Contracts\DonationQuestionAnswerRepositoryInterface;

class Create implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Organization
     *
     * @var \App\Organization
     */
    public $organization;

    /**
     * Donor data
     *
     * @var array
     */
    public $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Organization $organization, $data)
    {
        $this->data = $data;
        $this->organization = $organization;
    }



    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(
        DonorRepositoryInterface $donorRepo,
        DonationRepositoryInterface $donationRepo,
        DonationQuestionAnswerRepositoryInterface $donationAnswerRepo,
        CampaignRepositoryInterface $campaignRepo
    ) {
        // First create the donor if not already exists
        $donor = $donorRepo->createIfNotExists(
            [
                'first_name' => $this->data['first_name'],
                'last_name' => $this->data['last_name'],
                'email' => $this->data['email'],
            ]
        );

        // Create new donation
        $donation = $donationRepo->store(
            [
                'organization_id' => $this->organization->id,
                'donor_id' => $donor->id,
                'affiliation_id' => $this->data['affiliation_id'],
                'entry_type' => 'manual',
                'donation_method' => $this->data['donation_method'],
                'check_number' => $this->data['check_number'],
                'gross_amount' => $this->data['gross_amount'],
                'net_amount' => $this->data['gross_amount'],
                'stripe_fee' => 0.00,
                'application_fee' => 0.00,
                'campaign_id' => $this->data['campaign_id'],
                'currency_id' => $this->organization->currency->id,
                'mailing_address1' => $this->data['mailing_address1'],
                'mailing_address2' => $this->data['mailing_address2'],
                'mailing_city' => $this->data['mailing_city'],
                'mailing_state' => $this->data['mailing_state'],
                'mailing_zipcode' => $this->data['mailing_zipcode'],
                'billing_address1' => $this->data['billing_address1'],
                'billing_address2' => $this->data['billing_address2'],
                'billing_city' => $this->data['billing_city'],
                'billing_state' => $this->data['billing_state'],
                'billing_zipcode' => $this->data['billing_zipcode'],
                'comments' => $this->data['comments'],
            ]
        );

        // Store donor question's answers if provided
        if (isset($this->data['donor_answers']) && !empty($this->data['donor_answers'])) {
            foreach ($this->data['donor_answers'] as $questionId => $answer) {
                if (!empty($answer['answer'])) {
                    $donationAnswerRepo->store(
                        [
                            'organization_id' => $this->organization->id,
                            'campaign_id' => $this->data['campaign_id'],
                            'donation_id' => $donation->id,
                            'donor_question_id' => $questionId,
                            'answer' => $answer['answer']
                        ]
                    );
                }
            }
        }

        event(new DonationWasMade($donation));

        return $donor;
    }
}
