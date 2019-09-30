@extends('layouts.app')

@section('content')

    <div class="row">
        <div class="col-lg-7 m-lg-auto px-lg-0 donor-email">
            <header class="d-flex justify-content-between bg-white p-3">
                <div>
                    <h3 class="mb-0">{{ __('RocketJar') }}</h3>
                    <div class="f-18">{{ ('Receipt: Donation to Assumption High School') }}</div>
                    <div class="f-18">{!! __('To: <label class="text-grey-5">Janice Smith</label>') !!}</div>
                </div>
                <div class="text-grey-5">{{ __('Today at 9:42 AM') }}</div>
            </header>

            <div class="donor-email__body">
                <div class="donor-email__inner mb-4">
                    <div class="mb-3 d-flex align-items-center pb-4 border-bottom">
                        <div>
                            <img src="../images/assumption-high-school.png" alt="Assumption High School">
                        </div>
                        <div class="ml-3">
                            <h4>{{ __('Assumption High School') }}</h4>
                        </div>
                    </div>

                    <div class="my-5">
                        <h2 class="aleo mb-0">{{ __('Thank you, Janice!') }}</h2>
                        <div>{{ __('Thanks for donating to our fundraiser! If you have any questions, please contact me at donald@assumptionhighschool.jcpsky.edu.') }}</div>
                    </div>

                    <div class="thanks-box px-0 py-4 mb-5">
                        <h3 class="aleo text-center mb-1">{{ __('Annual Summer Track Meet Fundraiser') }}</h3>
                        <div class="my-4 contribution-bg">
                            <div class="info-text">{!! __('Your <strong>$15</strong> contribution brings our total raised to <strong>$2,515</strong>!') !!}</div>
                            <div class="d-flex progressbar-container my-3 px-md-3">
                                <div class="w-100">
                                    <div class="d-flex mb-2">
                                        <div><span>$2,500</span> of $10,000</div>
                                    </div>
                                    <div class="progress rounded-pill">
                                        <div role="progressbar" aria-valuenow="26" aria-valuemin="0" aria-valuemax="100" class="progress-bar w-25"></div>
                                    </div>
                                </div>
                                <div class="flex-shrink-1 align-self-end">
                                    <div class="ref pl-2">26%</div>
                                </div>
                            </div>
                        </div>
                        <div class="px-3 px-md-5">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <strong>{{ __('DATE') }}</strong>
                                        <div>{{ __('11/4/2019 9:48am') }}</div>
                                    </div>
                                    <div class="mb-3">
                                        <strong>{{ __('FROM') }}</strong>
                                        <div>{{ __('Janice Smith') }}</div>
                                    </div>
                                    <div class="mb-3">
                                        <strong>{{ __('DONATION AMOUNT') }}</strong>
                                        <div>{{ __('$15.00') }}</div>
                                    </div>
                                    <div class="mb-3">
                                        <strong>{{ __('METHOD') }}</strong>
                                        <div>{{ __('Online (VISA 5713)') }}</div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <strong>{{ __('RECEIPT NO.') }}</strong>
                                        <div>{{ __('3229987') }}</div>
                                    </div>
                                    <div class="mb-3">
                                        <strong>{{ __('TO') }}</strong>
                                        <div>
                                            {!! __('<address>Track Meet Fundraiser<br>
                                            Assumption High School<br>
                                            123 Main Street<br>
                                            Louisville, KY 40202</address>') !!}
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <strong>{{ __('REWARD') }}</strong>
                                        <div>{{ __('Water Bottle') }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <h3 class="aleo">{{ __('Tell your friends:') }}</h3>
                    @include('partials.common.social-share', ['shareUrl' => url()->current(), 'shareButton' => ''])
                </div>
                <div class="text-center text-grey-5">{!! __('&copy; 2019 RocketJar. All rights reserved.') !!}</div>
            </div>
        </div>
    </div>

@endsection
