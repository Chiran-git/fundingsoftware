<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\CountryRepositoryInterface;

class CountryRepository extends Repository implements CountryRepositoryInterface
{
    /**
     * Method to find the default country for the app.
     *
     * @return App\Country
     */
    function findDefault()
    {
        // Get default country code from config
        return $this->findWhere(['iso_code' => config('app.defaults.country')]);
    }

    /**
     * Method to find the Country with the given iso code.
     *
     * @param string $code Three char iso code (example USD)
     *
     * @return App\Country
     */
    function findByCode($code)
    {
        // Get default Country code from config
        return $this->findWhere(['iso_code' => $code]);
    }
}
