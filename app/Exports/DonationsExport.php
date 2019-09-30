<?php

namespace App\Exports;

use App\Donation;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use App\Repositories\Contracts\DonationRepositoryInterface;

class DonationsExport implements FromCollection, WithHeadings, WithMapping
{

    /**
     * Organization.
     * @var App\Organization
     */
    public $organization;

    private $repo;

    /**
     * Create a new controller instance.
     * @param  App\Organization $organization
     * @param  App\Repositories\Contracts\DonationRepositoryInterface $repo
     * @return void
     */
    public function __construct(
        $organization,
        DonationRepositoryInterface $repo
    ) {
        $this->organization = $organization;
        $this->repo = $repo;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->repo->getDonationsList($this->organization->id, null, false);
    }

    /**
     * Function to display headings
     */
    public function headings(): array
    {
        return [
            __('Name'),
            __('Donation'),
            __('Net'),
            __('Campaign'),
            __('Reward'),
            __('Fund'),
            __('Time')
        ];
    }

    public function map($donation): array
    {
        if (empty($donation->net_amount)) {
            $donation->net_amount = '0.00';
        }
        return [
            $donation->full_name,
            $donation->symbol.$donation->gross_amount,
            $donation->symbol.$donation->net_amount,
            $donation->name,
            $donation->title,
            ucfirst($donation->entry_type) . ': '. ((ucfirst($donation->entry_type) == 'Online') ? ucfirst($donation->card_brand) : ucfirst($donation->donation_method)),
            Carbon::parse($donation->created_at)->format('n/j/Y g:i A')
        ];
    }
}
