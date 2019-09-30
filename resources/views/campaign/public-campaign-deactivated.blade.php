@extends('layouts.app')

@section('title', "Inactive Campaign")

@section('content')

    <div class="row align-items-center my-5 flex-wrap-reverse">
        <div class="col-md-6">
            <h1 class="aleo font-weight-normal mb-2 f-70">{{ __('Sorry!') }}</h1>
            <h2 class="aleo">{{ __('This campaign is no longer active.') }}</h2>
            <a href="{{ route('organization.show', ['orgSlug' => $organization->slug]) }}" class="btn rounded-pill py-1 mt-4">{{ __('View Active Campaigns') }}</a>
        </div>
        <div class="col-md-6 mb-4 mb-md-0">
            <img src="../images/illustration-placeholder.jpg" alt="Placeholder">
        </div>
    </div>

@endsection
