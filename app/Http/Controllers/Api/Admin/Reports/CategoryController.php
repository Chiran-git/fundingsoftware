<?php

namespace App\Http\Controllers\Api\Admin\Reports;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\CampaignCategoryRepositoryInterface;

class CategoryController extends Controller
{
    /**
     * @var CategoryRepositoryInterface
     */
    private $campaignCategoryRepo;

    /**
     *
     */
    public function __construct(CampaignCategoryRepositoryInterface $campaignCategoryRepo)
    {
        $this->campaignCategoryRepo = $campaignCategoryRepo;
    }

    /**
     * Method to get the category stats
     */
    public function getCampaignCategories(Request $request)
    {
        return $this->campaignCategoryRepo->getCampaignCategories(config('pagination.limit'));
    }
}
