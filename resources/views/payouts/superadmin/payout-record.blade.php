@extends('layouts.app')

@section('title', __('Record Pay-Out'))

@section('content')
<payout-create inline-template>
<section class="section--def">
    <div class="row mb-1">
        <div class="col-12 col-md-6">
            <div class="px-x-2 mb-5 mt-3">
                <h2>{{ __('Record Pay-Out')}}</h2>
            </div>
        </div>
    </div>
    <div class="section__content">
        <form method='post' @submit.prevent="" v-on:change="updateDonation">
            <div class="row">
                <div class="col-md-6">
                    <div class='form_wrapper'>
                        <ul class='form_fields'>
                            <li class='field size2 align-top'><label class='field_label f-16 mb-1 mb-1'>{{ __('Organization Name')}}</label>
                                <div class='input_container input_container_text'>
                                    <autocomplete
                                        ref="search"
                                        source="{{ route('organization.search', ['q' => '']) }}"
                                        placeholder="Search Organization"
                                        method="post"
                                        placeholder="{{ __('Search') }}"
                                        input-class="form-control mr-sm-2 w-100"
                                        :results-display="showItem"
                                        :request-headers="httpHeaders"
                                        :show-no-results="true"
                                        @selected="getCampaigns" >
                                    </autocomplete>
                                </div>
                                <span class="invalid-feedback" v-show="errors.organization_id">
                                    @{{ errors.organization_id }}
                                </span>
                            </li>
                            <li class='field size2 align-top'><label class='field_label f-16 mb-1'>{{ __('Campaign name')}}</label>
                                <div class='input_container_select'>
                                    <v-select
                                        placeholder="{{ __('Select Campaigns') }}"
                                        v-model="campaign"  
                                        :options="campaigns"
                                        label="name"
                                        @input="selectedCampaign" >
                                    </v-select>
                                </div>
                                <span class="invalid-feedback" v-show="errors.campaign_id">
                                    @{{ errors.campaign_id }}
                                </span>
                            </li>
                            <li class='field pl-2 size2 pb-2 field_radio--tab' v-if="form.campaign_id">
                                <div class="tab__content ml-0 d-block">
                                    <div class="container">
                                        <div class="row">
                                            <div class="col-12 col-md-6">
                                                <div class="body__content vertical-line py-md-0">
                                                    <p class="font-italic mb-2">{{ __('Your check will be mailed to:') }}</p>
                                                    <p>
                                                        @{{ form.payout_name }} <br/>
                                                        @{{ campaign.organization.name }} <br/>
                                                        @{{ form.payout_address1 }}<br/>
                                                        <span v-if="form.payout_address2">@{{ form.payout_address2 }}<br/></span>
                                                        @{{ form.payout_city + ', ' + form.payout_state + ' ' + form.payout_zipcode }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <div class="body__content py-md-0">
                                                    <p class="font-italic mb-2">{{ __('Your check will be payable to:') }}</p>
                                                    <p>
                                                        <span  v-if="form.payout_payable_to">@{{ form.payout_payable_to }}</span>
                                                        <span v-else>@{{ campaign.organization.name }}</span>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class='field size2 align-top'>
                                <label class='field_label'>{{ __('Issue Date')}}</label>
                                <div class='input_container input_container_text position-relative'>
                                    <flat-pickr
                                        :config="flatPickrConfig"
                                        class="form-control"
                                        placeholder="{{ __("MM/DD/YYYY") }}"
                                        name="issue_date"
                                        v-model="form.issue_date">
                                    </flat-pickr>
                                </div>
                                <span class="invalid-feedback" v-show="errors.issue_date">
                                    @{{ errors.issue_date }}
                                </span>
                            </li>
                            <li class='field size1 align-top'>
                                <label class='field_label'>{{ __('Start Date')}}</label>
                                <div class='input_container input_container_text position-relative'>
                                    <flat-pickr
                                        :config="flatPickrConfig"
                                        class="form-control"
                                        placeholder="{{ __("MM/DD/YYYY") }}"
                                        name="start_date"
                                        v-model="form.start_date">
                                    </flat-pickr>
                                </div>
                                <span class="invalid-feedback" v-show="errors.start_date">
                                    @{{ errors.start_date }}
                                </span>
                            </li>
                            <li class='field size1 align-top'>
                                <label class='field_label'>{{ __('End Date')}}</label>
                                <div class='input_container input_container_text position-relative'>
                                    <flat-pickr
                                        :config="flatPickrConfig"
                                        class="form-control"
                                        placeholder="{{ __("MM/DD/YYYY") }}"
                                        name="end_date"
                                        v-model="form.end_date">
                                    </flat-pickr>
                                </div>
                                <span class="invalid-feedback" v-show="errors.end_date">
                                    @{{ errors.end_date }}
                                </span>
                            </li>
                            <li class='field size1 align-top'>                                
                                <div class='input_container input_container_text position-relative'>
                                    @include('partials.common.button-with-loading', [
                                        'title' => __('Get Donations'),
                                        'busyCondition' => 'showLoading',
                                        'buttonClass' => 'btn--size6',
                                        'submitMethod' => "getDonations",
                                    ])
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-12">
                    <table class="table table-striped" v-if="donations.length > 0">
                        <thead>
                            <tr>
                                <th>Donor</th>
                                <th>Gross Amount</th>
                                <th>Net Amount</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="donation in donations">
                                <td>@{{ donation.donor.first_name+" "+donation.donor.last_name}}</td>
                                <td>@{{ $root.donationMoney(donation.gross_amount, donation.organization.currency.symbol)}}</td>
                                <td>@{{ $root.donationMoney(donation.net_amount, donation.organization.currency.symbol)}}</td>
                                <td>@{{ $root.convertUTCToBrowser(donation.created_at, 'M/DD/YY') }}</td>
                            </tr>
                        </tbody>
                    </table>

                    <h4 v-if="getDonationBtn && donations.length == 0">{{ __('No donations found') }}</h4>
                </div>
                <div class="col col-md-6" v-if="donations.length > 0">
                    <div class="d-flex justify-content-between">
                        <h3 class="aleo">Total Payout Amount: @{{ $root.donationMoney(form.total_amount, currency)}}</h3>
                    </div>
                </div>
                <div class="col col-md-12" v-if="donations.length > 0">
                    <div class='mt-4 form_footer d-flex flex-column flex-md-row justify-content-between align-items-start'>
                        @include('partials.common.button-with-loading', [
                            'title' => __('Record'),
                            'buttonClass' => 'btn--size4',
                            'busyCondition' => 'form.busy',
                            'submitMethod' => 'submit'
                        ])
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
</payout-create>
@endsection
