<div class="modal fade pr-0" id="{{ $modalId }}" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-xl w-100 m-w-100">
        <div class="modal-content grayscale">
            <div class="container-fluid">
                <div class="row preview-bar py-3 align-items-center">
                    <div class="col-12 col-lg-1">
                        <h5 class="text-uppercase preview-bar__title">{{ __('PREVIEW') }}</h5>
                    </div>
                    <div class="col-12 col-lg-7">
                        <p class="f-14 my-3 my-lg-0">{{ __('Your unsaved changes are shown below. Save your changes or continue editing the campaign.') }}</p>
                    </div>
                    <div class="col-12 col-lg-4 text-lg-right">
                        <a href="#" class='btn btn--redoutline btn--lightborder btn--lightborder--ft rounded-pill mr-xl-3 mb-1 assistant f-16' data-dismiss="modal"
                        ><i class="fas fa-pencil-alt"></i> {{ __('Continue Editing')}}</a>
                        <a href="#" class='btn btn--redoutline btn--lightborder btn--lightborder--ft rounded-pill mb-1 assistant f-16'
                            @click.prevent="submit"
                            :disabled="form.busy">
                            <span v-if="! form.busy"><i class="fas fa-check"></i> {{ __('Save Changes')}}</span>
                            <span v-else>@include('partials.common.loading')</span>
                        </a>

                    </div>
                </div>
            </div>
            <nav class="navbar nav-inverse--light d-flex flex-md-row align-items-center px-4 py-2 bg-white border-bottom shadow-sm">
                @php $href = !empty($organization->slug) ? url("/{$organization->slug}") : url("/"); @endphp
                <a class="navbar-brand my-0 mr-md-auto donor-logo" href="{{ $href }}"></a>
                <ul class="d-flex align-items-center aleo">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle dropdown-toggle--modified f-20" href="#" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Share</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link f-20" :href="`${$root.rj.baseUrl}/organization/${organization.id}/donation/create`" id="dropdown02">{{ __('Donate') }}</a>
                    </li>
                </ul>
            </nav>
            <header class="header header--light">
                <section class="donor-hero">
                    <div class="donor-hero-filter" :style="campaignBackgroundColor">
                        <div class="container">
                            <div class="row">
                                <div class="col-12">
                                    <div class="donor-hero-filter__body">
                                        <div class="row align-items-center">
                                            <div class="col p-5 d-flex flex-column h-405 order-2 order-lg-1">
                                                <h2 class="aleo mb-0 break-word">@{{ form.name }}</h2>
                                                <span class="f-20 break-word">@{{ organization.name }}</span>
                                                <div class="d-flex progressbar-container progressbar-container--thick mt-auto">
                                                    <div class="w-85">
                                                        <div class="d-flex justify-content-between mb-1">
                                                            <div class="aleo f-20" v-if="campaign.funds_raised && campaign.fundraising_goal"><span>@{{ fundsRaised(campaign, organization) }}</span> of @{{ fundRaisingGoal(campaign, organization) }}</div>
                                                            <div class="aleo f-20" v-else-if="form.fundraising_goal"><span>@{{ fundsRaised(form, organization) }}</span> of @{{ fundRaisingGoal(form, organization) }}</div>
                                                            <div class="f-14 mt-2 aleo" v-if="form.end_date">@{{ endsAt(form) }}</div>
                                                        </div>
                                                        <div class="position-relative">
                                                            <div class="progress rounded-pill w-100">
                                                                <div role="progressbar" :style="'width: ' + progress(campaign) + '%'" :aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" class="progress-bar"></div>
                                                            </div>
                                                            <div class="ref aleo pl-2 f-34">@{{ progress(campaign) }}%</div>
                                                        </div>
                                                        <div class="d-flex" v-if="campaign.total_donations">
                                                             <div class="text-grey-5 f-14 font-weight-bold sub__text mt-3 mt-sm-1">
                                                                    {{ __('Raised by') }} @{{ campaign.total_donations }} {{ __('donors') }} <div class="d-inline font-weight-normal" v-if="campaign.published_at">{{ __('since') }} @{{ $root.convertUTCToBrowser(campaign.published_at, 'M/D/YY') }}</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <button type="submit" class="btn btn--size14 btn-full-m rounded-pill mt-auto">{{ __('Donate Now') }}</button>
                                            </div>
                                            <div class="col-12 col-lg-7 order-1 order-lg-2">
                                                <div class="donor-hero-filter__image" :style="imageStyles.image" v-if="campaign.image"></div>
                                                <svg v-else width="100%" height="405">
                                                    <rect width="100%" height="100%" fill="#e2e2e2">
                                                    </rect>
                                                    <text x="50%" y="50%" fill="#222222" text-anchor="middle" alignment-baseline="central" font-weight="bold">No Image</text>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </header>

            <section class="our-story mt-5 border-0">
                <div class="container">
                <div class="row">
                    <div class="col-md-8">
                        <h3 class="aleo">{{ __('Our Story') }}</h3>
                        <div class="f-16 preview--html break-word" v-html="$root.renderMd(form.description)">
                        </div>

                        <h3 class="mt-4">{{ __('Share this Campaign:') }}</h3>
                        @include('partials.common.social-share-preview')
                        {{-- <ul class="list-inline mb-0 mt-auto">
                            <li class="list-inline-item mb-2">
                                <button class="btn btn-facebook rounded-pill cursor-pointer assistant text-left py-1 pl-4 l-h-normal popup">
                                    <i class="fab fa-facebook-f mr-2"></i>Share
                                    <span class="popuptext">{{ __('You can not share this preview/edit page.') }}</span>
                                </button>
                            </li>
                            <li class="list-inline-item mb-2">
                                <button class="btn btn-twitter rounded-pill cursor-pointer assistant text-left py-1 pl-4 l-h-normal popup">
                                    <i class="fab fa-twitter mr-2"></i>Tweet
                                </button>
                            </li>
                            <li class="list-inline-item mb-2">
                                <button class="btn btn-email rounded-pill cursor-pointer assistant text-left py-1 pl-4 l-h-normal popup">
                                    <i class="fa fa-link fa-fw mr-2"></i>Share
                                </button>
                            </li>
                        </ul> --}}

                        <div class="organizer d-flex flex-wrap my-5">
                            <h3 class="aleo w-100">{{ __('Organizer') }}</h3>
                            <div class="mr-4" v-if="organization.logo">
                                <img :src="organization.logo" alt="organization.name" class="btn-file--small rounded-circle float-left">
                            </div>
                            <address>
                                <h4 class="mb-0">@{{ organization.name }}</h4>
                                @{{ organization.address1 }}<br>
                                <div v-if="organization.address2">
                                    @{{ organization.address2 }}
                                </div>
                                @{{ organization.city + ', ' + organization.state }}
                            </address>
                        </div>
                    </div>

                    <div class="col-md-4 pl-lg-5">
                        <div v-if="$parent.rewards[0].title">
                            <h3 class="aleo">{{ __('Rewards') }}</h3>
                            <div v-for="(reward, index) in $parent.rewards">
                                <div class="d-flex" v-if="reward.image">
                                    <img :src="reward.image" alt="Rewards">
                                </div>

                                <div class="rewards-card p-4 mb-2">
                                    <div class="adjust-amount">
                                        <sup>@{{ organization.currency.symbol }}</sup> <span class="rewards-card__amount rewards-card__amount--fs-40">@{{ reward.min_amount }}</span>
                                    </div>
                                    <div class="">
                                        <div class="rewards-card__title f-18 assistant break-word">@{{ reward.title }}</div>
                                        <div class="rewards-card__subtitle break-word" v-if="reward.description">@{{ reward.description.substring(0, 100) }}....</div>
                                        <a href="#" class="cta assistant" v-if="reward.description">{{ __('Read More') }}</a>
                                    </div>
                                    <div class="">
                                        <div class="rewards-card__status d-flex flex-wrap align-items-center mt-4">
                                            <button @click.prevent="selectReward" type="submit" class="btn btn--m-w-auto rounded-pill mr-3 f-12">{{ __('Select') }}</button>
                                            <span class="f-14 font-weight-bold" v-if="reward.quantity">@{{ Math.floor(reward.quantity / 2)}} <span class="font-weight-normal">of</span> @{{ Math.floor(reward.quantity) }} <span class="font-weight-normal">claimed</span></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @php
                            if (isset($campaign)) {
                                $donationsCount = $campaign->donations()->count();
                                $count = 0;
                            }
                        @endphp
                        @if (isset($donationsCount) && $donationsCount != 0)
                            <h3 class="aleo mt-5">{{ __('Thanks to our Supporters!') }}</h3>
                            <div class="rewards-card p-4 mb-2">
                                @foreach ($campaign->donations()->orderBy('created_at', 'desc')->limit(3)->get() as $donation)
                                <div class="supporters">
                                    {{ $donation->donor->name }} <span>{{ RJ::donationMoney($donation->gross_amount, $donation->currency->symbol) }}</span>
                                    <div class="time">{{ $donation->created_at->diffForHumans() }}</div>
                                </div>
                                @php $count++; @endphp
                                @endforeach
                            </div>
                            @if ($donationsCount - $count)
                            <div>{!! __('and <strong>:count</strong> others', ['count' => $donationsCount - $count]) !!}</div>
                            @endif
                        @endif

                    </div>
                </div>
                </div>
            </section>
        </div>
    </div>
</div>
