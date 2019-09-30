<?php

namespace App\Repositories\Eloquent;

use Illuminate\Http\Request;
use App\Repositories\Contracts\DonorRepositoryInterface;


class DonorRepository extends Repository implements DonorRepositoryInterface
{
    /**
     * Get the donors list for the given organization
     *
     * @param integer $organizationId
     * @param integer $limit
     * @param boolean $pagination
     * @param array   $params
     *
     * @return App\Donor
     */
    public function getDonorsList($organizationId, $limit = 10, $pagination = true)
    {
        $filterBy = request()->query('filter');
        $campaignId = request()->query('campaign');
        if ( $campaignId ) {
            $query = $this->model->select('donors.id', 'donors.first_name', 'donors.last_name', 'donors.email')
            ->selectRaw("CONCAT(donors.first_name, ' ', donors.last_name) AS full_name")
            ->selectRaw("COUNT(donations.id) AS total_donation_count")
            ->selectRaw("SUM(donations.gross_amount) AS total_donation_amount")
            ->join('donations', 'donors.id', '=', 'donations.donor_id')
            ->where('donations.organization_id', $organizationId)
            ->whereNull('donors.deleted_at')
            ->whereNull('donations.deleted_at')
            ->where('donations.campaign_id', '=', $campaignId)
            ->where(function ($query) use($filterBy) {
                if (!empty($filterBy)) {
                    $query->whereRaw("CONCAT(donors.first_name, ' ', donors.last_name) LIKE '%$filterBy%'")
                        ->orWhereRaw("donors.email LIKE '%$filterBy%'");
                }
            })
            ->groupBy('donations.donor_id');
        } else {
            $query = $this->model->select('donors.id', 'donors.first_name', 'donors.last_name', 'donors.email', 'donors.total_donation_count', 'donors.total_donation_amount')
            ->selectRaw("CONCAT(donors.first_name, ' ', donors.last_name) AS full_name")
            ->join('donations', 'donors.id', '=', 'donations.donor_id')
            ->where('donations.organization_id', $organizationId)
            ->whereNull('donors.deleted_at')
            ->whereNull('donations.deleted_at')
            ->where(function ($query) use($filterBy) {
                if (!empty($filterBy)) {
                    $query->whereRaw("CONCAT(donors.first_name, ' ', donors.last_name) LIKE '%$filterBy%'")
                        ->orWhereRaw("donors.email LIKE '%$filterBy%'");
                }
            })
            ->groupBy('donations.donor_id');
        }

        if ($pagination) {
            $sortOptions = json_decode(request()->query('sort'));
            if (isset($sortOptions->fieldName) && ! empty($sortOptions->fieldName)
                && isset($sortOptions->order) && ! empty($sortOptions->order)) {
                $query->orderBy($sortOptions->fieldName, $sortOptions->order);
            } else {
                $query->orderBy('full_name', 'asc');
            }

            return $query->paginate($limit);
        } else {
            $query->orderBy('full_name', 'asc');
            return $query->get();
        }
    }

    /**
     * Method to create a new donor if the donor with given email doesn't exist
     *
     * @param array $attributes Donor attributes
     *
     * @return App\Donor New Donor or existing user object
     */
    function createIfNotExists($attributes)
    {
        if ($donor = $this->findWhere(['email' => $attributes['email']])) {
            return $donor;
        }

        return $this->store($attributes);
    }
}
