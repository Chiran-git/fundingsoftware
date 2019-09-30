<!DOCTYPE html>
<html lang="en">
    @include('partials.common.head', ['layout' => 'auth'])
<body class="image-background-default-image">
    <div id="app" class="h-100" v-cloak>
        <div class="site-wrapper">
            <div class="site-wrapper-inner">
                <div class="container inner">
                    @yield('content')
                </div>
                @include('partials.common.footer')
            </div>
        </div>
    </div>
    <!-- JavaScript -->
    <script src="{{ asset('js/manifest.js') }}"></script>
    <script src="{{ mix('js/vendor.js') }}"></script>
    <script src="{{ mix('js/app.js') }}"></script>
    <script src="{{ mix('js/plugins.js') }}"></script>
</body>
</html>
