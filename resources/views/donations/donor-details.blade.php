@extends('layouts.app')

@section('title', __('Donor Profile'))

@section('content')
    <donor-detail :organization="currentOrganization" :donor="{{ $donor }}" inline-template v-if="currentOrganization">
    <section class="section--def">
        <div class="row mb-1">
            <div class="col-12 col-md-6">
                <h5 class="aleo mb-1">{{ __('Donations')}}</h5>
                <h2>{{ $donor->first_name . ' ' . $donor->last_name }}</h2>
            </div>
            <div class="col-12 col-md-6">
                <div class="d-md-flex justify-content-end align-items-center">
                    <a href="#" class="btn btn--outline rounded-pill btn--size3 f-14 mt-2 mt-md-0 ml-md-3" @click.prevent="showEmailDonorModal()">{{ __('Email Donor') }}</a>
                </div>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-12 col-md-7">
                <div class="row justify-content-between my-4">
                    <div>
                        <div class="col-12 col-sm-12 col-md-12 mb-3">
                            <p class="text-capitalize aleo text-grey-5 mb-0"><strong>Email</strong></p>
                            <p class="d-block assistant">{{ $donor->email }}</p>
                        </div>
                        <div class="col-12 col-sm-12 col-md-12 mb-3">
                            <p class="text-capitalize aleo text-grey-5 mb-0"><strong>Billing Address</strong></p>
                            <p class="d-block assistant">
                                <span v-if="donations[0] && donations[0].billing_address1">@{{ donations[0].billing_address1 }}<br/></span>
                                <span v-if="donations[0] && donations[0].billing_address2">@{{ donations[0].billing_address2 }}<br/></span>
                                <span v-if="donations[0] && donations[0].billing_city && donations[0].billing_state">@{{ donations[0].billing_city + ', ' + donations[0].billing_state }}</span>
                                <span v-else-if="donations[0] && donations[0].billing_city">@{{ donations[0].billing_city }}</span>
                                <span v-else-if="donations[0] && donations[0].billing_state">@{{ donations[0].billing_state }}</span>
                                <span v-if="donations[0] && donations[0].billing_zipcode">@{{ donations[0].billing_zipcode }}</span>
                            </p>
                        </div>
                        <div class="col-12 col-sm-12 col-md-12 mb-3">
                            <p class="text-capitalize aleo text-grey-5 mb-0"><strong>Mailling Address</strong></p>
                            <p class="d-block assistant">
                            <span v-if="donations[0] && donations[0].mailing_address1">@{{ donations[0].mailing_address1 }}<br/></span>
                                <span v-if="donations[0] && donations[0].mailing_address2">@{{ donations[0].mailing_address2 }}<br/></span>
                                <span v-if="donations[0] && donations[0].mailing_city && donations[0].mailing_state">@{{ donations[0].mailing_city + ', ' + donations[0].mailing_state }}</span>
                                <span v-else-if="donations[0] && donations[0].mailing_city">@{{ donations[0].mailing_city }}</span>
                                <span v-else-if="donations[0] && donations[0].mailing_state">@{{ donations[0].mailing_state }}</span>
                                <span v-if="donations[0] && donations[0].mailing_zipcode">@{{ donations[0].mailing_zipcode }}</span>
                            </p>
                        </div>
                    </div>

                    <div v-if="donor_questions">
                        <div class="col-12 mb-3" v-for="(donor_question, index) in donor_questions">
                            <p class="text-capitalize aleo text-grey-5 mb-0"><strong>@{{ donor_question.question }}</strong></p>
                            <p class="d-block assistant" v-if="donor_question.answer">@{{ donor_question.answer }}</p>
                            <p v-else>N/A</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <h3 class="aleo mb-1">{{ __('Donor Activity') }}</h3>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                            <th scope="col"><a href="#">{{ __('Campaign') }}</a></th>
                            <th scope="col"><a href="#">{{ __('Donation') }}</a></th>
                            <th scope="col"><a href="#">{{ __('Net') }}</a></th>
                            <th scope="col"><a href="#">{{ __('Reward') }}</a></th>
                            <th scope="col"><a href="#">{{ __('Fund') }}</a></th>
                            <th scope="col"><a href="#">{{ __('Time') }}</a></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(donation, index) in donations">
                                <th scope="row" class="text-capitalize text-red">@{{ donation.campaign.name }}</th>
                                <td>@{{ $root.donationMoney(donation.gross_amount, donation.currency.symbol) }}</td>
                                <td>@{{ $root.donationMoney(donation.net_amount, donation.currency.symbol) }}</td>
                                <td>
                                    <span v-if="donation.reward && donation.reward.reward"> @{{ donation.reward.reward }} </span>
                                    <span v-else>@{{ $root.rj.translations.none }}</span>
                                </td>
                                <td>
                                    <span v-if="_.capitalize(donation.entry_type) == 'Online'">@{{ _.capitalize(donation.entry_type) + ': ' + _.capitalize(donation.card_brand) }}</span>
                                    <span v-else>@{{ _.capitalize(donation.entry_type) + ': ' + _.capitalize(donation.donation_method) }}</span>
                                </td>
                                <td>@{{ $root.convertUTCToBrowser(donation.created_at, 'M/D/YY h:mmA') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @include('donations.modals.email-donor', [
            'modalId' => 'modal-email-donor',
        ])
    </section>
    </donor-detail>
@endsection
