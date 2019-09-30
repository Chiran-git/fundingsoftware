@extends('layouts.app')

@section('title', __('Donations'))

@section('content')
    <donation-list :organization="currentOrganization" inline-template v-if="currentOrganization">
    <section class="section">
        <div class="row mb-5">
            <div class="col-12 col-md-12 col-lg-2">
                <h2 class="aleo">{{ __('Donations')}}</h2>
            </div>
            <div class="col-12 ml-lg-auto col-md-12 col-lg-10 col-xl-9">
                <div class="d-md-flex justify-content-end align-items-center">
                    <div class="form_wrapper form--filters flex-grow-1">
                        <h6 class="text-uppercase float-md-left mt-2 pt-1 mr-2">{{ __('Filter by') }}</h6>
                        <form class="mt-2 mt-md-0 has-daterange">
                            <ul class='form_fields d-sm-flex'>
                                <li class='field size1 align-top pb-2 pb-md-0 mr-1 mr-lg-0'>
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
                                <li class='field size2 align-top pb-0'>
                                    <div class='input_container_text'>
                                        <date-range-picker v-model="dateRange"
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
                    <a :href="`${$root.rj.baseUrl}/organization/${organization.id}/donations/export`" class="btn btn--outline rounded-pill mt-2 mt-md-0 ml-md-1 ml-lg-3">{{ __('Export') }}</a>
                    <a :href="`${$root.rj.baseUrl}/organization/${organization.id}/donation/create`" class="btn btn--outline rounded-pill btn--size6 mt-2 mt-md-0 ml-md-1 ml-lg-3">{{ __('Record Donation') }}</a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="table-responsive">
                    <table-component
                        :data="setDonationList"
                        :show-filter=false
                        :show-caption=false
                        sort-by="created_at"
                        sort-order="desc"
                        :cache-lifetime=0
                        ref="donationList"
                        table-class="table table-striped">

                        <table-column show="full_name" label="{{ __('Name') }}" cell-class="first-col">
                            <template slot-scope="row">
                                <a :href="`${$root.rj.baseUrl}/organization/${organization.id}/donor/${row.donor_id}`">@{{ row.full_name }}</a>
                            </template>
                        </table-column>
                        <table-column show="gross_amount" label="{{ __('Donation') }}">
                            <template slot-scope="row">
                                @{{ $root.donationMoney(row.gross_amount, row.symbol) }}
                            </template>
                        </table-column>
                        <table-column show="net_amount" label="{{ __('Net') }}">
                            <template slot-scope="row">
                                @{{ $root.donationMoney(row.net_amount, row.symbol) }}
                            </template>
                        </table-column>
                        <table-column show="name" label="{{ __('Campaign') }}"></table-column>
                        <table-column show="title" label="{{ __('Reward') }}">
                            <template slot-scope="row">
                                @{{ row.title ? row.title : $root.rj.translations.none }}
                            </template>
                        </table-column>

                        <table-column show="entry_type" label="{{ __('Fund') }}">
                            <template slot-scope="row">
                                <span v-if="_.capitalize(row.entry_type) == 'Online'">@{{ _.capitalize(row.entry_type) + ': ' + row.card_brand }}</span>
                                <span v-else>@{{ _.capitalize(row.entry_type) + ': ' + _.capitalize(row.donation_method) }}</span>
                            </template>
                        </table-column>

                        <table-column show="created_at" label="{{ __('Time') }}">
                            <template slot-scope="row">
                                @{{ $root.convertUTCToBrowser(row.created_at, 'M/D/YYYY &nbsp;&nbsp;  h:mm A') }}
                            </template>
                        </table-column>

                        <table-column label="" :sortable="false" :filterable="false">
                            <template slot-scope="row">
                                <a :href="`${$root.rj.baseUrl}/organization/${organization.id}/donor/${row.donor_id}`"><span class="fa fa-search form-control-feedback"></span></a>
                            </template>
                        </table-column>
                    </table-component>
                </div>
            </div>
        </div>
    </section>
</donation-list>
@endsection
