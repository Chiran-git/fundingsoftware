@extends('layouts.app')

@section('title')
{{ $campaign->name }}
@endsection

@section('opengraph')
    <!-- Twitter Card data -->
    <meta name="twitter:card" content="summary" />
    <meta name="twitter:site" content="@rocketjar" />

    <!-- Open Graph data -->
    <meta property="og:title" content="{{ $campaign->name }} | RocketJar" />

    <meta property="og:type" content="website" />
    <meta property="og:url" content="{{ request()->url() }}" />
    @if ($campaign->image)
    <meta property="og:image" content="{{ $campaign->image }}" />
    @endif
    <meta property="og:description" content="{{ $campaign->excerpt }}" />
@endsection

@section('content')
<share-modal inline-template
    modal-id="organization-share-modal"
    modal-title="{{ __('Share this Campaign') }}"
    modal-subtitle="{{ $campaign->name }}"
    share-url="{{ route('campaign.show', ['orgSlug' => $organization->slug, 'campSlug' => $campaign->slug]) }}"
    share-headline="{{ $campaign->name }}"
    share-text="{{ __('Fund projects that matter') }} {{ route('campaign.show', ['orgSlug' => $organization->slug, 'campSlug' => $campaign->slug]) }}">
    @include('partials.modals.modal-share')
</share-modal>

<section class="donor-hero full-width">
    <div class="donor-hero-filter" style="border-color: #{{ $organization->secondary_color }}; background-color: #{{ $organization->primary_color }}">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="donor-hero-filter__body">
                        <div class="row align-items-center">
                            <div class="col p-5 d-flex flex-column h-405 order-2 order-lg-1">
                                <h2 class="aleo mb-0 break-word">{{ $campaign->name }}</h2>
                                <span class="f-20">{{ $organization->name }}</span>
                                <div class="d-flex progressbar-container progressbar-container--thick mt-auto">
                                    @include('campaign.partials.progress', ['campaign' => $campaign])
                                </div>
                                <a href="{{ route('campaign.donate', [
                                    'orgSlug' => $organization->slug,
                                    'campSlug' => $campaign->slug,
                                ]) }}" class="btn btn--size14 rounded-pill btn-full-m mt-auto">{{ __('Donate Now') }}</a>
                            </div>
                            <div class="col-12 col-lg-7 order-1 order-lg-2">
                                {{-- <img src="{{ RJ::assetCdn($campaign->image) }}" alt="{{ $campaign->name }}"> --}}
                                @if ($campaign->image)
                                <div class="donor-hero-filter__image" style="background-image:url({{ $campaign->image }})"></div>
                                @else
                                <svg width="100%" height="405">
                                    <rect width="100%" height="100%" fill="#e2e2e2">
                                    </rect>
                                    <text x="50%" y="50%" fill="#222222" text-anchor="middle" alignment-baseline="central" font-weight="bold">No Image</text>
                                </svg>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="our-story mt-5 border-0">
    <div class="row">
        <div class="col-md-8 mb-4 mb-md-0 preview--html">
            <h3 class="aleo">{{ __('Our Story') }}</h3>
            <div class="f-16 break-word">{{ \Illuminate\Mail\Markdown::parse($campaign->description) }}</div>
            <!-- Show on Desktop -->
            <div class="d-none d-md-block">
                <h3 class="mt-4">{{ __('Share this Campaign:') }}</h3>
                @include('partials.common.social-share', [
                    'shareUrl' => url()->current(),
                    'shareButton' => true,
                    'shareText' => __('Fund projects that matter :url', ['url' => url()->current()]),
                    'shareHeadline' => $campaign->name,
                ])

                <div class="organizer d-flex flex-wrap my-5">
                    <h3 class="aleo w-100">{{ __('Organizer') }}</h3>
                    @if ($organization->logo)
                    <div class="mr-4">
                        <img class="btn-file--small rounded-circle float-left" src="{{ $organization->logo }}" alt="{{ $organization->name }}">
                    </div>
                    @endif
                    <address>
                        <h4 class="mb-0">{{ $organization->name }}</h4>
                        {{ $organization->address1 }}<br>
                        @if ($organization->address2)
                        {{ $organization->address2 }}<br>
                        @endif
                        @if ($organization->city && $organization->state)
                            {{ $organization->city }}, {{ $organization->state }}
                        @else
                            {{ $organization->city }}
                            {{ $organization->state }}
                        @endif
                        {{ $organization->zipcode }}
                    </address>
                </div>
            </div>
        </div>

        <div class="col-md-4 pl-lg-5">
            @if ($campaign->rewards()->count())
                <h3 class="aleo">{{ __('Rewards') }}</h3>

                @foreach ($campaign->rewards()->get() as $reward)
                    @if ($reward->image)
                        <div class="d-md-flex d-none">
                            <img src="{{ RJ::assetCdn($reward->image) }}" alt="{{ $reward->title }}">
                        </div>
                    @endif
                    <div class="rewards-card p-4 mb-3 mob-full-width">
                        @if ($reward->image)
                            <div class="float-right d-block d-md-none">
                                <img class="m-w-120" src="{{ RJ::assetCdn($reward->image) }}" alt="{{ $reward->title }}">
                            </div>
                        @endif
                        <div class="adjust-amount">
                            <sup>{{ $organization->currency->symbol }}</sup> <span class="rewards-card__amount rewards-card__amount--fs-40">{{  $reward->min_amount }}</span>
                        </div>
                        <div class="">
                            <div class="rewards-card__title f-18 assistant break-word">{{ $reward->title }}</div>
                            <div class="rewards-card__subtitle break-word">{{ $reward->description }}</div>
                            {{-- <a href="#" class="cta assistant">{{ __('Read More') }}</a> --}}
                        </div>
                        <div class="">
                            <div class="rewards-card__status d-flex flex-wrap align-items-center mt-4">
                                @if ($reward->quantity > $reward->quantity_rewarded)
                                    <a href="{{ route('campaign.donate', [
                                    'orgSlug' => $organization->slug,
                                    'campSlug' => $campaign->slug,
                                    ]) }}" class="btn btn--m-w-auto rounded-pill mr-3 f-12">{{ __('Select') }}</a>
                                @else
                                    <button disabled type="submit" class="btn-file rounded-pill mr-3 mb-2 f-12 p-2">{{ __('Reward no longer available') }}</button>
                                @endif
                                <span class="f-14">{!! __('<strong>:rewarded</strong> of <strong>:quantity</strong> claimed', ['quantity' => $reward->quantity, 'rewarded' => $reward->quantity_rewarded ?: 0]) !!}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif

            @php
                $donationsCount = $campaign->donations()->count();
                $count = 0;
            @endphp
            @if ($donationsCount)
                <div class="px-4 px-md-0">
                    <h3 class="aleo mt-5">{{ __('Thanks to our Supporters!') }}</h3>
                    <div class="rewards-card p-4 mb-2">
                        @foreach ($campaign->donations()->orderBy('created_at', 'desc')->limit(3)->get() as $donation)
                        <div class="supporters">
                            {{ $donation->donor->name }} <span>{{ RJ::donationMoney($donation->gross_amount, $donation->currency->symbol) }}</span>
                            <div class="time">{{ $donation->created_at->diffForHumans() }}</div>
                        </div>
                        @php $count++; @endphp
                        @endforeach
                    </div>
                    @if ($donationsCount - $count)
                        <div>{!! __('and <strong>:count</strong> others', ['count' => $donationsCount - $count]) !!}</div>
                    @endif
                </div>
            @endif

            <!-- Show on Mobile -->
            <div class="d-block d-md-none mob-full-width">
                <div class="organizer mt-5 pb-0 border-bottom-0 px-4 px-sm-0">
                    <h3 class="mt-4">{{ __('Share this Campaign:') }}</h3>
                    @include('partials.common.social-share', [
                        'shareUrl' => url()->current(),
                        'shareButton' => true,
                        'shareText' => __('Fund projects that matter :url', ['url' => url()->current()]),
                        'shareHeadline' => $campaign->name,
                    ])
                </div>

                <div class="organizer d-flex flex-wrap my-5 px-4 px-sm-0">
                    <h3 class="aleo w-100">{{ __('Organizer') }}</h3>
                    @if ($organization->logo)
                    <div class="mr-4">
                        <img class="btn-file--small rounded-circle float-left" src="{{ RJ::assetCdn($organization->logo) }}" alt="{{ $organization->name }}">
                    </div>
                    @endif
                    <address>
                        <h4 class="mb-0">{{ $organization->name }}</h4>
                        {{ $organization->address1 }}<br>
                        @if ($organization->address2)
                        {{ $organization->address2 }}<br>
                        @endif
                        @if ($organization->city && $organization->state)
                            {{ $organization->city }}, {{ $organization->state }}
                        @else
                            {{ $organization->city }}
                            {{ $organization->state }}
                        @endif
                        {{ $organization->zipcode }}
                    </address>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
