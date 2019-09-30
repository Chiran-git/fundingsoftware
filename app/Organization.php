<?php

namespace App;

use App\Support\RJ;
use App\Traits\Sluggable;
use App\Traits\Presentable;
use Illuminate\Database\Eloquent\Model;
use App\Presenters\OrganizationPresenter;
use Illuminate\Database\Eloquent\SoftDeletes;

class Organization extends Model
{
    use Sluggable, Presentable, SoftDeletes;

    /**
     * Presenter used by this model
     *
     * @var string
     */
    protected $presenter = OrganizationPresenter::class;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'owner_id', 'currency_id',
        'country_id', 'primary_color', 'secondary_color',
        'address1', 'address2', 'city', 'state', 'zipcode', 'phone', 'slug'
    ];

    /**
     * Scope a query to only include active organizations.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->whereNull('deactivated_at');
    }

    /**
     * Relation with owner user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Relation with currency
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    /**
     * Relation with country
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * Relation with users
     *
     * A single user can belong to many users
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'organization_users')
            ->withTimestamps();
    }

    /**
     * Method to build relationship between Organization and DonorQuestion
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function donorQuestions()
    {
        return $this->hasMany(DonorQuestion::class);
    }

    /**
     * Method to build relationship between Organization and Campaign Reward model
     *
     * @return App\CampaignReward
     */
    public function rewards()
    {
        return $this->hasMany(CampaignReward::class);
    }

    /**
     * Method to build relationship between Organization and Campaigns
     *
     * @return App\Campaign
     */
    public function campaigns()
    {
        return $this->hasMany(Campaign::class);
    }


    /**
     * Method to build relationship between Organization and Donations
     *
     * @return App\Donation
     */
    public function donations()
    {
        return $this->hasMany(Donation::class);
    }

    /**
     * Method to build relationship between Organization and Active Campaigns
     * Active campaigns means:
     *  - Currently running (end_date yet to reach or null)
     *  - Have been published
     *  - Are not disabled
     *
     * @return App\Campaign
     */
    public function activeCampaigns()
    {
        return $this->hasMany(Campaign::class)
            ->where('published_at', '<=', now())
            ->where(
                function ($query) {
                    $query->whereNull('end_date')
                        ->orWhere('end_date', '>=', now());
                }
            )
            ->whereNull('disabled_at');
    }

    /**
     * Method to build relationship between Organization and Organization Connected Account
     *
     * @return App\OrganizationConnectedAccount
     */
    public function connected_accounts()
    {
        return $this->hasMany(OrganizationConnectedAccount::class);
    }

    /**
     * Get number of campaigns associated with the organization
     *
     * @return int
     */
    public function noOfCampaigns()
    {
        return $this->campaigns()->count();
    }

    /**
     * Get net total donation received by the organization
     *
     * @return int
     */
    public function totalDonationReceived()
    {
        return $this->donations()->sum('gross_amount');
    }

    /**
     * Get net donation received by the organization
     *
     * @return int
     */
    public function netDonationReceived()
    {
        return $this->donations()->sum('net_amount');
    }

    /**
     * Get the organization logo.
     *
     * @param  string  $value
     * @return string
     */
    public function getLogoAttribute($value)
    {
        return $value ? RJ::assetCdn($value) : null;
    }
}
