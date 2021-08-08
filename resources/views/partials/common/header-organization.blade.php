<header>
    @if (Session::get('impersonated_by'))
        @include('partials.common.header-superadmin')
    @endif

    <nav class="navbar navbar-expand-md nav-secondary py-0">
        <div class="container">
            @if (! Session::get('impersonated_by'))
            <a class="navbar-brand mr-md-auto" href="{{ url('/') }}">
                <img src="{{ asset('images/rocketjar-logo-wht.png') }}" alt="logo-organization" class="img-fluid">
            </a>
            @endif
            <button class="navbar-toggler collapsed ml-auto" type="button" data-toggle="collapse"
                data-target="#navbarCollapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <div class="navbar-collapse collapse" id="navbarCollapse" style="">
                @if (auth()->user()->organization->present()->status() !== 'setup_needed')
                @php
                $dashboardClass = $campaignClass = $donationClass = '';
                if (Request::is('*dashboard*')) {
                $dashboardClass = 'active';
                } else {
                $dashboardClass = '';
                }
                $currentOrgId = auth()->user()->organization->id;
                $role = auth()->user()->findAssociatedOrganization($currentOrgId)->pivot->role ;
                @endphp
                <ul class="topbar--menu {{ Session::get('impersonated_by') ? '' : 'pl-lg-5' }}">
                    <li class="nav-item {{ $dashboardClass }}">
                        <a class="nav-link" href="{{ route('dashboard') }}">{{ __('Dashboard')}}</a>
                    </li>

                    <li class="nav-item {{ request()->is('*campaign*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('campaign.index') }}">{{ __('Campaigns')}}</a>
                    </li>
                    <li
                        class="dropdown {{ request()->is('*donations*') || request()->is('*donos') || request()->is('*payouts') || request()->is('*connected-account') ? 'active' : '' }}">
                        <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="false">{{ __('Donations')}}</a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="{{ route('donations.index') }}">{{ __('Donations')}}</a>
                            @if (in_array($role, ['owner', 'admin']) )
                            <a class="dropdown-item" href="{{ route('donors.index') }}">{{ __('Donor List')}}</a>
                            <a class="dropdown-item" href="{{ route('payouts.index') }}">{{ __('Pay-Out History')}}</a>
                            <a class="dropdown-item"
                                href="{{ route('connected-account.index') }}">{{ __('Pay-Out Accounts')}}</a>
                            <a class="dropdown-item"
                                href="{{ route('reports.affiliations') }}">{{ __('Affiliation Reports')}}</a>
                            @endif
                        </div>
                    </li>
                </ul>
                @endif

                <ul class="topbar--menu align-items-lg-center ml-auto">
                    @if (auth()->user()->organization->present()->status() !== 'setup_needed' && !Session::get('impersonated_by'))
                    <li class="dropdown">
                        <a class="dropdown-toggle nav-user mr-0" data-toggle="dropdown" href="#" role="button"
                            aria-haspopup="false" aria-expanded="false">
                            <span class="account-user-avatar account-user-avatar--alt">
                                @if (auth()->user()->organization->logo)
                                <img src="{{ auth()->user()->organization->logo }}" alt="user-image"
                                    class="rounded-circle avatar">
                                @else
                                <img src="{{ asset('images/users/avatar-1.png') }}" alt="user-image"
                                    class="rounded-circle">
                                @endif
                            </span>
                            <span>
                                <span class="account-user-name f-md-14">{{ auth()->user()->organization->name }}</span>
                            </span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-animated topbar-dropdown-menu ">
                            @if (in_array($role, ['owner', 'admin']) )
                            <!-- item-->
                            <a href="{{ route('organization.edit') }}" class="dropdown-item notify-item">
                                <i class="mdi mdi-account-circle mr-1"></i>
                                <span>{{ __('Edit organization')}}</span>
                            </a>
                            @endif

                            <!-- item-->
                            <a href="#" class="dropdown-item notify-item" data-toggle="modal" data-target="#view_page">
                                <i class="mdi mdi-account-edit mr-1"></i>
                                <span>{{ __('view page')}}</span>
                            </a>

                            <!-- item-->
                            @if (in_array($role, ['owner', 'admin']))
                            <a href="{{ route('organization.edit', ['step' => 4]) }}" class="dropdown-item notify-item">
                                <i class="mdi mdi-logout mr-1"></i>
                                <span>{{ __('Account users')}}</span>
                            </a>
                            @endif
                        </div>
                    </li>
                    @endif

                    <li class="dropdown">
                        @if (Session::get('impersonated_by'))
                        <a class="dropdown-toggle nav-user mr-0" data-toggle="dropdown" href="#" role="button"
                            aria-haspopup="false" aria-expanded="false">
                            <span class="account-user-avatar account-user-avatar--alt">
                                @if (auth()->user()->organization->logo)
                                <img src="{{ auth()->user()->organization->logo }}" alt="user-image"
                                    class="rounded-circle avatar">
                                @else
                                <img src="{{ asset('images/users/avatar-1.png') }}" alt="user-image"
                                    class="rounded-circle">
                                @endif
                            </span>
                            <span>
                                <span class="account-user-name f-md-14">{{ auth()->user()->organization->name }}</span>
                            </span>
                        </a>
                        @else
                        <a class="dropdown-toggle nav-user mr-0" data-toggle="dropdown" href="#" role="button"
                            aria-haspopup="false" aria-expanded="false">
                            <span class="account-user-avatar account-user-avatar--alt">
                                @if (auth()->user()->image)
                                <img src="{{ RJ::assetCdn(auth()->user()->image) }}" alt="user-image"
                                    class="rounded-circle avatar">
                                @else
                                <img src="{{ asset('images/users/avatar-1.png') }}" alt="user-image"
                                    class="rounded-circle">
                                @endif
                            </span>
                            <span>
                                <span class="account-user-name f-md-14">{{ auth()->user()->name }}</span>
                            </span>
                        </a>
                        @endif
                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-animated topbar-dropdown-menu ">
                            @if (Session::get('impersonated_by'))
                                <!-- item-->
                                <a href="{{ route('organization.edit') }}" class="dropdown-item notify-item">
                                    <i class="mdi mdi-account-circle mr-1"></i>
                                    <span>{{ __('Edit organization')}}</span>
                                </a>
                                <!-- item-->
                                <a href="#" class="dropdown-item notify-item" data-toggle="modal" data-target="#view_page">
                                    <i class="mdi mdi-account-edit mr-1"></i>
                                    <span>{{ __('view page')}}</span>
                                </a>
                                <a href="{{ route('organization.edit', ['step' => 4]) }}" class="dropdown-item notify-item">
                                    <i class="mdi mdi-logout mr-1"></i>
                                    <span>{{ __('Account users')}}</span>
                                </a>
                                <a href="#" class="dropdown-item notify-item" data-toggle="modal" data-target="#switch-organization">
                                    <i class="mdi mdi-account-edit mr-1"></i>
                                    <span>{{ __('Switch Organization')}}</span>
                                </a>
                            @else
                                <!-- item-->
                                <a href="{{ route('myaccount') }}" class="dropdown-item notify-item">
                                    <i class="mdi mdi-account-circle mr-1"></i>
                                    <span>{{ __('My Profile')}}</span>
                                </a>

                                <!-- item-->
                                <a href="{{ route('change-password') }}" class="dropdown-item notify-item">
                                    <i class="mdi mdi-account-edit mr-1"></i>
                                    <span>{{ __('Change Password')}}</span>
                                </a>
                                <!-- item-->
                                <a href="#" class="dropdown-item notify-item"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="mdi mdi-logout mr-1"></i>
                                    <span>{{ __('Log Out')}}</span>
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            @endif
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>

@include('organization.preview.modal-view', [
'modalId' => 'modal-template-view',
])
