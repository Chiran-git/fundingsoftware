<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasFractionalMonetaryUnits;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payout extends Model
{
    use SoftDeletes, HasFractionalMonetaryUnits;

    /**
     * Fields that are stored in fractional monetary units in database.
     * This is used by HasFractionalMonetaryUnits trait
     *
     * @return array
     */
    public function fractionalMonetaryFields()
    {
        return [
            'deposit_amount',
            'gross_amount'
        ];
    }

    /**
     * Method to build relationship between OrganizationConnectedAccount and Payouts
     *
     * @return App\OrganizationConnectedAccount
     */
    public function account()
    {
        return $this->belongsTo(OrganizationConnectedAccount::class, 'organization_connected_account_id');
    }

}
