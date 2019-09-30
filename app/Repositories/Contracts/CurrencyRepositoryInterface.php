<?php

namespace App\Repositories\Contracts;

interface CurrencyRepositoryInterface
{
    /**
     * Method to find the default Currency for the app.
     *
     * @return App\Currency
     */
    function findDefault();

    /**
     * Method to find the Currency with the given iso code.
     *
     * @param string $code Three char iso code (example USD)
     *
     * @return App\Currency
     */
    function findByCode($code);
}
