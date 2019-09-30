<?php

namespace App\Http\Controllers\Api\Organization;

use App\Organization;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrganizationResource;
use App\Http\Requests\Organization\UpdateProfileRequest;
use App\Repositories\Contracts\CountryRepositoryInterface;
use App\Repositories\Contracts\CurrencyRepositoryInterface;
use App\Repositories\Contracts\OrganizationRepositoryInterface;

class ProfileController extends Controller
{
    /**
     * Org repo object
     *
     * @var \App\Repositories\Contracts\OrganizationRepositoryInterface
     */
    protected $organizationRepo;

    /**
     * Currency repo object
     *
     * @var \App\Repositories\Contracts\CurrencyRepositoryInterface
     */
    protected $currencyRepo;

    /**
     * Country repo object
     *
     * @var \App\Repositories\Contracts\CountryRepositoryInterface
     */
    protected $countryRepo;

    /**
     * Constructor
     *
     * @param \App\Repositories\Contracts\OrganizationRepositoryInterface $organizationRepo
     * @param \App\Repositories\Contracts\CountryRepositoryInterface $countryRepo
     * @param \App\Repositories\Contracts\CurrencyRepositoryInterface $currencyRepo
     *
     * @return void
     */
    public function __construct(
        OrganizationRepositoryInterface $organizationRepo,
        CountryRepositoryInterface $countryRepo,
        CurrencyRepositoryInterface $currencyRepo
    ) {
        $this->organizationRepo = $organizationRepo;
        $this->currencyRepo = $currencyRepo;
        $this->countryRepo = $countryRepo;
    }

    /**
     * Method to update the org profile
     *
     * @param \App\Organization                                           $organization
     * @param \App\Http\Requests\Organization\UpdateProfileRequest        $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(
        Organization $organization,
        UpdateProfileRequest $request
    ) {
        $attributes = $request->only(
            [
                'name',
                'address1',
                'address2',
                'city',
                'state',
                'zipcode',
                'phone',
                'slug',
            ]
        );

        if ($this->organizationRepo->update($organization->id, $attributes)
            && $this->updateCurrency($organization, $request)
            && $this->updateCountry($organization, $request)
        ) {
            return response()->json([]);
        }

        return response()->json(['message' => __('Bad Request')], 400);
    }

    /**
     * Method to update currency for the given org
     *
     * @param \App\]Organization $organization
     * @param \App\Http\Requests\Organization\UpdateProfileRequest $request
     *
     * @return boolean
     */
    protected function updateCurrency(Organization $organization, $request)
    {
        if (! $request->currency) {
            return false;
        }

        return $this->organizationRepo->updateCurrency(
            $organization->id,
            $this->currencyRepo->findByCode($request->currency)
        );
    }

    /**
     * Method to update country for the given org
     *
     * @param \App\]Organization $organization
     * @param \App\Http\Requests\Organization\UpdateProfileRequest $request
     *
     * @return boolean
     */
    protected function updateCountry(Organization $organization, $request)
    {
        // Country is not compulsory as default country is set
        if (! $request->country) {
            return true;
        }

        return $this->organizationRepo->updateCountry(
            $organization->id,
            $this->countryRepo->findByCode($request->country)
        );
    }
}
