<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrganizationConnectedAccount extends Model
{
	use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'organization_connected_accounts';

    /**
     * Attributes that can be mass assigned
     *
     * @var array
     */
    public $fillable = [
        'organization_id',
        'created_by_id',
        'is_default',
        'nickname',
        'stripe_user_id',
        'stripe_access_token',
        'stripe_livemode',
        'stripe_refresh_token',
        'stripe_token_type',
        'stripe_publishable_key',
        'stripe_scope',
    ];

    /**
     * Get the Campaigns for the Account
     *
     * @return App\Campaign
     */
    public function campaigns()
    {
        return $this->hasMany(Campaign::class, 'payout_connected_account_id');
    }

    /**
     * Get the Organization for the Account
     *
     * @return App\Organization
     */
    public function organization()
	{
	    return $this->belongsTo(Organization::class);
	}

    /**
     * Method to build relationship between OrganizationConnectedAccount and Payout
     *
     * @return App\Payout
     */
    public function payouts()
    {
        return $this->hasMany(Payout::class);
    }
}
