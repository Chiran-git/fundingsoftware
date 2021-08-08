<?php

namespace App\Repositories\Eloquent;

use DB;
use App\Organization;
use App\Repositories\Contracts\AffiliationRepositoryInterface;

class AffiliationRepository extends Repository implements AffiliationRepositoryInterface
{
    /**
     * Method to get affiliation donations for a particular organization
     */
    public function getAffiliationDonationsByOrganization($organization)
    {
        $orgId = $organization->id;
        $currencyId = $organization->currency_id;
        $affiliationDonations = $this->model
            ->select(
            'affiliations.id', 'affiliations.name',
            DB::raw("(SELECT currencies.symbol FROM currencies
                        WHERE currencies.id = $currencyId) as symbol"),
            DB::raw("coalesce( (SELECT FORMAT(SUM(donations.gross_amount)/100,2) FROM donations
                        WHERE donations.affiliation_id = affiliations.id and donations.organization_id = $orgId GROUP BY affiliations.id), 0 ) as total_donations"),
            DB::raw("coalesce( (SELECT FORMAT(SUM(donations.net_amount)/100, 2) FROM donations
                        WHERE donations.affiliation_id = affiliations.id and donations.organization_id = $orgId GROUP BY affiliations.id), 0 ) as net_donations")
            )
            ->get();

        return $affiliationDonations;
    }

    /**
     * Method to get affiliation donations of all organizations for superadmin
     */
    public function getAffiliationDonationsForAdmin()
    {
        $affiliationDonations = $this->model
            ->select(
            'affiliations.id', 'affiliations.name',
            DB::raw("(SELECT currencies.symbol FROM currencies
                        WHERE currencies.id = 1) as symbol"),
            DB::raw("coalesce( (SELECT FORMAT(SUM(donations.gross_amount)/100,2) FROM donations
                        WHERE donations.affiliation_id = affiliations.id GROUP BY affiliations.id), 0 ) as total_donations"),
            DB::raw("coalesce( (SELECT FORMAT(SUM(donations.net_amount)/100, 2) FROM donations
                        WHERE donations.affiliation_id = affiliations.id GROUP BY affiliations.id), 0 ) as net_donations")
            )
            ->get();

        return $affiliationDonations;
    }
}
