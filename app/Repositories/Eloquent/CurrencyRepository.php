<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\CurrencyRepositoryInterface;

class CurrencyRepository extends Repository implements CurrencyRepositoryInterface
{
    /**
     * Method to find the default Currency for the app.
     *
     * @return App\Currency
     */
    function findDefault()
    {
        // Get default Currency code from config
        return $this->findWhere(['iso_code' => config('app.defaults.currency')]);
    }

    /**
     * Method to find the Currency with the given iso code.
     *
     * @param string $code Three char iso code (example USD)
     *
     * @return App\Currency
     */
    function findByCode($code)
    {
        // Get default Currency code from config
        return $this->findWhere(['iso_code' => $code]);
    }
}
