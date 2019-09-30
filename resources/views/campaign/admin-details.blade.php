@extends('layouts.app')

@section('title', "RocketJar | Campaign-details")

@section('content')

<campaign-admin-details
    :organization="currentOrganization"
    :campaign-id="{{ isset($campaign->id) ? $campaign->id : '' }}"
    inline-template v-if="currentOrganization">
    <div>
        <section class="section--def pb-lg-5">
            <div class="row">
                <div class="col-lg-4 mb-3 mb-lg-0">
                    <h5 class="aleo">{{ __('Campaigns')}}</h5>
                    <h2 class="break-word">
                        @{{ campaign.name }}
                        <a v-if="campaign.status == 'inactive'" href="#" class="btn disabled btn-sm f-14 assistant p-1 text-tranform text-uppercase" role="button">{{ __('Inactive') }}</a>
                        <a v-else-if="campaign.status == 'unpublished'" href="#" class="btn disabled btn-sm f-14 assistant p-1 text-tranform text-uppercase" role="button">{{ __('Not Published') }}</a>
                    </h2>
                    <div class="d-flex progressbar-container progressbar-container--thick">
                        <div class="w-85">
                            <div class="d-flex justify-content-between mb-2">
                                <div class="f-24 aleo">@{{ fundsRaised(campaign, organization) }}  of @{{ fundRaisingGoal(campaign, organization) }} {{ __('goal') }}</div>
                            </div>
                            <div class="position-relative">
                                <div class="progress rounded-pill mb-2">
                                    <div role="progressbar" :style="'width: ' + progress(campaign) + '%'" :aria-valuenow="progress(campaign)" aria-valuemin="0" aria-valuemax="100" class="progress-bar"></div>
                                </div>
                                <div class="ref font-weight-light">@{{ progress(campaign) }}%</div>
                            </div>
                            <div class="d-flex justify-content-between">
                                <div class="f-16 aleo">@{{ endsAt(campaign) }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 pr-lg-5 mb-3 mb-lg-0 text-center">
                    <img :src="campaign.image" alt="" v-if="campaign.image" class="card-img-top rounded-0 mh-230 w-auto">
                    <div v-if="! campaign.image">
                        <svg width="100%" height="230">
                            <rect width="100%" height="100%" fill="#e2e2e2">
                            </rect>
                            <text x="50%" y="50%" fill="#222222" text-anchor="middle" alignment-baseline="central" font-weight="bold">No Image</text>
                        </svg>
                    </div>
                </div>
                <div class="col-lg-2 mb-3 mb-lg-0 px-lg-0" v-if="campaign.status == 'active' ">
                    <div class="pl-lg- ml-lg-">
                        <a :href="`${$root.rj.baseUrl}/campaign/${campaign.id}/edit`">
                            <button type="submit" class="btn btn--size16 rounded-pill mb-3 f-14">
                                {{ __('Edit') }}
                            </button>
                        </a>
                        <button type="submit" class="btn btn--transparent btn--size16 rounded-pill mb-3 f-14"
                        @click.prevent="showPreviewModal()">
                            {{ __('View') }}
                        </button>
                        <button type="submit" class="btn btn--transparent btn--size16 rounded-pill mb-3 f-14 showShareModal" data-modal-id="campaign-share-modal">
                            {{ __('Share') }}
                        </button>
                        <a :href="`${$root.rj.baseUrl}/organization/${organization.id}/donation/create`">
                            <button type="submit" class="btn btn--transparent btn--size16 rounded-pill mb-3 f-14">
                            {{ __('Record Donation') }}
                            </button>
                        </a>
                    </div>
                </div>
                <div class="col-lg-2 mb-3 mb-lg-0 px-lg-0" v-if="campaign.status == 'inactive'">
                    <div class="pl-lg- ml-lg-">
                        <button type="submit" class="btn btn--transparent btn--size16 rounded-pill mb-3 f-14"
                        @click.prevent="reactivateCampaign(campaign.id)">
                            {{ __('Reactivate') }}
                        </button>
                    </div>
                    <div>
                        <p>This campaign is currently <strong>inactive</strong></p>
                    </div>
                </div>
            </div>
        </section>

        <share-modal inline-template
            modal-id="campaign-share-modal"
            modal-title="{{ __('Share this Campaign') }}"
            :modal-subtitle="campaign.name"
            :share-url="'{{ url('/') }}/' + organization.slug + '/' + campaign.slug"
            :share-headline="campaign.name"
            :share-text="'{{ __('Fund projects that matter') }} {{ url('/') }}' + organization.slug + '/' + campaign.slug">
            @include('partials.modals.modal-share')
        </share-modal>

        <section class="full-width bg-grey py-5">
            <div class="container">
                <div class="row">
                    <div class="col-sm col-md-4 col-xl pr-xl-0">
                        <div class="d-flex align-items-center flat-card">
                                <div class="flat-card__icon">
                                    <img src="{{ asset('images/total-donation.png') }}" class="img-fluid" alt="Total Donation">
                                </div>
                            <div class="ml-1">
                                <p class="mb-0 aleo">{{ __('Total Donations') }}</p>
                                <h2><sup class="f-18">$ </sup>@{{ $root.donationMoney(campaignStats.total_donation) }}</h2>
                            </div>
                            <a :href="`${$root.rj.baseUrl}/donations?campaign=${campaign.id}`" class="stretched-link"></a>
                        </div>
                    </div>
                    <div class="col-sm col-md-4 col-xl pr-xl-0 pl-xl-2">
                        <div class="d-flex align-items-center flat-card">
                                <div class="flat-card__icon">
                                    <img src="{{ asset('images/net-donation.png') }}" class="img-fluid" alt="Net Donation">
                                </div>
                            <div class="ml-1">
                                <p class="mb-0 aleo">{{ __('Net Donations') }}</p>
                                <h2><sup class="f-18">$ </sup>@{{ $root.donationMoney(campaignStats.total_donation) }}</h2>
                            </div>
                            <a :href="`${$root.rj.baseUrl}/donations?campaign=${campaign.id}`" class="stretched-link"></a>
                        </div>
                    </div>
                    <div class="w-100 d-md-none"></div>
                    <div class="col-sm col-md-4 col-xl pr-xl-0 pl-xl-2">
                        <div class="d-flex align-items-center flat-card">
                                <div class="flat-card__icon">
                                    <img src="{{ asset('images/average-donation.png') }}" class="img-fluid" alt="Average Donation">
                                </div>
                            <div class="ml-1">
                                <p class="mb-0 aleo">{{ __('Average Donation') }}</p>
                                <h2>@{{ $root.donationMoney(campaignStats.average_donation) }}</h2>
                            </div>
                            <a :href="`${$root.rj.baseUrl}/donations?campaign=${campaign.id}`" class="stretched-link"></a>
                        </div>
                    </div>
                    <div class="col-sm col-md-4 col-xl pr-xl-0 pl-xl-2">
                        <div class="d-flex align-items-center flat-card">
                                <div class="flat-card__icon">
                                    <img src="{{ asset('images/total-donors.png') }}" class="img-fluid" alt="Total Donors">
                                </div>
                            <div class="ml-1">
                                <p class="mb-0 aleo">{{ __('Total Donors') }}</p>
                                <h2>@{{ campaignStats.total_donors }}</h2>
                            </div>
                            @php
                                $currentOrgId = auth()->user()->organization->id;
                                $role = auth()->user()->findAssociatedOrganization($currentOrgId)->pivot->role ;
                            @endphp
                            @if (in_array($role, ['owner']) )
                                <a :href="`${$root.rj.baseUrl}/donors?campaign=${campaign.id}`" class="stretched-link"></a>
                            @endif
                        </div>
                    </div>
                    <div class="w-100 d-md-none"></div>
                    <div class="col-sm col-md-4 col-xl pl-xl-2">
                        <div class="d-flex align-items-center flat-card">
                                <div class="flat-card__icon">
                                    <img src="{{ asset('images/rewards-earned.png') }}" class="img-fluid" alt="Rewards Earned">
                                </div>
                            <div class="ml-1">
                                <p class="mb-0 aleo">{{ __('Rewards Earned') }}</p>
                                <h2>@{{ campaignStats.rewards_earned }}</h2>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="donation-graph my-5">
                    <template v-if="dataset.length">
                    <div class="d-flex justify-content-between">
                        <h3 class="aleo">{{ __('Donations to Date') }}</h3>
                        <div class="text-muted aleo f-16">
                            @{{ chartStartDate }} - @{{ chartEndDate }}
                        </div>
                    </div>
                    <div class="line-chart">
                        {{-- <img src="../images/donation-graph.jpg" alt="Donation Graph"> --}}
                        <svg style="width:0; height:0; position:absolute;" aria-hidden="true" focusable="false">
                            <defs>
                                <linearGradient id="btcFill" x1="1" x2="1" y1="0" y2="1">
                                    <stop offset="0%" stop-color="#67bad3"></stop>
                                    <stop offset="100%" stop-color="#67bad3"></stop>
                                </linearGradient>
                            </defs>
                        </svg>
                        <trend-chart v-if="dataset.length"
                                    :datasets="[{data: dataset, fill: true, className: 'curve-btc'}]"
                                    :labels="labels"
                                    :min="0"
                                    :grid="grid"
                        />
                    </div>
                    </template>
                </div>

                <div class="table-responsive mb-3">
                    <h3 class="aleo">{{ __('Recent Donations') }}</h3>
                    <table class="table table-striped table-striped--def">
                        <thead>
                            <tr>
                                <th scope="col">{{ __('Name') }}</th>
                                <th scope="col">{{ __('Amount') }}</th>
                                <th scope="col">{{ __('Net') }}</th>
                                <th scope="col" colspan="2">{{ __('Fund') }}</th>
                                <th scope="col">{{ __('Time') }}</th>
                                <th scope="col">&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="recentDonation in recentDonations" v-on:click="clickRow(recentDonation.donor.id)" class='clickable-row'>
                                <th>@{{ recentDonation.donor.first_name +" "+recentDonation.donor.last_name }}</th>
                                <td>@{{ organization.currency.symbol+""+$root.donationMoney(recentDonation.gross_amount) }}</td>
                                <td>
                                    @{{ organization.currency.symbol+""+$root.donationMoney(recentDonation.net_amount) }}
                                </td>
                                <td colspan="2">
                                    <span v-if="_.capitalize(recentDonation.entry_type) == 'Online'">@{{ _.capitalize(recentDonation.entry_type) + ': ' + _.capitalize(recentDonation.card_brand) }}</span>
                                    <span v-else>@{{ _.capitalize(recentDonation.entry_type) + ': ' + _.capitalize(recentDonation.donation_method) }}</span>
                                </td>
                                <td>
                                @{{ $root.convertUTCToBrowser(recentDonation.created_at, 'MM/DD/Y') }} &nbsp;&nbsp;&nbsp;
                                @{{ $root.convertUTCToBrowser(recentDonation.created_at, 'h:mm A') }}
                                </td>
                                <td><span class="fa fa-search"></span></td>
                            </tr>
                        </tbody>
                    </table>
                    <a :href="`${$root.rj.baseUrl}/donations?campaign=${campaign.id}`">
                        <button type="submit" class="btn btn--transparent btn--size15 rounded-pill text-tranform-none btn--lightborder mb-3 f-12">
                            {!! __('More Donations<i class="fas fa-chevron-right f-8 ml-1"></i>') !!}
                        </button>
                    </a>
                </div>
            </div>
            <!-- /.container -->
        </section>

        <section class="bg-white py-5">
            <div class="row row-eq-height">
                <div class="col-lg-8 mb-5 mb-xl-0">
                    <h3 class="aleo pl-2">{{ __('Pay-Out History') }}</h3>
                    <div class="table-responsive">
                        <table class="table table-striped table-striped--trans">
                            <thead>
                                <tr>
                                    <th scope="col">{{ __('Issue Date') }}</th>
                                    <th scope="col">{{ __('Time Period') }}</th>
                                    <th scope="col">{{ __('Total Donations') }}</th>
                                    <th scope="col">{{ __('Net Deposit') }}</th>
                                    <th scope="col">{{ __('Account') }}</th>
                                    <th scope="col">&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="payout in payoutHistory">
                                    <th scope="row">@{{ $root.convertUTCToBrowser(payout.issue_date, 'MMM. DD, YYYY') }}</th>
                                    <td>@{{ $root.convertUTCToBrowser(payout.start_date, 'M/D/YY') + (payout.end_date ? ' - ' + $root.convertUTCToBrowser(payout.end_date, 'M/D/YY') : '') }}</td>
                                    <td>@{{ payout.gross_amount}}</td>
                                    <td>@{{ payout.deposit_amount }}</td>
                                    <td>@{{ payout.organization_connected_account.account_nickname }}</td>
                                    <td><span class="fa fa-search"></span></td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="cta pl-2">
                            <a :href="`${$root.rj.baseUrl}/payouts?campaign=${campaign.id}`">{{ __('View All') }}</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <h3 class="aleo ml-xl-5 pl-4">{{ __('Pay-Out Preferences') }}</h3>
                    <div class="bg-light-gray p-4 rounded ml-xl-5">
                        <div class="payout" v-if="campaign.payout_connected_account">
                            <div class="payout__title">{{ __('Payment Account') }}</div>
                            <div class="payout__subtitle">@{{ campaign.payout_connected_account.nickname }}</div>
                            <div class="payout__smalltitle" v-if="campaign.payout_method == 'bank' ">{{-- __('USBANK Account #34002219') --}}
                                <span v-if="campaign.payout_connected_account.external_account_name">@{{ campaign.payout_connected_account.external_account_name}}</span>
                                <span v-if="campaign.payout_connected_account.external_account_id">{{ __('Account') }} #@{{ campaign.payout_connected_account.external_account_id}}</span>
                            </div>
                        </div>
                        <div class="payout">
                            <div class="payout__title">{{ __('Payment Schedule') }}</div>
                            <div class="payout__subtitle">@{{ _.capitalize(campaign.payout_schedule) }}</div>
                        </div>
                        <div class="cta"><a :href="`${$root.rj.baseUrl}/connected-account`">{{ __('Edit') }}</a></div>
                    </div>
                </div>
            </div>
        </section>

        <section class="rewards full-width" v-if="rewards.length > 0">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <h3 class="aleo mb-3">{{ __('Rewards') }}</h3>
                    </div>
                </div>
                <div class="row" v-for="reward in rewards">
                    <div class="col">
                        <div class="rewards-card">
                            <div class="row align-items-center">
                                <div class="col-md-2 col-lg-2 px-lg-0">
                                    <sup class="position-absolute top-15">$</sup> <span class="rewards-card__amount rewards-card__amount--fs-35 ml-2 ml-lg-2"> @{{ reward.min_amount }}</span>
                                </div>
                                <div class="col-md-6 col-lg-6">
                                    <div class="d-flex align-items-center">
                                        <div class="mr-3" v-if="reward.image">
                                            <img :src="reward.image" alt="Rewards" class="float-left rounded rewards-card--image" >
                                        </div>
                                        <div><div class="rewards-card__title break-word">@{{ reward.title }}</div>
                                        <div class="rewards-card__subtitle break-word">@{{ reward.description }}</div></div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="rewards-card__status" v-if="reward.quantity_rewarded">
                                        @{{ reward.quantity_rewarded }} <span>{{ __('Claimed') }}</span>
                                    </div>
                                    <div class="rewards-card__status" v-else>
                                        0 <span>{{ __('Claimed') }}</span>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="rewards-card__status">
                                        @{{ reward.quantity - reward.quantity_rewarded }} <span>{{ __('Remaining') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="cta mt-3">
                            <a :href="`${$root.rj.baseUrl}/campaign/${campaign.id}/edit`">{{ __('Edit Rewards') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="our-story full-width">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8">
                        <h3 class="aleo">{{ __('Our Story') }}</h3>
                        {{-- <p class="f-16">@{{ campaign.description }}</p> --}}
                        <p class="f-16 preview--html break-word" v-html="$root.renderMd(campaign.description)"></p>
                        <a :href="`${$root.rj.baseUrl}/campaign/${campaign.id}/edit`">
                            <button type="submit" class="btn btn--transparent btn--size2 rounded-pill btn--lightborder">
                            {{ __('Edit') }}
                            </button>
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <section class="reactive-campaign" v-if="campaign.status == 'active'">
            <div class="row">
                <div class="col-lg-8">
                    <h3 class="aleo">{{ __('Ready to stop this campaign?') }}</h3>
                    <button @click.prevent="deactivateCampaign(campaign.id)" class="btn rounded-pill mt-3">{{ __('Deactivate Campaign') }}</button>
                </div>
            </div>
        </section>
        @include('campaign.preview.modal-campaign-view', [
            'modalId' => 'campaign-view',
        ])
    </div>
</campaign-admin-details>
@endsection
