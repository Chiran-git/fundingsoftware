<?php

namespace App;

use App\Support\RJ;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use App\Traits\HasFractionalMonetaryUnits;
use Illuminate\Database\Eloquent\SoftDeletes;

class Donation extends Model
{
    use Notifiable, SoftDeletes, HasFractionalMonetaryUnits;

    /**
     * Attributes that can be mass assigned
     *
     * @var array
     */
    public $fillable = [
        'organization_id',
        'donor_id',
        'payout_method',
        'entry_type',
        'donation_method',
        'check_number',
        'gross_amount',
        'net_amount',
        'stripe_fee',
        'application_fee',
        'campaign_id',
        'currency_id',
        'mailing_address1',
        'mailing_address2',
        'mailing_city',
        'mailing_state',
        'mailing_zipcode',
        'billing_address1',
        'billing_address2',
        'billing_city',
        'billing_state',
        'billing_zipcode',
    ];

    /**
     * Fields that are stored in fractional monetary units in database.
     * This is used by HasFractionalMonetaryUnits trait
     *
     * @return array
     */
    public function fractionalMonetaryFields()
    {
        return [
            'gross_amount',
            'net_amount',
            'stripe_fee',
            'application_fee',
        ];
    }

    public static function boot()
    {
        parent::boot();

        self::created(function($model){
            $model->where('id', $model->id)
                ->update(['receipt_number' => (config('app.receipt_number_start_value') + $model->id)]);
        });
    }

    /**
     * Method to build relationship between Organization
     *
     * @return App\Organization
     */
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Method to build relationship between Donation and Campaign
     *
     * @return App\Campaign
     */
    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    /**
     * Method to build relationship between Donation and Donor
     *
     * @return App\Donor
     */
    public function donor()
    {
        return $this->belongsTo(Donor::class);
    }

    /**
     * Method to build relationship between Donation and Campaign
     *
     * @return App\Currency
     */
    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    /**
     * Relation with DonationRewards
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reward()
    {
        return $this->hasOne(DonationReward::class, 'donation_id');
    }

    /**
     * Method to update the aggregate fields in donors table
     *
     * @param \App\Donation $donation
     *
     * @return void
     */
    public static function updateDonorAggregateFields($donation)
    {
        $donationStats = $donation->selectRaw("SUM(donations.gross_amount) as total_donation_amount")
            ->selectRaw("COUNT(donations.id) as total_donation_count")
            ->where('donor_id', $donation->donor_id)
            ->first();

        // Update the donor's donation count and amount
        $donation->donor->total_donation_count = $donationStats->total_donation_count;
        $donation->donor->total_donation_amount = RJ::convertToWholeUnit(
            $donationStats->total_donation_amount
        );
        return $donation->donor->save();
    }

    /**
     * Method to update the aggregate fields in campaigns table
     *
     * @param \App\Donation $donation
     *
     * @return void
     */
    public static function updateCampaignAggregateFields($donation)
    {
        $donationStats = $donation->selectRaw("SUM(donations.gross_amount) as funds_raised")
            ->selectRaw("COUNT(donations.id) as total_donations")
            ->where('campaign_id', $donation->campaign_id)
            ->first();

        // Update the donor's donation count and amount
        $donation->campaign->total_donations = $donationStats->total_donations;
        $donation->campaign->funds_raised = RJ::convertToWholeUnit(
            $donationStats->funds_raised
        );
        return $donation->campaign->save();
    }

}
