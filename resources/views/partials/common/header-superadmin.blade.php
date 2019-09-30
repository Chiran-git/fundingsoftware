<header>
    <nav class="navbar navbar-dark bg-dark p-0">
        <div class="container flex-nowrap flex-lg-wrap">
            <a class="navbar-brand my-0 logo-sp mr-lg-0" href="{{ url('/') }}">
                <img src="{{ asset('images/logo-sp-ad.png') }}" alt="logo-admin" class="img-fluid">
            </a>
            <ul class="navbar-nav align-items-lg-center mr-md-auto">
                <li class="nav-item {{ request()->is('*admin/dashboard*') ? 'active' : '' }}">
                    <a class="nav-link nav-link--icon" href="{{ route('admin.dashboard') }}">
                        <span class="d-none d-md-block">{{ __('AE Dashboard')}}</span>
                        <i class="fas fa-home"></i>
                    </a>
                </li>
                <li class="nav-item {{ request()->is('*organization*') ? 'active' : '' }}">
                    <a class="nav-link nav-link--icon" href="{{ route('admin.organizations') }}">
                        <span class="d-none d-md-block">{{ __('Organizations')}}</span>
                        <i class="fas fa-sitemap"></i>
                    </a>
                </li>
                <li class="nav-item {{ request()->is('*admins*') ? 'active' : '' }}">
                    <a class="nav-link nav-link--icon" href="{{ route('admin.admins') }}">
                            <span class="d-none d-md-block">{{ __('Admins')}}</span>
                        <i class="fas fa-users-cog"></i>
                    </a>
                </li>
            </ul>
            <ul class="navbar-nav align-items-lg-center">
                {{--  <li class="nav-item">
                    <a class="nav-link nav-link--icon" href="#">
                        <i class="fas fa-plus-circle ipluse"></i>
                    </a>
                </li>  --}}
                <li class="dropdown nav-item">
                    <a class="dropdown-toggle nav-link nav-link--icon" data-toggle="dropdown" href="#" role="button"
                        aria-haspopup="false" aria-expanded="false">
                        <span>
                            <i class="fas fa-plus-circle ipluse"></i>
                        </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu--top-arrow dropdown-menu-right dropdown-menu-animated topbar-dropdown-menu">
                        <!-- item-->
                        <a href="{{ route('admin.organization.create') }}" class="dropdown-item notify-item">
                            <i class="mdi mdi-account-circle mr-1"></i>
                            <span>{{ __('New Organization')}}</span>
                        </a>

                        <!-- item-->
                        <a href="#" class="dropdown-item notify-item" data-toggle="modal" data-target="#select-organization-campaign">
                            <i class="mdi mdi-account-edit mr-1"></i>
                            <span>{{ __('New Campaign')}}</span>
                        </a>

                        <!-- item-->
                        <a href="#" class="dropdown-item notify-item" data-toggle="modal" data-target="#select-organization-donation">
                            <i class="mdi mdi-logout mr-1"></i>
                            <span>{{ __('Record Donation')}}</span>
                        </a>

                        <!-- item-->
                        <a href="{{ route('admin.payout') }}" class="dropdown-item notify-item">
                            <i class="mdi mdi-account-circle mr-1"></i>
                            <span>{{ __('Record Payouts')}}</span>
                        </a>

                    </div>
                </li>
                <li class="dropdown dropdown--search nav-item">
                    <a class="dropdown-toggle nav-link nav-link--icon" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                        <i class="fas fa-search search"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-animated topbar-dropdown-menu">
                        <form class="form-inline mt-2 mt-md-0 px-3 px-md-0">
                            <div class="form-group has-search">
                                {{-- <span class="fa fa-search form-control-feedback"></span>
                                <input type="text" class="form-control" placeholder="Search"> --}}
                                <org-search-autocomplete inline-template>
                                    <form class="form-inline my-2 my-lg-0" @submit.prevent="">
                                        <autocomplete
                                            ref="search"
                                            source="{{ route('admin.search', ['q' => '']) }}"
                                            method="post"
                                            placeholder="{{ __('Search') }}"
                                            input-class="form-control mr-sm-2 w-100"
                                            :results-display="showSearchResults"
                                            :request-headers="httpHeaders"
                                            :show-no-results="true"
                                            @selected="showOrganizationForAdmin">
                                          </autocomplete>
                                    </form>
                                </org-search-autocomplete>
                            </div>
                        </form>
                    </div>
                </li>
                <li class="dropdown nav-item">
                    <a class="dropdown-toggle nav-user mr-0" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                        @if (session()->has('impersonator_name'))
                        <span class="d-none d-md-block">
                            <span class="account-user-name">{{ Session::get('impersonator_name') }}</span>
                            <span class="account-position assistant font-weight-bold text-grey-5">{{ Session::get('impersonator_role') }}</span>
                        </span>
                        @else
                        <span class="d-none d-md-block">
                            <span class="account-user-name">{{ auth()->user()->name }}</span>
                            <span class="account-position assistant font-weight-bold">{{ auth()->user()->user_type }}</span>
                        </span>
                        @endif
                        <span class="account-user-avatar">
                            @if (Session::get('impersonator_image') != null)
                                <img src="{{ Session::get('impersonator_image') }}" alt="user-image" class="rounded-circle avatar">
                            @else
                                @if (auth()->user()->image)
                                    <img src="{{ RJ::assetCdn(auth()->user()->image) }}" alt="user-image" class="rounded-circle avatar">
                                @else
                                    <img src="{{ asset('images/users/avatar-1.png') }}" alt="user-image" class="rounded-circle avatar">
                                @endif
                            @endif
                        </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-animated topbar-dropdown-menu">
                        <!-- item-->
                        <a href="{{ route('admin.myaccount') }}" class="dropdown-item notify-item">
                            <i class="mdi mdi-account-circle mr-1"></i>
                            <span>{{ __('My Profile')}}</span>
                        </a>

                        <!-- item-->
                        <a href="{{ route('admin.change-password') }}" class="dropdown-item notify-item">
                            <i class="mdi mdi-account-edit mr-1"></i>
                            <span>{{ __('Change Password')}}</span>
                        </a>

                        <!-- item-->
                        <a href="#" class="dropdown-item notify-item"
                                onclick="event.preventDefault();
                                        document.getElementById('logout-form').submit();">
                            <i class="mdi mdi-logout mr-1"></i>
                            <span>{{ __('Log Out')}}</span>
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>

                    </div>
                </li>
            </ul>
        </div>
    </nav>
</header>

@include('admin.modals.switch-organization')

