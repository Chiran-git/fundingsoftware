<?php

namespace App\Repositories\Eloquent;

use DB;
use App\Repositories\Contracts\CampaignCategoryRepositoryInterface;

class CampaignCategoryRepository extends Repository implements CampaignCategoryRepositoryInterface
{
    public function getCampaignCategories($limit = 10, $pagination = true)
    {
        $sortBy = "";
        if (!empty(request()->query('sort'))) {
            $sortBy = json_decode(request()->query('sort'));
        }

        $query = $this->model
                ->select('campaign_categories.id','campaign_categories.name', 'campaign_categories.created_at', 'campaign_categories.updated_at',
                    DB::raw("coalesce( (SELECT COUNT(campaigns.id) FROM campaigns
                        WHERE campaigns.campaign_category_id = campaign_categories.id
                        GROUP BY campaigns.campaign_category_id), 0) as total_campaigns"),
                    DB::raw("coalesce( (SELECT COUNT(campaigns.id) FROM campaigns
                        WHERE campaigns.campaign_category_id = campaign_categories.id and published_at <= NOW() and disabled_at is null and (end_date is null or end_date >= NOW() ) GROUP BY campaigns.campaign_category_id), 0 ) as active_campaigns"),
                    DB::raw("coalesce( (SELECT COUNT(campaigns.id) FROM campaigns
                        WHERE campaigns.campaign_category_id = campaign_categories.id and published_at <= NOW() and disabled_at is null and (end_date <= NOW() ) GROUP BY campaigns.campaign_category_id), 0 ) as completed_campaigns")
                )
                ->when($sortBy, function ($query, $sortBy) {
                    return $query->orderBy($sortBy->fieldName, $sortBy->order);
                }, function ($query) {
                    return $query->orderBy('campaign_categories.created_at', 'desc');
                });

        if ($pagination) {
            return $query->paginate($limit);
        } else {
            $query->orderBy('campaign_categories.created_at', 'desc');
            return $query->get();
        }
    }
}
