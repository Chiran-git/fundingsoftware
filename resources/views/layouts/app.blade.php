<!DOCTYPE html>
<html lang="en">
    @include('partials.common.head', ['layout' => 'app'])
<body>
    @php
        $routeName = request()->route()->getName();
    @endphp
    <div id="app" class="wrapper" v-cloak>
        @guest
            @if (request()->is('/') || request()->is('signup'))
                @include('partials.common.header')
            @else
                @include('partials.common.header-donor')
            @endif
        @else
            @if (auth()->user()->isSuperAdmin() || auth()->user()->isAppAdmin() )
                @include('partials.common.header-superadmin')

            {{-- Else show the organization header/nav only if not on frontend pages (donation pages) --}}
            @elseif (! in_array($routeName, [
                'organization.show',
                'campaign.show',
                'campaign.donate',
                'donation.make',
                'donation.success'
                ]
            ))
                @include('partials.common.header-organization')
            @endif
        @endguest
        <div class="{{ request()->is("/") ? '' : 'container' }}" >
            @yield('content')
        </div>
        @include('partials.common.footer')
    </div>
    @include('partials.modals.modal-message', [
        'modalId' => 'modal-session-expired',
        'modalTitle' => __('Session Expired'),
        'modalBody' => __('Your session has expired. Please login again to continue.'),
        'buttons' => [
            'action' => [
                'title' => __('Go To Login'),
                'url' => route('login'),
            ]
        ]
    ])
    <!-- JavaScript -->
    <script src="{{ asset('js/manifest.js') }}"></script>
    <script src="{{ mix('js/vendor.js') }}"></script>
    <script src="{{ mix('js/app.js') }}"></script>
    <script src="{{ mix('js/plugins.js') }}"></script>
    <script>
        @yield('footer-scripts');
    </script>
</body>
</html>
