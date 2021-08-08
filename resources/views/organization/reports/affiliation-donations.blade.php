@extends('layouts.app')

@section('title', "RocketJar")

@section('content')
@if (request()->is('admin*'))
<affiliation-donations inline-template="">
@else
<affiliation-donations
    :organization="currentOrganization"
    inline-template=""
    v-if="currentOrganization">
@endif
    <section class="section--def">
        <div class="row mb-1">
            <div class="col-12 col-md-12">
                <div class="px-x-2 mb-4">
                    <h2 class="mb-1" v-if="$root.user">
                        {{ __('Affiliation Donation Reports') }}
                    </h2>
                </div>
            </div>
        </div>
        <div class="section__content">
            <div class="row">
                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 mb-3 pr-md-0" v-for="affiliation in affiliations">
                    <div class="d-flex align-items-center flat-card flat-card--sec">
                        <div class="flat-card--sec__icon mr-2">
                            <img alt="No. Of Donations" src="{{ asset('images/icons/card-4.png') }}">
                            </img>
                        </div>
                        <div class="ml-1">
                            <p class="mb-0 aleo">
                                @{{ affiliation.name }}
                            </p>
                            <p>
                                Total Donation : @{{ affiliation.symbol+""+affiliation.total_donations }} <br>
                                Net Donation : @{{ affiliation.symbol+""+affiliation.net_donations }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>
</affiliation-donations>
@endsection
