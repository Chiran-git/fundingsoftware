<footer class="{{ (request()->is('/') || request()->is('signup')) ? 'footer' : 'footer footer--grayscale' }}">
    <div class="container">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
            <div class="text-md-left d-flex flex-md-column align-items-end order-2">
                <a class="my-0 mr-md-auto logo-secondary img-fluid" href="{{ url('/') }}">
                <img src="{{ asset('images/logo-footer-inverted.png') }}" alt="footer-logo">
                </a>
                <h6 class="pb-1 pb-md-0 my-0 mr-md-auto font-weight-normal"> {{ __('© 2019 RocketJar, Inc. All rights reserved.') }}</h6>
            </div>
            <nav class="my-2 my-md-0 mr-md-3 order-1 order-md-12">
                <a class="p-2 text-dark" href="{{ route('terms-of-service') }}">{{ __('Terms of Service') }}</a>
                <a class="p-2 text-dark" href="{{ route('privacy') }}">{{ __('Privacy Policy') }}</a>
                <a class="p-2 text-dark" href="mailto:help@rocketjar.com">{{ __('Support') }}</a>
            </nav>
        </div>
    </div>
</footer>
