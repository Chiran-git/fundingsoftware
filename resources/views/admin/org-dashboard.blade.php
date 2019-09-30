@extends('layouts.app')

@section('title', __('Dashboard'))

@section('content')
<org-dashboard
    :organization="currentOrganization"
    inline-template
    v-if="currentOrganization">
    <section class="section--def">
        <div class="row mb-5 align-items-end">
            <div class="col-12 col-md-6 mr-md-auto">
                <h2 class="mb-1 aleo" v-if="$root.user.first_name">@{{ getGreeting() }}</h2>
                <h3 class="aleo mb-1 font-weight-normal f-24">{{ __('Here’s what’s happening with your campaigns.')}}</h3>
            </div>
            @if (auth()->user()->currentRole() != 'campaign-admin')
            <div class="col-12 col-md-auto">
                <a href="{{ route('campaign.create') }}" class="btn btn--outline rounded-pill btn--size6 mt-2 mt-md-0 ml-md-3 f-14">{{ __('New Campaign') }}</a>
            </div>
            @endif
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
                            <h2><sup>@{{organization.currency.symbol}}</sup>@{{ donationStats.all_time_donations }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-sm col-md-4 col-xl mb-3 pr-md-0">
                    <div class="d-flex align-items-center flat-card flat-card--sec">
                            <div class="flat-card--sec__icon mr-2">
                                <img src="{{ asset('images/icons/card-4.png') }}" alt="Net Donation">
                            </div>
                        <div class="ml-1">
                            <p class="mb-0 aleo">{{ __('Average Donation') }}</p>
                            <h2><sup>@{{organization.currency.symbol}}</sup>@{{ donationStats.average_donation }}</h2>
                        </div>
                    </div>
                </div>
                <div class="w-100 d-md-none"></div>
                <div class="col-sm col-md-4 col-xl mb-3 pr-md-0">
                    <div class="d-flex align-items-center flat-card flat-card--sec">
                            <div class="flat-card--sec__icon mr-2">
                                <img src="{{ asset('images/icons/card-2.png') }}" alt="Average Donation">
                            </div>
                        <div class="ml-1">
                            <p class="mb-0 aleo">{{ __('Total Donors') }}</p>
                            <h2>@{{ donationStats.total_donors }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-sm col-md-4 col-xl mb-3 pr-md-0 pr-xl-3">
                    <div class="d-flex align-items-center flat-card flat-card--sec">
                            <div class="flat-card--sec__icon mr-2">
                                <img src="{{ asset('images/icons/card-1.png') }}" alt="Total Donors">
                            </div>
                        <div class="ml-1">
                            <p class="mb-0 aleo">{{ __('Active Campaigns') }}</p>
                            <h2>@{{ donationStats.active_campaigns }}</h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="donation-graph my-5">
                <div class="d-flex justify-content-between">
                    <h3 class="aleo">{{ __('Recent Donations') }} <span class="font-italic text-muted f-16 font-weight-normal pl-md-1 d-block d-md-inline-block">{{ __('All Campaigns') }}</span></h3>
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
                <div v-if="! dataset.length">
                    {{ __('No donations in last 15 days.') }}
                </div>
            </div>
            <div class="row">
                <div class="col col-md-7 mb-3">
                    <h3 class="aleo text-capitalize">{{ __('Recent Donors') }}</h3>
                    <div class="table-responsive">
                        <table class="table table-striped table-striped--alternate mb-2">
                            <thead>
                                <tr>
                                <th scope="col" width="25%"><a href="#">{{ __("Name") }}</a></th>
                                <th scope="col" width="15%"><a href="#">{{ __('Donation') }}</a></th>
                                <th scope="col" width="28%"><a href="#">{{ __('Campaign') }}</a></th>
                                <th scope="col" width="24%">{{ __('Time') }}</th>
                                <th scope="col" width="8%"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(donor,index) in recentDonors" v-if="index < 5" v-on:click="clickRow(donor.donor.id)" class='clickable-row'>
                                    <th scope="row">@{{ donor.donor.first_name+" "+donor.donor.last_name }}</th>
                                    <td>@{{ $root.donationMoney(donor.gross_amount, donor.currency.symbol) }}</td>
                                    <td class="text-capitalize">@{{ donor.campaign.name }}</td>
                                    <td>@{{ $root.convertUTCToBrowser(donor.created_at, 'M/D/YY h:mmA') }}</td>
                                    <td>
                                        <a
                                        :href="`${$root.rj.baseUrl}/organization/${organization.id}/donor/${donor.donor.id}`"
                                        class="ml-2 text-muted-2">
                                        <span class="fa fa-search form-control-feedback"></span></a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <a href="{{ route('donations.index') }}" class="text-link--red font-weight-bold">View All Donations <i class="fas fa-angle-double-right f-8"></i></a>
                </div>
                <div class="col col-md-5 mb-3">
                    <h3 class="aleo text-capitalize">All-time top donors</h3>
                    <div class="table-responsive">
                        <table class="table table-striped table-striped--alternate">
                            <thead>
                                <tr>
                                <th scope="col" width="65%"><a href="#">{{ __("Name") }}</a></th>
                                <th scope="col" width="23%"><a href="#">{{ __('Total') }}</a></th>
                                <th scope="col" width="8%"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(topDonor,index) in topDonors" v-if="index < 5" v-on:click="clickRow(topDonor.donor.id)" class='clickable-row'>
                                    <th scope="row">@{{ topDonor.donor.first_name+" "+topDonor.donor.last_name }}</th>
                                    <td>@{{ $root.donationMoney(topDonor.gross_amount, topDonor.currency.symbol) }}</td>
                                    <td>
                                        <a :href="`${$root.rj.baseUrl}/organization/${organization.id}/donor/${topDonor.donor.id}`"
                                        class="ml-2 text-muted-2">
                                        <span class="fa fa-search form-control-feedback"></span></a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <section class="my-5 py-5 full-width bg-grey">
            <div class="container">
                <div class="row">
                    <div class="col-12 mb-3 mt-4 pt-3">
                        <h2>{{ __('My Campaigns') }}</h2>
                        <div class="row mb-2">
                            <div class="col-md-4 d-flex" v-for="(campaign, index) in campaigns.data" v-if="index < 3">
                                <div class="card mb-4 flex-fill">
                                    <div class="form_wrapper">
                                        <img :src="campaign.image" alt="" v-if="campaign.image" class="card-img-top">
                                        <div class="image-uploader btn-file card-img-top rounded-0" v-if="! campaign.image">
                                            <a :href="`${$root.rj.baseUrl}/campaign/${campaign.id}/edit`">
                                                <img src="{{ asset('images/icons/add-a-photo.png') }}" alt="">
                                            <span class="mb-1 f-16 d-block mt-3">{{ __("Add a Photo")}}</span></a>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <h3 class="aleo break-word">@{{ campaign.name }}</h3><a href="#" class="ml-2 text-muted"><span class="fa fa-search text-grey-5"></span></a>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <div class="progressbar-container progressbar-container--grey">
                                            <div class="f-20 aleo"><span class="font-weight-bold">@{{ fundsRaised(campaign, organization) }}</span> {{ __('of') }} @{{ fundRaisingGoal(campaign, organization) }} {{ __('Raised') }}</div>
                                            <div class="progress rounded-pill w-100 mb-2">
                                                <div class="progress-bar" :style="'width: ' + progress(campaign) + '%'" :aria-valuenow="progress(campaign)" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                            <div class="f-16 aleo">@{{ endsAt(campaign) }}</div>
                                        </div>
                                    </div>
                                    <a :href="`${$root.rj.baseUrl}/campaign/${campaign.id}/details`" class="stretched-link"></a>
                                </div>
                            </div>
                        </div>
                        <a href="{{ route('campaign.index') }}" class="text-link--red font-weight-bold">View All Campaigns <i class="fas fa-angle-double-right f-8"></i></a>
                    </div>
                </div>
            </div>
        </section>
    </section>
</org-dashboard>
@endsection
