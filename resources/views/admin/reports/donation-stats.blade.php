@extends('layouts.app')

@section('title', "RocketJar")

@section('content')
<donation-stats inline-template="">
    <section class="section--def">
        <div class="row mb-1">
            <div class="col-4 col-md-4">
                <div class="px-x-2 mb-4">
                    <h2 class="mb-1" v-if="$root.user">
                        {{ __('Donation Stats') }}
                    </h2>
                </div>
            </div>
        </div>
        <div class="section__content">
            <div class="row">
                <div class="col-sm col-md-3 col-xl mb-3 pr-md-0">
                    <div class="d-flex align-items-center flat-card flat-card--sec">
                        <div class="flat-card--sec__icon mr-2">
                            <img alt="Total Donation" src="{{ asset('images/icons/card-4.png') }}">
                            </img>
                        </div>
                        <div class="ml-1">
                            <p class="mb-0 aleo">
                                {{ __('Total Gross Donation') }}
                            </p>
                            <h2>
                                <sup>
                                    $
                                </sup>
                                @{{ stats.totalGrossDonation }}
                            </h2>
                        </div>
                    </div>
                </div>
                <div class="col-sm col-md-3 col-xl mb-3 pr-md-0">
                    <div class="d-flex align-items-center flat-card flat-card--sec">
                        <div class="flat-card--sec__icon mr-2">
                            <img alt="No. Of Donations" src="{{ asset('images/icons/card-4.png') }}">
                            </img>
                        </div>
                        <div class="ml-1">
                            <p class="mb-0 aleo">
                                {{ __('Total Net Donations') }}
                            </p>
                            <h2>
                                <sup>
                                    $
                                </sup>
                                @{{ stats.totalNetDonation }}
                            </h2>
                        </div>
                    </div>
                </div>
                <div class="w-100 d-md-none">
                </div>
                <div class="col-sm col-md-3 col-xl mb-3 pr-md-0">
                    <div class="d-flex align-items-center flat-card flat-card--sec">
                        <div class="flat-card--sec__icon mr-2">
                            <img alt="RocketJar Fee" src="{{ asset('images/icons/card-4.png') }}">
                            </img>
                        </div>
                        <div class="ml-1">
                            <p class="mb-0 aleo">
                                {{ __('Total No. Of Donations') }}
                            </p>
                            <h2>
                                @{{ stats.totalNoOfDonation }}
                            </h2>
                        </div>
                    </div>
                </div>
                <div class="col-sm col-md-3 col-xl mb-3 pr-md-0 pr-xl-3">
                    <div class="d-flex align-items-center flat-card flat-card--sec">
                        <div class="flat-card--sec__icon mr-2">
                            <img alt="Stripe Fee" src="{{ asset('images/icons/card-4.png') }}">
                            </img>
                        </div>
                        <div class="ml-1">
                            <p class="mb-0 aleo">
                                {{ __('Total Unique Donors') }}
                            </p>
                            <h2>
                                @{{ stats.uniqueDonor }}
                            </h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="donation-graph my-5">
            <div class="d-flex justify-content-between">
                <h3 class="aleo">
                    {{ __('Monthly donations of last 12 months.') }}
                    <span class="font-italic text-muted f-16 font-weight-normal pl-md-1 d-block d-md-inline-block">
                    </span>
                </h3>
                <div class="text-muted aleo f-16">
                    @{{ chartStartDate }} - @{{ chartEndDate }}
                </div>
            </div>
            <div class="line-chart">
                {{--
                <img alt="Donation Graph" src="../images/donation-graph.jpg">
                    --}}
                    <svg aria-hidden="true" focusable="false" style="width:0; height:0; position:absolute;">
                        <defs>
                            <lineargradient id="btcFill" x1="1" x2="1" y1="0" y2="1">
                                <stop offset="0%" stop-color="#67bad3">
                                </stop>
                                <stop offset="100%" stop-color="#67bad3">
                                </stop>
                            </lineargradient>
                        </defs>
                    </svg>
                    <trend-chart :datasets="[{data: dataset, fill: true, className: 'curve-btc'}]" :grid="grid" :labels="labels" :min="0" v-if="dataset.length">
                    </trend-chart>
                </img>
            </div>
            <div v-if="! dataset.length">
                {{ __('Monthly donations of last twelve months.') }}
            </div>
        </div>
    </section>
</donation-stats>
@endsection
