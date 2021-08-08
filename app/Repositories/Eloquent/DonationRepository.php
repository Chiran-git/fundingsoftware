<?php

namespace App\Repositories\Eloquent;

use DB;
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
        $query = $this->model->select('donations.id', 'donations.donor_id', 'donations.gross_amount', 'donations.net_amount', 'donations.entry_type', 'donations.donation_method', 'donations.card_brand', 'donations.created_at', 'campaigns.name', 'campaign_rewards.title', 'currencies.symbol', 'affiliations.name as affiliation')
            ->selectRaw("CONCAT(donors.first_name, ' ', donors.last_name) AS full_name")
            ->join('donors', 'donations.donor_id', '=', 'donors.id')
            ->join('campaigns', 'donations.campaign_id', '=', 'campaigns.id')
            ->join('currencies', 'donations.currency_id', '=', 'currencies.id')
            ->leftJoin('affiliations', 'donations.affiliation_id', '=', 'affiliations.id')
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
            $query->orderBy('created_at', 'desc');
            return $query->get();
        }
    }

    /*
     * Method to get donations reports
     */
    public function getOnlineDonations($limit = 10, $pagination = true)
    {
        $sortBy = "";
        if (!empty(request()->query('sort'))) {
            $sortBy = json_decode(request()->query('sort'));
        }
        $query = $this->model
            ->leftJoin('organizations', 'donations.organization_id', '=', 'organizations.id')
            ->leftJoin('currencies', 'currencies.id', '=', 'donations.currency_id')
            ->select('organizations.name', 'organizations.logo', 'currencies.symbol',
                DB::raw('ifnull(sum(donations.gross_amount),0) as gross_donations'),
                DB::raw('ifnull(sum(donations.net_amount),0) as net_donations'),
                DB::raw('ifnull(sum(donations.stripe_fee),0) as stripe_fees'),
                DB::raw('ifnull(sum(donations.application_fee),0) as rocket_fees'),
                DB::raw('ifnull(sum(donations.application_fee),0) as rocket_fees'),
                DB::raw('COUNT("*") as no_of_donations'),
            )
            ->where('entry_type', 'online')
            ->whereNull('donation_method')
            ->groupBy('donations.organization_id')
            ->when($sortBy, function ($query, $sortBy) {
                return $query->orderBy($sortBy->fieldName, $sortBy->order);
            }, function ($query) {
                return $query->orderBy('donations.id', 'desc');
            });

        if (request()->query('start_date') && request()->query('end_date')) {
            $query->whereBetween('donations.created_at', [request()->query('start_date'), request()->query('end_date')]);
        }

        if ($pagination) {
            return $query->paginate($limit);
        } else {
            $query->orderBy('donations.id', 'desc');
            return $query->get();
        }
    }

}
