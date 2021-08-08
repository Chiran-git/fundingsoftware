@extends('layouts.app')

@section('title')
{{ __('Donate to :campaign', ['campaign' => $campaign->name]) }}
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

    <div class="row">
        <div class="col-lg-8 m-lg-auto px-lg-0">
            <div class="my-5">
                <h2 class="aleo mb-0 break-word">{{ $campaign->name }}</h2>
                <div class="aleo f-18 break-word">{{ $organization->name }}</div>
            </div>
            @php
                $donationAction = route('donation.make', [
                    'orgSlug' => $organization->slug,
                    'campSlug' => $campaign->slug,
                ]);
            @endphp
            <div class="mb-5">
                <form class="form-inline mt-2 mt-md-0" method="get" action="{{ $donationAction }}">
                    <h3 class="aleo font-weight-normal f-24 mb-2">{{ __('Contribute without a Reward') }}</h3>
                    <div class="col-12 bg-light-gray p-3 rounded">
                        <div class="d-md-flex">
                            <div>
                                <div class="form-group has-dollar">
                                    <span class="form-control-feedback">{{ $organization->currency->symbol }}</span>
                                    <input type="text" name="amount" placeholder="{{ __('Donation Amount') }}" class="form-control" value="{{ request()->amount }}">
                                </div>
                            </div>
                            <button type="submit" class="btn rounded-pill mt-2 mt-md-0 ml-md-3">{{ __('Continue') }}</button>
                        </div>
                    </div>
                </form>
            </div>
            @if ($campaign->rewards()->count())
            <div class="">
                <h3 class="aleo font-weight-normal f-24 mb-2">{{ __('or Choose a Reward') }}</h3>
                @foreach ($campaign->rewards()->get() as $reward)
                <form method="get" action="{{ $donationAction }}">
                    <input type="hidden" name="amount" value="{{ $reward->min_amount }}">
                    <input type="hidden" name="reward" value="{{ $reward->id }}">
                    <div class="bg-light-gray position-relative p-4 mb-3 rounded">
                        <div class="row">
                            <div class="col-lg-8">
                                <div class="">
                                    <div class="d-flex l-h-35 mb-2">
                                        <span class="sup sup--top f-24 aleo">{{ $organization->currency->symbol }}</span> <span class="rewards-card__amount rewards-card__amount--fs-40 mr-2">{{  $reward->min_amount }}</span>
                                        <span class="rewards-card__title assistant align-self-end break-word">{{ $reward->title }}</span>
                                    </div>
                                    <p class="f-16 mb-3 break-word">{{ $reward->description }}</p>
                                    <p class="f-16 text-grey-5">{!! __('<strong>:rewarded</strong> out of <strong>:quantity</strong> claimed', ['quantity' => $reward->quantity, 'rewarded' => $reward->quantity_rewarded ?: 0]) !!}</p>
                                    @if ($reward->quantity > $reward->quantity_rewarded)
                                        <button type="submit" class="btn btn--size12 rounded-pill mb-3 mb-lg-0 py-1">{{ __('Select Reward') }}</button>
                                    @else
                                        <button disabled type="submit" class="btn-file px-3 py-1 rounded-pill">{{ __('Reward no longer available') }}</button>
                                    @endif
                                </div>
                            </div>
                            @if ($reward->image)
                                <div class="col-12 col-lg-4">
                                    <img class="donor-reward-image" src="{{ RJ::assetCdn($reward->image) }}" alt="{{ $reward->title }}">
                                </div>
                            @endif
                        </div>
                    </div>
                </form>
                @endforeach
            @endif
        </div>
    </div>
@endsection
