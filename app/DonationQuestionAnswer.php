<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DonationQuestionAnswer extends Model
{
    use SoftDeletes;
    /**
     * Attributes that can be mass assigned
     *
     * @var array
     */
    public $fillable = [
        'organization_id',
        'campaign_id',
        'donation_id',
        'donor_question_id',
        'answer'
    ];

    /**
     * Method to build relationship between DonationQuestionAnswer and DonationQuestion
     *
     * @return App\Currency
     */
    public function donation_question()
    {
        return $this->belongsTo(DonationQuestion::class);
    }

}
