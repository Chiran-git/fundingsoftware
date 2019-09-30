<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasFractionalMonetaryUnits;
use Illuminate\Database\Eloquent\SoftDeletes;

class CampaignReward extends Model
{
    use SoftDeletes, HasFractionalMonetaryUnits;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'campaign_rewards';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'organization_id',
        'campaign_id',
        'title',
        'description',
        'min_amount',
        'quantity',
        'quantity_rewarded',
        'image',
        'image_filename',
        'image_filesize',
        'disabled_by_id'
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
            'min_amount',
        ];
    }

    /**
     * Relation with Organization
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Relation with campaign
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    /**
     * Method to update the quantity rewarded
     *
     * @param \App\CampaignReward $campaignReward
     *
     * @return void
     */
    public static function updateQuantityRewarded($campaignReward)
    {
        if (empty($campaignReward->quantity_rewarded)) {
            $campaignReward->quantity_rewarded = 1;
        } else {
            $campaignReward->quantity_rewarded += 1;
        }
        return $campaignReward->save();
    }

}
