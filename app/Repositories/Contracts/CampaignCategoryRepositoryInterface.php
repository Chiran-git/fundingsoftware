<?php

namespace App\Repositories\Contracts;

interface CampaignCategoryRepositoryInterface
{
    /**
     * Method to get the stats related to the categories
     *
     * @return array
     */
    function getCampaignCategories();
}
