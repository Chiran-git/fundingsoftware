<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use App\Traits\HasFractionalMonetaryUnits;
use Illuminate\Database\Eloquent\SoftDeletes;

class Donor extends Model
{
    use Notifiable, SoftDeletes, HasFractionalMonetaryUnits;

    /**
     * Attributes that can be mass assigned
     *
     * @var array
     */
    public $fillable = [
        'first_name',
        'last_name',
        'email',
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
            'total_donation_amount',
        ];
    }

    /**
     * Accessor for full name
     *
     * @return string
     */
    public function getNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    /**
     * Method to build relationship between Donation and Donor
     *
     * @return App\Donation
     */
    public function donations()
    {
        return $this->hasMany(Donation::class);
    }

    /**
     * Method to check if donor donated for any organization campaigns
     *
     * @param int $organizationId
     *
     * @return boolean
     */
    public function isOrganizationDonor($organizationId)
    {
        return $this->donations()->where('organization_id', $organizationId)
            ->where('donor_id', $this->id)
            ->exists();
    }

    /**
     * Method to check if donor donated for organization campaigns belonging to current campaign admin.
     *
     * @param int   $organizationId Organization id
     * @param array $campaignIds    Campaign ids
     *
     * @return boolean
     */
    public function isOrganizationCampaignDonor($organizationId, $campaignIds)
    {
        return $this->donations()->where('organization_id', $organizationId)
            ->whereIn('campaign_id', $campaignIds)
            ->where('donor_id', $this->id)
            ->exists();
    }
}
