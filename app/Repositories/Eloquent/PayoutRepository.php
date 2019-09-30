<?php

namespace App\Repositories\Eloquent;

use Illuminate\Http\Request;
use App\Repositories\Contracts\PayoutRepositoryInterface;


class PayoutRepository extends Repository implements PayoutRepositoryInterface
{
    /**
     * Get the donors list for the given organization
     *
     * @param integer $organizationId
     * @param integer $limit
     *
     * @return App\Payout
     */
    public function getPayoutsList($organizationId, $limit = 10)
    {
        $query = $this->model->select('payouts.issue_date', 'payouts.start_date', 'payouts.end_date', 'payouts.gross_amount', 'payouts.deposit_amount', 'campaigns.name', 'organization_connected_accounts.nickname')
            ->join('campaigns', 'payouts.campaign_id', '=', 'campaigns.id')
            ->join('organization_connected_accounts', 'payouts.organization_connected_account_id', '=', 'organization_connected_accounts.id')
            ->where('payouts.organization_id', $organizationId)
            ->whereNull('payouts.deleted_at');

        $sortOptions = json_decode(request()->query('sort'));
        if (isset($sortOptions->fieldName) && ! empty($sortOptions->fieldName)
            && isset($sortOptions->order) && ! empty($sortOptions->order)) {
            $query->orderBy($sortOptions->fieldName, $sortOptions->order);
        } else {
            $query->orderBy('issue_date', 'desc');
        }

        if (!empty(request()->query('campaign'))) {
            $query->where('payouts.campaign_id', request()->query('campaign'));
        }
        if (!empty(request()->query('account'))) {
            $query->where('payouts.organization_connected_account_id', request()->query('account'));
        }

        return $query->paginate($limit);

    }

}
