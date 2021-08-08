@extends('layouts.app')

@section('title', "RocketJar")

@section('content')
<camp-list inline-template>
    <section class="section  section--primary">
        <div class="row mb-5">
            <div class="col-12 col-md-6 mr-md-auto">
                @php
                    $status = app('request')->input('status');
                @endphp
                @if ($status == 'active')
                    <h2 class="aleo">{{ __('Active Campaigns')}}</h2>
                @else
                    <h2 class="aleo">{{ __('Completed Campaigns')}}</h2>
                @endif
            </div>
        </div>
        <section class="my-5">
            <div class="row">
                <div class="col-12 table-responsive">
                    <table-component
                        :data="getCampaigns"
                        :show-filter=false
                        :show-caption=false
                        :cache-lifetime=0
                        sort-order="desc"
                        sort-by="created_at"
                        ref="Campaigns"
                        table-class="table table-gray"
                        @row-click="clickCampaignRow">

                        <table-column show="name" label="Campaign Name" cell-class="clickable-row">
                            <template slot-scope="row">
                                <img :src="row.image" v-if="row.image" :alt="row.name" class="rounded-circle org-logo mr-2">
                                <svg v-else="row.image" width="45" height="45" class="rounded-circle org-logo mr-2">
                                    <rect width="100%" height="100%" fill="#e2e2e2">
                                    </rect>
                                    <text x="50%" y="50%" fill="#222222" text-anchor="middle" alignment-baseline="central" font-weight="bold" class="f-8">{{ __('No Image') }}</text>
                                </svg>
                                <span class="text-capitalize aleo">
                                    @{{ row.name }}
                                </span>
                            </template>
                        </table-column>

                        <table-column show="created_at" label="Created" cell-class="clickable-row">
                            <template slot-scope="row">
                                @{{ $root.convertUTCToBrowser(row.created_at, 'M/DD/YY') }}
                            </template>
                        </table-column>

                        <table-column show="end_date" label="End Date" cell-class="clickable-row">
                            <template slot-scope="row">
                                <span v-if="row.end_date">@{{ $root.convertUTCToBrowser(row.end_date, 'M/DD/YY') }}</span>
                                <span v-else="">-</span>
                            </template>
                        </table-column>

                        <table-column show="funds_raised" label="Progress" cell-class="clickable-row text-dark" :sortable="false">
                            <template slot-scope="row">
                                @{{ row.funds_raised ? row.symbol+$root.donationMoney(row.funds_raised): row.symbol+0}} of @{{ row.symbol+$root.donationMoney(row.fundraising_goal)}}
                            </template>
                        </table-column>

                        <table-column label="" :sortable="false" :filterable="false"  cell-class="clickable-row">
                            <template slot-scope="row">
                                <a href="#">
                                    <span class="fa fa-search form-control-feedback"></span>
                                </a>
                            </template>
                        </table-column>
                    </table-component>
                </div>
            </div>
        </section>
    </section>
</camp-list>
@endsection
