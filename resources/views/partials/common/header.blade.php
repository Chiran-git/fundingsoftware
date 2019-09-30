@if (! request()->is('login'))
<header class="header d-flex flex-column justify-content-center">
    <nav class="navbar nav-inverse navbar-expand-md">
        <a class="navbar-brand my-0 mr-md-auto logo" href="{{ url('/') }}"></a>
        <button id="nav-icon" class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
            <span></span>
            <span></span>
            <span></span>
            <span></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <ul class="navbar-nav ml-md-auto d-flex align-items-center">
                <li class="nav-item active"><a class="nav-link" href="{{ route('home') }}#tour">{{ __('Tour') }}</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('home') }}#how-it-works">{{ __('How It Works') }}</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('home') }}#pricing">{{ __('Pricing') }}</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a></li>
                <li class="nav-item"><a class="btn-threed" href="{{ route('org-signup') }}"><span>{{ __('Sign Up Today') }}</span></a></li>
            </ul>
        </div>
    </nav>
    <div class="bg-container" style="background-image: url('../images/signup-hero.jpg');"></div>
    <!-- /.bg-container -->
    <div class="container">
        <div class="row justify-content-center text-center align-items-center">
            <div class="col-xl-12 col-lg-9 col-md-10 layer-3 aos-init aos-animate" data-aos="fade-up" data-aos-delay="500">
                <div class="header__body my-4">
                    <h1>Fund projects that <span>matter</strong></h1>
                        <org-search-autocomplete inline-template>
                        <form class="form-inline my-2 my-lg-0" @submit.prevent="">
                            <autocomplete
                                ref="search"
                                source="{{ route('organization.search', ['q' => '']) }}"
                                method="post"
                                placeholder="{{ __('Find your organization') }}"
                                input-class="form-control mr-sm-2 w-100"
                                :results-display="showItem"
                                :request-headers="httpHeaders"
                                :show-no-results="true"
                                @selected="showOrganization">
                              </autocomplete>
                        </form>
                        </org-search-autocomplete>
                </div>
            </div>
        </div>
    </div>
    <!-- /.container -->
</header>
@endif
