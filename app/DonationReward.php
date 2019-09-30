<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DonationReward extends Model
{
    use SoftDeletes;

    /**
     * Relation with CampaignReward
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function campaign_reward()
    {
        return $this->belongsTo(CampaignReward::class, 'campaign_reward_id');
    }
}
