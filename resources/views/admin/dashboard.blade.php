@extends('layouts.app')

@section('title', "RocketJar")

@section('content')
<admin-dashboard inline-template>
    <section class="section--def">
        <div class="row mb-1">
            <div class="col-12 col-md-6">
                <div class="px-x-2 mb-4">
                    <h2 class="mb-1" v-if="$root.user">@{{ getGreeting() }}</h2>
                    <h3 class="aleo mb-1 font-weight-normal f-24">{{ __('Here’s what’s happening at RocketJar.')}}</h3>
                </div>
            </div>
        </div>
        <div class="section__content">
            <div class="row">
                <div class="col-sm col-md-4 col-xl mb-3 pr-md-0">
                    <div class="d-flex align-items-center flat-card flat-card--sec">
                            <div class="flat-card--sec__icon mr-2">
                                <img src="{{ asset('images/icons/card-3.png') }}" alt="Total Donation">
                            </div>
                        <div class="ml-1">
                            <p class="mb-0 aleo">{{ __('All-Time Donations') }}</p>
                            <h2><sup>$</sup>@{{ stats.allTimeDonation }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-sm col-md-4 col-xl mb-3 pr-md-0">
                    <div class="d-flex align-items-center flat-card flat-card--sec">
                            <div class="flat-card--sec__icon mr-2">
                                <img src="{{ asset('images/icons/card-4.png') }}" alt="Net Donation">
                            </div>
                        <div class="ml-1">
                            <p class="mb-0 aleo">{{ __('Active Organizations') }}</p>
                            <h2>@{{ stats.activeOrganization }}</h2>
                        </div>
                        <a :href="`${$root.rj.baseUrl}/admin/organizations`" class="stretched-link"></a>
                    </div>
                </div>
                <div class="w-100 d-md-none"></div>
                <div class="col-sm col-md-4 col-xl mb-3 pr-md-0">
                    <div class="d-flex align-items-center flat-card flat-card--sec">
                            <div class="flat-card--sec__icon mr-2">
                                <img src="{{ asset('images/icons/card-2.png') }}" alt="Average Donation">
                            </div>
                        <div class="ml-1">
                            <p class="mb-0 aleo">{{ __('Active Campaigns') }}</p>
                            <h2>@{{ stats.activeCampaigns }}</h2>
                        </div>
                        <a :href="`${$root.rj.baseUrl}/admin/campaigns?status=active`" class="stretched-link"></a>
                    </div>
                </div>
                <div class="col-sm col-md-4 col-xl mb-3 pr-md-0 pr-xl-3">
                    <div class="d-flex align-items-center flat-card flat-card--sec">
                            <div class="flat-card--sec__icon mr-2">
                                <img src="{{ asset('images/icons/card-1.png') }}" alt="Total Donors">
                            </div>
                        <div class="ml-1">
                            <p class="mb-0 aleo">{{ __('Complete Campaigns') }}</p>
                            <h2>@{{ stats.completeCampaigns }}</h2>
                        </div>
                        <a :href="`${$root.rj.baseUrl}/admin/campaigns?status=completed`" class="stretched-link"></a>
                    </div>
                </div>
            </div>
        </div>
        <section class="my-5">
            <div class="row">
                <div class="col-12">
                    <ul class="list-fixed-head-js list-fixed-head--rounded">
                        <li class="head-fixed">
                            <ul>
                                <li class="heading">
                                    <strong>{{ __('New Organizations') }}</strong>
                                </li>
                                <li class="p-2">
                                    <strong>{{ __('Created') }}</strong>
                                </li>
                                <li class="p-2">
                                    <strong>{{ __('Campaigns') }}</strong>
                                </li>
                                <li class="p-2">
                                    <strong>{{ __('Donations') }}</strong>
                                </li>
                                <li class="p-2">
                                    <strong>{{ __('Net') }}</strong>
                                </li>
                                <li class="p-2">
                                    <strong></strong>
                                </li>
                            </ul>
                        </li>
                        <div class="list--content">
                            <li class="box clickable-row" v-for="organization in organizations">
                                <ul @click.prevent="clickRow(organization.id)">
                                    <li class="p-2">
                                        <img v-if="organization.logo" :src="organization.logo" class="rounded-circle org-logo mr-2">
                                        <svg v-else="organization.logo" width="45" height="45" class="rounded-circle org-logo mr-2">
                                            <rect width="100%" height="100%" fill="#e2e2e2">
                                            </rect>
                                            <text x="50%" y="50%" fill="#222222" text-anchor="middle" alignment-baseline="central" font-weight="bold" class="f-8">{{ __('No Image') }}</text>
                                        </svg>
                                        <p class="text-capitalize aleo">
                                            @{{ organization.name }}
                                        </p>
                                    </li>
                                    <li class="p-2">
                                        <p>@{{ $root.convertUTCToBrowser(organization.created_at, 'M/DD/YY') }}
                                        </p>
                                    </li>
                                    <li class="p-2">
                                        <p>@{{ organization.no_of_campaigns }}
                                        </p>
                                    </li>
                                    <li class="p-2">
                                        <p class="f-20"><sup>{{ __('$') }}</sup>@{{ $root.donationMoney(organization.total_donations/100) }}
                                        </p>
                                    </li>
                                    <li class="p-2">
                                        <p class="f-20"><sup>{{ __('$') }}</sup>@{{ $root.donationMoney(organization.net_donations/100) }}
                                        </p>
                                    </li>
                                    <li class="p-2">
                                        <a :href="`${$root.rj.baseUrl}/admin/impersonate/${organization.id}`" class="ml-2">
                                            <span class="fa fa-search form-control-feedback"></span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </div>
                    </ul><!-- /.list-members -->
                    <a :href="`${$root.rj.baseUrl}/admin/organizations`" class="btn btn--transparent btn--size15 rounded-pill btn--lightborder">
                            {{ __('View all organizations') }}
                    </a>
                </div>
            </div>
        </section>

        <section class="my-5 py-5">
            <div class="row">
                <div class="col-12">
                    <ul class="list-fixed-head list-fixed-head--rounded list-fixed-head--colm-five">
                        <li class="head-fixed">
                            <ul>
                                <li class="heading">
                                    <strong>{{ __('Active Campaigns') }}</strong>
                                </li>
                                <li class="p-2">
                                    <strong>{{ __('Created') }}</strong>
                                </li>
                                <li class="p-2">
                                    <strong>{{ __('End Date') }}</strong>
                                </li>
                                <li class="p-2">
                                    <strong>{{ __('Progress') }}</strong>
                                </li>
                                <li class="p-2">
                                    <strong></strong>
                                </li>
                            </ul>
                        </li>
                        <div class="list--content">
                            <li class="box clickable-row" v-for="campaign in activeCampaigns">
                                <ul @click.prevent="clickCampaignRow(campaign.id)">
                                    <li class="p-2">
                                        <img v-if="campaign.image" :src="campaign.image" :alt="campaign.name" class="rounded-circle org-logo mr-2">
                                        <svg v-if="! campaign.image" width="45" height="45" class="rounded-circle org-logo mr-2">
                                            <rect width="100%" height="100%" fill="#e2e2e2">
                                            </rect>
                                            <text x="50%" y="50%" fill="#222222" text-anchor="middle" alignment-baseline="central" font-weight="bold" class="f-8">{{ __('No Image') }}</text>
                                        </svg>
                                        <p class="text-capitalize aleo">
                                            @{{ campaign.name }}
                                            <span class="d-block f-12 assistant">@{{ campaign.organization.name }}</span>
                                        </p>
                                    </li>
                                    <li class="p-2">
                                        <p>@{{ $root.convertUTCToBrowser(campaign.created_at, 'M/DD/YY') }}</p>
                                    </li>
                                    <li class="p-2">
                                        <p v-if="campaign.end_date">@{{ $root.convertUTCToBrowser(campaign.end_date, 'M/DD/YY') }}</p>
                                        <p v-else>-</p>
                                    </li>
                                    <li class="p-2">
                                        <p class="f-20">@{{ campaign.funds_raised ? campaign.organization.currency.symbol+$root.donationMoney(campaign.funds_raised): campaign.organization.currency.symbol+0}} of @{{ campaign.organization.currency.symbol+$root.donationMoney(campaign.fundraising_goal)}}</p>
                                    </li>
                                    <li class="p-2">
                                        <a :href="`${$root.rj.baseUrl}/admin/campaign/${campaign.id}/details`" class="ml-2">
                                            <span class="fa fa-search form-control-feedback"></span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </div>
                    </ul><!-- /.list-members -->
                    <a :href="`${$root.rj.baseUrl}/admin/campaigns?status=active`" class="btn btn--transparent btn--size15 rounded-pill btn--lightborder">
                            {{ __('View active campaigns') }}
                    </a>
                </div>
            </div>
        </section>

        <section class="my-5 py-5">
            <div class="row">
                <div class="col-12">
                    <ul class="list-fixed-head list-fixed-head--rounded list-fixed-head--colm-five">
                        <li class="head-fixed">
                            <ul>
                                <li class="heading">
                                    <strong>{{ __('Completed Campaigns') }}</strong>
                                </li>
                                <li class="p-2">
                                    <strong>{{ __('Created') }}</strong>
                                </li>
                                <li class="p-2">
                                    <strong>{{ __('End Date') }}</strong>
                                </li>
                                <li class="p-2">
                                    <strong>{{ __('Progress') }}</strong>
                                </li>
                                <li class="p-2">
                                    <strong></strong>
                                </li>
                            </ul>
                        </li>
                        <div class="list--content">
                            <li class="box clickable-row" v-for="campaign in completedCampaigns">
                                <ul @click.prevent="clickCampaignRow(campaign.id)">
                                    <li class="p-2">
                                        <img v-if="campaign.image" :src="campaign.image" :alt="campaign.name" class="rounded-circle org-logo mr-2">
                                        <svg v-if="! campaign.image" width="45" height="45" class="rounded-circle org-logo mr-2">
                                            <rect width="100%" height="100%" fill="#e2e2e2">
                                            </rect>
                                            <text x="50%" y="50%" fill="#222222" text-anchor="middle" alignment-baseline="central" font-weight="bold" class="f-8">{{ __('No Image') }}</text>
                                        </svg>
                                        <p class="text-capitalize aleo">
                                            @{{ campaign.name }}
                                            <span class="d-block f-12 assistant">@{{ campaign.organization.name }}</span>
                                        </p>
                                    </li>
                                    <li class="p-2">
                                        <p>@{{ $root.convertUTCToBrowser(campaign.created_at, 'M/DD/YY') }}</p>
                                    </li>
                                    <li class="p-2">
                                        <p v-if="campaign.end_date">@{{ $root.convertUTCToBrowser(campaign.end_date, 'M/DD/YY') }}</p>
                                        <p v-else="">-</p>
                                    </li>
                                    <li class="p-2">
                                        <p class="f-20">@{{ campaign.funds_raised ? campaign.organization.currency.symbol+$root.donationMoney(campaign.funds_raised): campaign.organization.currency.symbol+0}} of @{{ campaign.organization.currency.symbol+$root.donationMoney(campaign.fundraising_goal)}}</p>
                                    </li>
                                    <li class="p-2">
                                        <a :href="`${$root.rj.baseUrl}/admin/campaign/${campaign.id}/details`" class="ml-2">
                                            <span class="fa fa-search form-control-feedback"></span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </div>
                    </ul><!-- /.list-members -->
                    <a :href="`${$root.rj.baseUrl}/admin/campaigns?status=completed`" class="btn btn--transparent btn--size15 rounded-pill btn--lightborder">
                            {{ __('View completed campaigns') }}
                    </a>
                </div>
            </div>
        </section>

    </section>
</admin-dashboard>
@endsection
