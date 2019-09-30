<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>
        @if(request()->is('/'))
            @yield('title', config('app.name'))
        @else
            @yield('title') | {{ config('app.name') }}
        @endif
    </title>
    @yield('opengraph')
    <link href='https://use.fontawesome.com/releases/v5.2.0/css/all.css' rel='stylesheet' type='text/css'>
    @if ($layout == 'app')
        <link href="{{ mix('css/app.css') }}" rel="stylesheet">
        <link href="{{ mix('css/style.css') }}" rel="stylesheet">
    @else
        <link href="{{ mix('css/auth.css') }}" rel="stylesheet">
        <link href="{{ mix('css/style.css') }}" rel="stylesheet">
    @endif
    <!-- Global RJ Object -->
    <script>
        window.RJ = <?php echo json_encode(array_merge(
            RJ::scriptVariables(), [
                'translations' => trans('js'),
                'baseUrl' => url('/'),
                'userId' => auth()->user() ? auth()->user()->id : null,
                'apiBaseUrl' => url('/api/v1'),
                'defaults' => config('app.defaults'),
                'pagination' => config('pagination'),
                'stripeKey' => env('STRIPE_KEY'),
            ]
        )); ?>;
    </script>
    <script src="https://js.stripe.com/v3/"></script>
    @yield('head-tags')
</head>
