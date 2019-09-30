@extends('layouts.app')

@section('title', "RocketJar")

@section('content')
<payout-list :organization="currentOrganization" inline-template v-if="currentOrganization">
    <section class="section">
        <div class="row mb-5">
            <div class="col-12 col-md-12 col-lg-4">
                <h2 class="aleo">{{ __('Pay-Out History')}}</h2>
            </div>
            <div class="col-12 ml-lg-auto col-md-12 col-lg-6">
                <div class="d-md-flex">
                    <div class="form_wrapper form--filters flex-grow-1">
                        <h6 class="text-uppercase float-left mt-2 pt-1 mr-2">{{ __('Filter by') }}</h6>
                        <form class="mt-2 mt-md-0">
                            <ul class='form_fields d-flex'>
                                <li class='field size1 align-top pb-0 mr-3 mr-lg-0'>
                                    <div class='input_container_select'>
                                        <v-select :options="campaignOptions"
                                            placeholder="{{ __('All Campaigns')}}"
                                            v-model="campaign">
                                            <template slot="option" slot-scope="option">
                                                @{{ getCampaignOptionLabel(option.label) }}
                                            </template>
                                            <template slot="selected-option" slot-scope="option">
                                                @{{ getCampaignOptionLabel(option.label) }}
                                            </template>
                                        </v-select>
                                    </div>
                                </li>
                                <li class='field size1 align-top pb-0'>
                                    <div class='input_container_select'>
                                        <v-select :options="accountOptions"
                                            placeholder="{{ __('All Accounts')}}"
                                            v-model="account">
                                            <template slot="option" slot-scope="option">
                                                @{{ getAccountOptionLabel(option.label) }}
                                            </template>
                                            <template slot="selected-option" slot-scope="option">
                                                @{{ getAccountOptionLabel(option.label) }}
                                            </template>
                                        </v-select>
                                    </div>
                                </li>
                            </ul>
                        </form>
                    </div>
                </div>
            </div>
        </div>
            <div class="row">
                <div class="col-12">
                    <div class="table-responsive">
                        {{--  <table class="table table-striped table-striped--def">
                            <thead>
                                <tr>
                                <th scope="col"><a href="#">{{ __("Issue Date") }}</a></th>
                                <th scope="col"><a href="#">{{ __('Time Period') }}</a></th>
                                <th scope="col"><a href="#">{{ __('Campaign') }}</a></th>
                                <th scope="col"><a href="#">{{ __('Account') }}</a></th>
                                <th scope="col"><a href="#">{{ __('Total Donations') }}</a></th>
                                <th scope="col"><a href="#">{{ __('Net Deposit') }}</a></th>
                                <th scope="col"></th>
                                </tr>
                            </thead>  --}}

                            <table-component
                            :data="setPayoutsList"
                            :show-filter=false
                            :show-caption=false
                            sort-by="issue_date"
                            sort-order="desc"
                            :cache-lifetime=0
                            ref="payoutsList"
                            table-class="table table-striped">

                            <table-column show="issue_date" label="{{ __('Issue Date') }}" cell-class="first-col">
                                <template slot-scope="row">
                                    <a href="#">@{{ $root.convertUTCToBrowser(row.issue_date, 'MMM. D, YYYY') }}</a>
                                </template>
                            </table-column>
                            <table-column show="start_date" label="{{ __('Time Period') }}">
                                <template slot-scope="row">
                                    @{{ $root.convertUTCToBrowser(row.start_date, 'M/D/YY') + (row.end_date ? ' - ' + $root.convertUTCToBrowser(row.end_date, 'M/D/YY') : '') }}
                                </template>
                            </table-column>

                            <table-column show="name" label="{{ __('Campaign') }}"></table-column>
                            <table-column show="nickname" label="{{ __('Account') }}"></table-column>

                            <table-column show="gross_amount" label="{{ __('Total Donations') }}">
                                <template slot-scope="row">
                                    @{{ $root.donationMoney(row.gross_amount, organization.currency.symbol) }}
                                </template>
                            </table-column>
                            <table-column show="deposit_amount" label="{{ __('Net Deposit') }}">
                                <template slot-scope="row">
                                    @{{ $root.donationMoney(row.deposit_amount, organization.currency.symbol) }}
                                </template>
                            </table-column>

                            <table-column label="" :sortable="false" :filterable="false">
                                <template slot-scope="row">
                                    {{-- <a href="#"><i class="fas fa-file-download"></i></a> --}}
                                </template>
                            </table-column>
                        </table-component>
                    </div>
                </div>
            </div>
    </section>
</payout-list>
@endsection
