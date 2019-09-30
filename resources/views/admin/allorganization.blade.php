@extends('layouts.app')

@section('title', "RocketJar")

@section('content')
<org-list inline-template>
    <section class="section  section--primary">
        <div class="row mb-5">
            <div class="col-12 col-md-6 mr-md-auto">
                <h2 class="aleo">{{ __('All Organizations')}}</h2>
            </div>
            <div class="col-12 col-md-auto">
                <a href="{{ route('admin.organization.create') }}" class="btn btn--outline rounded-pill btn--size6 mt-2 mt-md-0 ml-md-3 f-14">{{ __('New Organization') }}</a>
            </div>
        </div>
        <section class="my-5">
            <div class="row">
                <div class="col-12 table-responsive">
                    <table-component
                        :data="getOrganizations"
                        :show-filter=false
                        :show-caption=false
                        :cache-lifetime=0
                        sort-order="desc"
                        sort-by="created_at"
                        ref="Organizations"
                        table-class="table table-gray"
                        @row-click="clickRow">
                        <table-column show="name" label="Organization Name" cell-class="clickable-row">
                            <template slot-scope="row">
                                <img :src="row.logo" v-if="row.logo" :alt="row.name" class="rounded-circle org-logo mr-2">
                                <svg v-else="row.logo" width="45" height="45" class="rounded-circle org-logo mr-2">
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
                        <table-column show="no_of_campaigns" label="Campaigns"  cell-class="clickable-row"></table-column>
                        <table-column show="total_donations" label="Total" cell-class="clickable-row">
                            <template slot-scope="row">
                                @{{ row.symbol+$root.donationMoney(row.total_donations/100) }}
                            </template>
                        </table-column>
                        <table-column show="net_donations" label="Net" cell-class="clickable-row">
                            <template slot-scope="row">
                                @{{ row.symbol+$root.donationMoney(row.net_donations/100) }}
                            </template>
                        </table-column>
                        <table-column label="" :sortable="false" :filterable="false"  cell-class="clickable-row">
                                <template slot-scope="row">
                                    <a :href="`${$root.rj.baseUrl}/admin/impersonate/${row.id}`">
                                        <span class="fa fa-search form-control-feedback"></span>
                                    </a>
                                </template>
                        </table-column>
                    </table-component>
                </div>
            </div>
        </section>
    </section>
</org-list>
@endsection
