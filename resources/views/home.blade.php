@extends('layouts.app')

@section('title')
{{ __('RocketJar') }}
@endsection

@section('title', "RocketJar")

@section('content')
<div class="homepage">
    <div class="logo-banner">
        <div class="container">
            <div class="row">
                <div class="col-12"></div>
            </div>
        </div>
    </div>
    <section class="section" id="tour">
        <div class="section__title">
            <div class="container tour1">
                <div class="row">
                    <div class="col-md-6">
                        <h2>{{ __('Powerful Funding Tools for Your Organization') }}</h2>
                        <p class="headline">{{ __('Rocketjar is a crowdfunding platform with a mission to help your organization fund projects that are important to you. Rocketjar is available to churches, schools, hospitals, or organizations who need funds for anything.') }}</p>
                        <div class="text-center pb-5">
                            <a class="btn-threed d-inline-block" href="{{ route('org-signup') }}"><span>{{ __('Sign Up Today') }}</span></a>
                        </div>
                    </div>
                    <div class="col-md-6 firstsection_image">
                        <img src="{{asset('images/powerfulfundingtools.png')}}">
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="section" id="how-it-works">
        <div class="section__title">
            <div class="container tour1">
                <div class="row">
                    <div class="col-md-6 order-2">
                        <h2>{{ __('Targeted funding for your small projects') }}</h2>
                        <p class="headline">{{ __('We help you reach the community that cares most about your projects. Our platform is designed to be completely customized to your organization - that means you get your own website with your organization\'s branding.') }}</p>
                        <div class="text-center pb-5">
                            <a class="btn-threed d-inline-block" href="{{ route('org-signup') }}"><span>{{ __('Sign Up Today') }}</span></a>
                        </div>
                    </div>
                    <div class="col-md-6 order-1">
                        <img src="{{asset('images/targetedfunding.png')}}">
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="section" id="pricing">
        <div class="section__title border-bottom-0">
            <div class="container tour1">
                <div class="row">
                    <div class="col-md-6">
                        <h2>{{ __('Simple pricing and payouts') }}</h2>
                        <p class="headline">{{ __('FREE to sign-up where our customized administration and services include the development of your personalized funding website branded for your school, hosting services, unlimited campaigns and the administration of your donations that we collect on your behalf through our secure online platform and then distribute the funds directly to your bank account.') }}</p>
                        <div class="text-center pb-5">
                            <a class="btn-threed d-inline-block" href="{{ route('org-signup') }}"><span>{{ __('Sign Up Today') }}</span></a>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <img src="{{asset('images/simplespricing.png')}}">
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="signup-banner">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center">
                    <h3>{{ __('Get your projects funded') }} <strong>{{ __('Today!') }}</strong></h3>
                </div>
            </div>
            <form action="{{ route('org-signup') }}" method="get">
                <div class="row justify-content-center text-center">
                    <div class="col-12 col-sm-4">
                        <input type="text" name="name" placeholder="{{ __('Your Name') }}" class="form-control">
                    </div>
                    <div class="col-12 col-sm-4">
                        <input type="text" name="organization" placeholder="{{ __('Your Organization') }}" class="form-control">
                    </div>
                    <div class="col-12 col-sm-4">
                        <button type="submit" class="signup-button">{{ __('Sign Up Today') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </section>
    <section class="section">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-4 pb-3">
                    <h4 class="text-uppercase">{{ __('Follow Us') }}</h4>
                    <a href="https://twitter.com/rocketjar" target="_black"><img class="social-media-icon" src="{{asset('images/icons/twitter-circle.png')}}"></a>
                    <a href="https://www.facebook.com/rocketjar" target="_black"><img class="social-media-icon" src="{{asset('images/icons/facebook-circle.png')}}"></a>
                </div>
                <div class="col-xs-12 col-sm-4 pb-3">
                    <h4 class="text-uppercase">{{ __('Contact Us') }}</h4>
                    <p>
                        <strong>Phone:</strong> {{ __('(502) 442-2760') }}<br>
                        <strong>Fax:</strong> {{ __('(502) 415-7293') }}<br>
                        <strong>Email:</strong><a class="text-dark" href="mailto:help@rocketjar.com"> {{ __('help@rocketjar.com') }}</a><br>
                    </p>
                </div>
                <div class="col-xs-12 col-sm-4">
                    <h4 class="d-none d-sm-block">&nbsp;</h4>
                    <p>{{ __('946 Goss Ave') }}<br>
                        {{ __('Suite 3106') }}<br>
                        {{ __('Louisville, KY 40217') }}<br><br></p>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
