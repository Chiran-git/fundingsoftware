<?php

namespace App\Repositories\Contracts;

interface CountryRepositoryInterface
{
    /**
     * Method to find the default country for the app.
     *
     * @return App\Country
     */
    function findDefault();

    /**
     * Method to find the Country with the given iso code.
     *
     * @param string $code Two char iso code (example US)
     *
     * @return App\Country
     */
    function findByCode($code);
}
