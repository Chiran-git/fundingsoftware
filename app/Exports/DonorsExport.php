<?php

namespace App\Exports;

use App\Donor;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use App\Repositories\Contracts\DonorRepositoryInterface;

class DonorsExport implements FromCollection, WithHeadings, WithMapping
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
     * @param  App\Repositories\Contracts\DonorRepositoryInterface $repo
     * @return void
     */
    public function __construct(
        $organization,
        DonorRepositoryInterface $repo
    ) {
        $this->organization = $organization;
        $this->repo = $repo;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->repo->getDonorsList($this->organization->id, null, false);
    }

    /**
     * Function to display headings
     */
    public function headings(): array
    {
        return [
            __('Name'),
            __('Email'),
            __('Total Donated'),
            __('Number of Donations'),
        ];
    }

    public function map($donor): array
    {
        return [
            $donor->full_name,
            $donor->email,
            $this->organization->currency->symbol.$donor->total_donated,
            $donor->total_donations,
        ];
    }
}
