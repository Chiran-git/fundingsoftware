<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CampaignUser extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'campaign_users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'organization_id',
        'campaign_id',
        'user_id'
    ];

 	/**
     * Get the campaign that is associated with the user.
     */
    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }
}
