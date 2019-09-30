@extends('layouts.app')

@section('title', __('Create Campaign'))

@section('content')
    <campaign-create inline-template
        :organization="currentOrganization"
        :campaign-id="''"
        v-if="currentOrganization">
    <section class="section--def">
        <h5 class="aleo">{{ __('Campaigns')}}</h5>
        <h2>{{ __('New Campaign')}}</h2>
            <div id="campaign-create">
                @include('campaign.partials.tabs')
                @include('campaign.partials.info-form')
                @include('campaign.partials.rewards')
                @include('campaign.partials.donor-message')
                @include('campaign.partials.invite-users')
                @include('campaign.partials.pay-out')
                @include('campaign.partials.completed')
            </div>
    </section>
    </campaign-create>
@endsection

@section('head-tags')
<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.5/cropper.min.css" rel="stylesheet">
@endsection
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.5/cropper.min.js"></script>
