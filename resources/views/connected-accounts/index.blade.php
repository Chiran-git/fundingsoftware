@extends('layouts.app')

@section('title', "RocketJar")

@section('content')
    <section class="section--def">
        <div class="row mb-5">
            <div class="col-12 col-md-5">
                <h2 class="aleo">{{ __('Pay-Out Accounts')}}</h2>
            </div>
            <div class="col-12 ml-md-auto col-md-auto">
                <div class="d-md-flex justify-content-end">
                    @php
                        $returnUrl = urlencode(route('connected-account.index'));
                    @endphp
                    <a href="{{ route('connected-account.create') }}?return={{ $returnUrl }}" class="btn btn--outline rounded-pill btn--size6 mt-2 mt-md-0 ml-md-3">{{ __('New Pay-Out Account') }} </a>
                </div>
            </div>
        </div>
        <connected-account-list inline-template :organization="currentOrganization" v-if="currentOrganization">
            <section>
                <div class="row">
                    <div class="col-12 col-lg-6">
                        <h3 class="font-weight-normal mb-5">{{ __("This is where we'll deposit your donation funds for each campaign.") }}</h3>
                        <div class="table-responsive">
                            <ul class="list-fixed-head list-fixed-head--secondary">
                                <li class="head-fixed">
                                    <ul>
                                        <li>
                                            <strong>{{ __('Name') }}</strong>
                                        </li>
                                        <li>
                                            <strong>{{ __('Campaigns') }}</strong>
                                        </li>
                                        <li>
                                            <strong></strong>
                                        </li>
                                    </ul>
                                </li>
                                <div class="list--content">
                                    <li v-for="(account, index) in accounts">
                                        <ul>
                                            <li>
                                                <p><strong>@{{ account.account_nickname }}</strong>
                                                <span class="d-block" v-if="account.external_account_name">@{{ account.external_account_name }} xxxx@{{ account.external_account_last4 }}</span></p>
                                            </li>
                                            <li>
                                                <p :class="{'text-muted': account.campaigns.length == 0}">
                                                    <span class="d-block" v-for="campaign in account.campaigns">
                                                        @{{ campaign.name }}
                                                    </span>
                                                    <span v-if="! account.campaigns.length">None</span>
                                                </p>
                                            </li>
                                            <li>
                                                <a href="" @click.prevent="editAccount(account)">
                                                    <i class="fas fa-pencil-alt"></i>
                                                </a>
                                                <a href="" class="ml-2" @click.prevent="confirmDeleteAccount(index)">
                                                    <i class="fas fa-times-circle"></i>
                                                </a>
                                            </li>
                                        </ul>
                                    </li>
                                </div>
                            </ul><!-- /.list-members -->
                        </div>
                    </div>
                </div>
                @include('campaign.modals.connected-account-edit', [
                    'modalId' => 'connected-account-edit',
                ])
            </section>
        </connected-account-list>
    </section>
@endsection
