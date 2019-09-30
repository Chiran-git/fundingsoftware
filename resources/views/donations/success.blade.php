@extends('layouts.app')
@section('title', __('Thank you for your donation'))
@section('content')

    <div class="row">
        <div class="col-lg-7 m-lg-auto px-lg-0">
            <div class="my-5">
                <h2 class="aleo mb-0">{{ __('Thank you, :first_name!', ['first_name' => $donor->first_name]) }}</h2>
                <div>{{ __('A receipt has been emailed to :email.', ['email' => $donor->email]) }}</div>
            </div>

            <div class="thanks-box">
                <div class="mb-3 d-flex px-md-4">
                    <div class="">
                        @if ($organization->logo)
                            <div class="img-fluid float-left rounded-circle mr-2 w-h-50" style="background-image: url({{ RJ::assetCdn($organization->logo) }}); background-repeat: no-repeat; background-size: cover;"></div>
                        @endif
                    </div>
                    <div class="ml-3 mt-2 break-word">
                        <h3 class="mb-1">{{ $organization->name }}</h3>
                        {{ \Illuminate\Mail\Markdown::parse($campaign->donor_message) }}
                    </div>
                </div>

                <div class="my-5 contribution-bg">
                    <h3 class="aleo text-center mb-1 break-word">{{ $campaign->name }}</h3>
                    <div class="info-text">{!! __('Your <strong>:amount</strong> contribution brings our total raised to <strong>:total</strong>!', [
                        'amount' => RJ::donationMoney($donation->gross_amount, $organization->currency->symbol),
                        'total' => RJ::donationMoney($campaign->funds_raised, $organization->currency->symbol),
                    ]) !!}</div>
                    <div class="d-flex progressbar-container progressbar-container--white my-3 px-md-3">
                        @include('campaign.partials.progress', ['campaign' => $campaign])
                    </div>
                </div>

                <div class="text-center">
                    <h3 class="aleo">{{ __('Spread the word:') }}</h3>
                    {{-- <ul class="list-inline mb-0 mt-auto" data-easyshare data-easyshare-url="">
                        <li class="list-inline-item mb-2">
                            <button data-easyshare-button="facebook" data-href="{{ url()->current() }}" class="btn btn-facebook rounded-pill cursor-pointer assistant text-left py-1 pl-4 l-h-normal">
                                <!--<span data-easyshare-button-count="facebook">0</span>-->
                                <i class="fab fa-facebook-f mr-2"></i>Share
                            </button>
                        </li>
                        <li class="list-inline-item mb-2">
                            <button data-easyshare-button="twitter" data-easyshare-tweet-text="" class="btn btn-twitter rounded-pill cursor-pointer assistant text-left py-1 pl-4 l-h-normal"><i class="fab fa-twitter mr-2"></i></i>Tweet</button>
                        </li>
                        <li class="list-inline-item mb-2">
                            <a class="btn btn-email rounded-pill cursor-pointer assistant text-left py-1 pl-4 l-h-normal" href="mailto:?subject=your%20subject&amp;body=your%20body"><i class="fa fa-envelope fa-fw mr-2"></i>Email</a>
                        </li>
                    </ul> --}}
                    @php
                        $shareUrl = route('campaign.show', ['orgSlug' => $organization->slug, 'campSlug' => $campaign->slug]);
                    @endphp
                    @include('partials.common.social-share', [
                        'shareUrl' => $shareUrl,
                        'emailButton' => true,
                        'shareText' => __('Fund projects that matter :url', ['url' => $shareUrl]),
                        'shareHeadline' => $campaign->name,
                    ])
                </div>
            </div>

        </div>
    </div>

@endsection
