@extends('layouts.app')

@section('title', __('Donors'))

@section('content')
    <donor-list :organization="currentOrganization" inline-template v-if="currentOrganization">
    <section class="section">
        <div class="row mb-5">
            <div class="col-12 col-md-4">
                <h2 class="aleo">{{ __('Donor List')}}</h2>
            </div>
            <div class="col-12 ml-lg-auto col-md-auto">
                <div class="d-md-flex justify-content-start">
                    <div>
                        <form class="form-inline form-search--light mt-2 mt-md-0">
                            <div class="form-group has-search">
                                <span class="fa fa-search form-control-feedback"></span>
                                <input type="text" class="form-control" placeholder="{{ __('Search Donors...') }}" v-model="searchQuery">
                            </div>
                        </form>
                    </div>
                    <a :href="`${$root.rj.baseUrl}/organization/${organization.id}/donors/export`" class="btn btn--outline rounded-pill mt-2 mt-md-0 ml-md-3">{{ __('Export') }}</a>
                    <a :href="`${$root.rj.baseUrl}/organization/${organization.id}/donation/create`" class="btn btn--outline rounded-pill btn--size6 mt-2 mt-md-0 ml-md-3 text-right pr-3">{{ __('Add Donor') }} <i class="fas fa-caret-down ml-4 pl-2"></i></a>
                </div>
            </div>
        </div>

        <div class="row">
                <div class="col-12">
                    <div class="table-responsive">
                        {{--  <table class="table table-striped">
                            <thead>
                                <tr>
                                <th scope="col"><a href="#">{{ __("Name") }}</a></th>
                                <th scope="col"><a href="#">{{ __('Email') }}</a></th>
                                <th scope="col"><a href="#">{{ __('Total Donated') }}</a></th>
                                <th scope="col" class="text-center"><a href="#">{{ __('Number of Donations') }}</a></th>
                                <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(donor, index) in donors.data">
                                    <th scope="row"><a :href="`${$root.rj.baseUrl}/organization/${organization.id}/donor/${donor.id}`">@{{ donor.full_name }}</a></th>
                                    <td>@{{ donor.email }}</td>
                                    <td>@{{ organization.currency.symbol + donor.total_donated }}</td>
                                    <td class="text-center">@{{ donor.total_donations }}</td>
                                    <td><a :href="`${$root.rj.baseUrl}/organization/${organization.id}/donor/${donor.id}`"><span class="fa fa-search form-control-feedback"></span></a></td>
                                </tr>
                            </tbody>
                        </table>  --}}

                        <table-component
                            :data="setDonorList"
                            :show-filter=false
                            :show-caption=false
                            sort-by="full_name"
                            sort-order="asc"
                            :cache-lifetime=0
                            ref="donorsList"
                            table-class="table table-striped table-striped--center-text">

                            <table-column show="full_name" label="{{ __('Name') }}" cell-class="first-col">
                                <template slot-scope="row">
                                    <a :href="`${$root.rj.baseUrl}/organization/${organization.id}/donor/${row.id}`">@{{ row.full_name }}</a>
                                </template>
                            </table-column>
                            <table-column show="email" label="{{ __('Email') }}"></table-column>
                            <table-column show="total_donation_amount" label="{{ __('Total Donated') }}">
                                <template slot-scope="row">
                                    @{{ $root.donationMoney(row.total_donation_amount, $root.currentOrganization.currency.symbol) }}
                                </template>
                            </table-column>
                            <table-column show="total_donation_count" label="{{ __('Number of Donations') }}"></table-column>
                            <table-column label="" :sortable="false" :filterable="false">
                                <template slot-scope="row">
                                    <a :href="`${$root.rj.baseUrl}/organization/${organization.id}/donor/${row.id}`"><span class="fa fa-search form-control-feedback"></span></a>
                                </template>
                            </table-column>
                        </table-component>

                    </div>
                </div>
            </div>
            {{--  <pagination :data="donors" :limit="2" :show-disabled=true @pagination-change-page="setDonorList"></pagination>  --}}
        </section>
    </donor-list>
@endsection
