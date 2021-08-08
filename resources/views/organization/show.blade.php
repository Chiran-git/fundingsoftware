@php
use App\Support\RJ;
use Illuminate\Support\Str;
@endphp

@extends('layouts.app')

@section('title')
{{ $organization->name }}
@endsection

@section('opengraph')
    <!-- Twitter Card data -->
    <meta name="twitter:card" content="summary" />
    <meta name="twitter:site" content="@rocketjar" />

    <!-- Open Graph data -->
    <meta property="og:title" content="{{ $organization->name }} | RocketJar" />

    <meta property="og:type" content="website" />
    <meta property="og:url" content="{{ request()->url() }}" />
    <meta property="og:image" content="{{ RJ::assetCdn($organization->cover_image) }}" />
    <meta property="og:description" content="{{ $organization->appeal_headline }} - {{ $organization->appeal_message }}" />
@endsection

@section('content')
<share-modal inline-template
    modal-id="organization-share-modal"
    modal-title="{{ __('Share this Page') }}"
    modal-subtitle="{{ $organization->name }}"
    share-url="{{ route('organization.show', ['orgSlug' => $organization->slug]) }}"
    share-headline="{{ $organization->name }}"
    share-text="{{ __('Fund projects that matter') }} {{ route('organization.show', ['orgSlug' => $organization->slug]) }}">
    @include('partials.modals.modal-share')
</share-modal>

<header class="header header--light full-width">
    @include('partials.common.flash-message')
    @if ($organization->cover_image)
        <div class="img-cover-container">
            <div class="img-cover bg-auto d-block" style="background-image: url({{ RJ::assetCdn($organization->cover_image) }}); background-repeat: no-repeat; background-size: cover;"></div>
        </div>
    @else
        <div class="img-rounded-left d-flex justify-content-center align-items-center h-320">
            <span class="mb-2 d-block font-weight-bold f-34">{{ __("No Image")}}</span>
        </div>
    @endif
    <section class="header-filter" style="border-color: #{{ $organization->secondary_color }}; background-color: #{{ $organization->primary_color }}">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="header-filter__body">
                        <div class="row">
                            <div class="col-12 col-md-3">
                                <div class="p-2 p-md-3 mb-3 mb-md-0 border-hr ml-2">
                                    @if ($organization->logo)
                                        <div class="img-fluid float-left float-md-none mr-2" style="background-image: url({{ $organization->logo }}); background-repeat: no-repeat; background-size: cover; background-position: center;"></div>
                                    @endif
                                    <address>
                                        <p class="mb-2 f-16">{{ $organization->name }}</p>
                                        <p class="mb-0 f-14"><span class="d-block"> {{ $organization->address1 }}</span><span class="d-block"> {{ $organization->address2 }}</span>
                                            @if ($organization->city && $organization->state)
                                                {{ $organization->city }}, {{ $organization->state }}
                                            @else
                                                {{ $organization->city }}
                                                {{ $organization->state }}
                                            @endif
                                            {{ $organization->zipcode }}</p>
                                    </address>
                                </div>
                            </div>
                            <div class="col-12 col-md-9">
                                <div class="px-2 pr-md-4 pl-md-2 left d-flex align-content-between flex-column h-100 break-word">
                                    <h2 class="mb-3">{{ $organization->appeal_headline }}</h2>

                                    <div class="mb-4 mb-md-auto">
                                        @if ($organization->appeal_photo)
                                            <div class="img-container float-right rounded-circle ml-4 mr-2" style="background-image: url({{ RJ::assetCdn($organization->appeal_photo) }}); background-repeat: no-repeat; background-size: cover;"></div>
                                        @endif
                                        {{ \Illuminate\Mail\Markdown::parse($organization->appeal_message) }}
                                    </div>
                                    <p>
                                        @include('partials.common.social-share', [
                                            'shareUrl' => url()->current(),
                                            'shareButton' => true,
                                            'shareText' => __('Fund projects that matter :url', ['url' => url()->current()]),
                                            'shareHeadline' => $organization->name,
                                        ])
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</header>
@if (($campaigns = $organization->activeCampaigns()->get()) && count($campaigns))
    <section class="my-5 pt-4">
        <div class="row">
            <div class="col-12 mb-3">
                <h3 class="aleo">{{ __('Our Campaigns') }}</h3>
            </div>
        </div>
        @foreach ($campaigns as $campaign)
        <div class="row no-gutters border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-250 position-relative">
            <!-- Show on Desktop -->
            <div class="col-lg-3 d-none d-sm-block">
                @if ($campaign->image)
                    <img class="image-cover" src="{{ $campaign->image }}" alt="{{ $campaign->name }}">
                @else
                    <div class="img-rounded-left d-flex justify-content-center align-items-center h-lg-100">
                        <span class="mb-2 d-block font-weight-bold">{{ __("No Image")}}</span>
                    </div>
                @endif
            </div>
            <div class="col-md-8 col-lg-7 p-4 d-flex flex-column position-static">
                <!-- Show on Mobile -->
                <div class="d-flex align-items-center d-sm-none mb-2">
                    @if ($campaign->image)
                        <img class="image-cover small-img rounded mr-2" src="{{ $campaign->image }}" alt="{{ $campaign->name }}">
                    @else
                        <div class="img-rounded-left d-flex justify-content-center align-items-center h-lg-100 rounded mr-2 small-img">
                            <span class="d-block font-weight-bold">{{ __("No Image")}}</span>
                        </div>
                    @endif
                    <h3 class="mb-0 aleo mt-lg-2 break-word">{{ $campaign->name }}</h3>
                </div>
                <h3 class="mb-0 aleo mt-lg-2 d-none d-sm-block break-word">{{ $campaign->name }}</h3>
                <p class="card-text mb-0 mt-auto break-word">{{ $campaign->excerpt }}
                <p class="mb-auto"><a href="{{ route('campaign.show', ['orgSlug' => $organization->slug, 'campSlug' => $campaign->slug]) }}"class="dark-link stretched-link">{{ __("Read More") }} <i class="fas fa-chevron-right f-8"></i></a></p>
                <div class="d-flex progressbar-container progressbar-container--medium mt-auto">
                    @include('campaign.partials.progress', ['campaign' => $campaign])
                </div>
            </div>
            <div class="col-md-4 col-lg-2 p-4 pl-lg-2 mt-lg-2 d-flex flex-column position-static">
                <a href="{{ route('campaign.donate', ['orgSlug' => $organization->slug, 'campSlug' => $campaign->slug]) }}" class="btn rounded-pill">{{ __("Donate") }}</a>
            </div>
        </div>
        @endforeach
    </section>
@endif
@endsection
