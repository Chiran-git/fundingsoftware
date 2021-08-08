<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Affiliation extends Model
{
    protected $fillable = ['name'];

    /**
     * Method to get all the donations related to the affiliation
     */
    public function donations()
    {
        return $this->hasMany(Donation::class);
    }
}
