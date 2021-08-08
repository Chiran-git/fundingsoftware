@extends('layouts.app')

@section('title', "RocketJar")

@section('content')
<online-donations inline-template>
    <section class="section--def">
        <div class="row mb-1">
            <div class="col-4 col-md-4">
                <div class="px-x-2 mb-4">
                    <h2 class="mb-1" v-if="$root.user">{{ __('Online Donation Report') }}</h2>
                </div>
            </div>
            <div class="col-8 ml-lg-auto col-md-8 col-lg-8 col-xl-8">
                <div class="d-md-flex justify-content-end align-items-center">
                    <div class="form_wrapper form--filters flex-grow-1">
                        <h6 class="text-uppercase float-md-left mt-2 pt-1 mr-2">{{ __('Filter by') }}</h6>
                        <form class="mt-2 mt-md-0 has-daterange">
                            <ul class='form_fields d-sm-flex'>
                                <li class='field size2 align-top pb-0'>
                                    <div class='input_container_text'>
                                        <date-range-picker v-model="dateRange"
                                            ref="picker"
                                            :locale-data="{ firstDay: 1, format: 'MMM D, YYYY' }"
                                            @update="dateRangeUpdated"
                                            :auto-apply=true
                                            :linked-calendars=false>
                                            <div slot="input" slot-scope="picker">
                                                @{{ (picker.startDate && picker.endDate) ? ($root.formatDate(picker.startDate, 'MMM D, YYYY') + ' - '+  $root.formatDate(picker.endDate, 'MMM D, YYYY')) : $root.rj.translations.select_date_range }}
                                            </div>
                                        </date-range-picker>
                                    </div>
                                </li>
                            </ul>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="section__content">
            <div class="row">
                <div class="col-sm col-md-4 col-xl mb-3 pr-md-0">
                    <div class="d-flex align-items-center flat-card flat-card--sec">
                            <div class="flat-card--sec__icon mr-2">
                                <img src="{{ asset('images/icons/card-4.png') }}" alt="Total Donation">
                            </div>
                        <div class="ml-1">
                            <p class="mb-0 aleo">{{ __('Total Online Donations') }}</p>
                            <h2><sup>$</sup>@{{ stats.totalDonations}}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-sm col-md-4 col-xl mb-3 pr-md-0">
                    <div class="d-flex align-items-center flat-card flat-card--sec">
                            <div class="flat-card--sec__icon mr-2">
                                <img src="{{ asset('images/icons/card-4.png') }}" alt="No. Of Donations">
                            </div>
                        <div class="ml-1">
                            <p class="mb-0 aleo">{{ __('No. Of Donations') }}</p>
                            <h2>@{{ stats.noOfDonations }}</h2>
                        </div>
                    </div>
                </div>
                <div class="w-100 d-md-none"></div>
                <div class="col-sm col-md-4 col-xl mb-3 pr-md-0">
                    <div class="d-flex align-items-center flat-card flat-card--sec">
                            <div class="flat-card--sec__icon mr-2">
                                <img src="{{ asset('images/icons/card-4.png') }}" alt="RocketJar Fee">
                            </div>
                        <div class="ml-1">
                            <p class="mb-0 aleo">{{ __('RocketJar Fee') }}</p>
                            <h2><sup>$</sup>@{{ stats.rocketJarFees }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-sm col-md-4 col-xl mb-3 pr-md-0 pr-xl-3">
                    <div class="d-flex align-items-center flat-card flat-card--sec">
                            <div class="flat-card--sec__icon mr-2">
                                <img src="{{ asset('images/icons/card-4.png') }}" alt="Stripe Fee">
                            </div>
                        <div class="ml-1">
                            <p class="mb-0 aleo">{{ __('Stripe Fee') }}</p>
                            <h2><sup>$</sup>@{{ stats.stripeFees }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <section class="my-5 py-5">
            <div class="row">
                <div class="col-12">
                    <table-component
                        :data="getDonations"
                        :show-filter=false
                        :show-caption=false
                        :cache-lifetime=0
                        ref="donationList"
                        sort-order="desc"
                        sort-by="donations.created_at"
                        table-class="table table-gray table-gray-alt">
                            <table-column show="name" label="Organization Name">
                                <template slot-scope="row">
                                    <img :src="row.logo" v-if="row.logo" :alt="row.name"
                                        class="rounded-circle org-logo mr-2">
                                    <svg v-else="row.logo" width="45" height="45" class="rounded-circle org-logo mr-2">
                                        <rect width="100%" height="100%" fill="#e2e2e2">
                                        </rect>
                                        <text x="50%" y="50%" fill="#222222" text-anchor="middle"
                                            alignment-baseline="central" font-weight="bold"
                                            class="f-8">{{ __('No Image') }}</text>
                                    </svg>
                                    <span class="text-capitalize aleo">
                                        @{{ row.name }}
                                    </span>
                                </template>
                            </table-column>
                            <table-column show="no_of_donations" label="No. Of Donations">
                                <template slot-scope="row">
                                    @{{ row.no_of_donations }}
                                </template>
                            </table-column>
                            <table-column show="gross_donations" label="Gross Donations">
                                <template slot-scope="row">
                                    @{{ row.symbol+$root.donationMoney(row.gross_donations/100) }}
                                </template>
                            </table-column>
                            <table-column show="rocket_fees" label="RocketJar Fee">
                                <template slot-scope="row">
                                    @{{ row.symbol+$root.donationMoney(row.rocket_fees/100) }}
                                </template>
                            </table-column>
                            <table-column show="net_donations" label="Net Donations">
                                <template slot-scope="row">
                                    @{{ row.symbol+$root.donationMoney(row.net_donations/100) }}
                                </template>
                            </table-column>
                            <table-column show="stripe_fees" label="Stripe Fee">
                                <template slot-scope="row">
                                    @{{ row.symbol+$root.donationMoney(row.stripe_fees/100) }}
                                </template>
                            </table-column>
                        </table-component>
                </div>
            </div>
        </section>

    </section>
</online-donations>
@endsection

