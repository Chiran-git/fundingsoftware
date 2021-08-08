<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CampaignCategory extends Model
{
    protected $fillable = ['name'];

    /**
     * Method to get all the campaigns related to the category
     */
    public function campaigns()
    {
        return $this->hasMany(Campaign::class);
    }
}
