<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\DonationRepositoryInterface;

class DonationRepository extends Repository implements DonationRepositoryInterface
{

    /**
     * Get the donations list for the given organization
     *
     * @param integer $organizationId
     * @param integer $limit
     * @param boolean $pagination
     * @param array   $params
     *
     * @return integer
     */
    public function getDonationsList($organizationId, $limit = 10, $pagination = true)
    {
        $query = $this->model->select('donations.id', 'donations.donor_id', 'donations.gross_amount', 'donations.net_amount', 'donations.entry_type', 'donations.donation_method', 'donations.card_brand', 'donations.created_at', 'campaigns.name', 'campaign_rewards.title', 'currencies.symbol')
            ->selectRaw("CONCAT(donors.first_name, ' ', donors.last_name) AS full_name")
            ->join('donors', 'donations.donor_id', '=', 'donors.id')
            ->join('campaigns', 'donations.campaign_id', '=', 'campaigns.id')
            ->join('currencies', 'donations.currency_id', '=', 'currencies.id')
            ->leftJoin('donation_rewards', 'donations.id', '=', 'donation_rewards.donation_id')
            ->leftJoin('campaign_rewards', 'donation_rewards.campaign_reward_id', '=', 'campaign_rewards.id')
            ->where('donations.organization_id', $organizationId)
            ->whereNull('donations.deleted_at');

        if (request()->user()->currentRole() == 'campaign-admin') {
            $query->join('campaign_users', 'campaigns.id', '=', 'campaign_users.campaign_id')
                ->where('campaign_users.user_id', request()->user()->id);
        }

        if ($pagination) {
            $sortOptions = json_decode(request()->query('sort'));
            if (isset($sortOptions->fieldName) && ! empty($sortOptions->fieldName)
                && isset($sortOptions->order) && ! empty($sortOptions->order)) {
                $query->orderBy($sortOptions->fieldName, $sortOptions->order);
            } else {
                $query->orderBy('full_name', 'asc');
            }

            if (!empty(request()->query('campaign'))) {
                $query->where('donations.campaign_id', request()->query('campaign'));
            }
            if (!empty(request()->query('start_date')) && !empty(request()->query('end_date')) ) {
                $query->whereBetween('donations.created_at', [
                    request()->query('start_date'),
                    request()->query('end_date')
                ]);
            }

            return $query->paginate($limit);
        } else {
            $query->orderBy('full_name', 'asc');
            return $query->get();
        }
    }

}
