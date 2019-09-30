@extends('layouts.app')

@section('title', __('Edit Campaign'))

@section('content')
    <campaign-create inline-template
        :organization="currentOrganization"
        :campaign-id="{{ isset($campaign->id) ? $campaign->id : '' }}"
        v-if="currentOrganization">
    <section class="section--def">
        <h5 class="aleo">{{ __('Campaigns')}}</h5>
        <h2>{{ __('Edit Campaign')}}</h2>
            <div id="campaign-create">
                @include('campaign.partials.info-form', ['action' => 'edit'])
                @include('campaign.partials.rewards', ['action' => 'edit'])
                @include('campaign.partials.donor-message', ['action' => 'edit'])
                @include('campaign.partials.invite-users', ['action' => 'edit'])
                @include('campaign.partials.pay-out', ['action' => 'edit', 'campaignId' => $campaign->id])
                {{-- @include('campaign.partials.completed') --}}
            </div>
    </section>
    </campaign-create>
@endsection
@section('head-tags')
<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.5/cropper.min.css" rel="stylesheet">
@endsection
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.5/cropper.min.js"></script>
