<div class="modal fade" id="{{ $modalId }}" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-cm-lg">
        <div class="modal-content grayscale rounded-0 p-3 pt-5 p-md-5">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
            <h2 class="text-center">{{ __("Donor Email Preview") }}</h2>

            <div class="container">
                <div class="row">
                    <div class="col-12 px-0">
                        <div class="donor-email__body donor-email__body--modal">
                            <div class="donor-email__inner donor-email__inner--modal mb-4">
                                <div class="mb-3 d-flex align-items-center pb-4 border-bottom">
                                    <div>
                                        <img :src="organization.logo" class="w-h-50 rounded-circle float-left" v-if="organization.logo" alt="organization.name">
                                    </div>
                                    <div class="ml-3">
                                        <h4 class="break-word">@{{ organization.name }}</h4>
                                    </div>
                                </div>

                                <div class="my-5">
                                    <h3 class="aleo mb-0">{{ __('Thank you, Janice!') }}</h3>
                                    <div class="preview--html break-word" v-html="$root.renderMd(form.donor_message)"></div>
                                </div>

                                <div class="thanks-box px-0 py-4 mb-5">
                                    <h3 class="aleo text-center mb-1 break-word">
                                        <template v-if="campaign.name">
                                            @{{ campaign.name }}
                                        </template>
                                        <template v-else>
                                            {{ __('Your Campaign Name') }}
                                        </template>
                                    </h3>
                                    <div class="my-4 contribution-bg">
                                        <div class="info-text">{!! __('Your <strong>$[Amount]</strong> contribution brings our total raised to <strong v-text="fundsRaised(campaign, organization)"></strong>!') !!}</div>
                                        <div class="d-flex progressbar-container my-3 px-md-3">
                                            <div class="w-100">
                                                <div class="d-flex mb-2">
                                                    <div><span>@{{ fundsRaised(campaign, organization) }}</span> of @{{ fundRaisingGoal(campaign, organization) }}</div>
                                                </div>
                                                <div class="progress rounded-pill">
                                                    <div role="progressbar" aria-valuenow="progress(campaign)" aria-valuemin="0" aria-valuemax="100" class="progress-bar" :style="'width:' + progress(campaign) + '%'"></div>
                                                    <div class="flex-shrink-1 align-self-end">
                                                        <div class="ref pl-2">@{{ progress(campaign) }}%</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="px-3 px-md-5">
                                        <div class="row break-word">
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <strong>{{ __('DATE') }}</strong>
                                                    <div>{{ now()->format('m/d/Y \a\t h:i a') }}</div>
                                                </div>
                                                <div class="mb-3">
                                                    <strong>{{ __('FROM') }}</strong>
                                                    <div>{{ __('Janice Smith') }}</div>
                                                </div>
                                                <div class="mb-3">
                                                    <strong>{{ __('DONATION AMOUNT') }}</strong>
                                                    <div>{{ __('$[Amount]') }}</div>
                                                </div>
                                                <div class="mb-3">
                                                    <strong>{{ __('METHOD') }}</strong>
                                                    <div>{{ __('Online (VISA xxxx)') }}</div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <strong>{{ __('RECEIPT NO.') }}</strong>
                                                    <div>{{ __('xxx987') }}</div>
                                                </div>
                                                <div class="mb-3">
                                                    <strong>{{ __('TO') }}</strong>
                                                    <div>
                                                        <address>
                                                            @{{ campaign.name }}<br>
                                                            @{{ organization.name }}<br>
                                                            @{{ organization.address1 }}<br>
                                                            <span v-if="organization.address2">@{{ organization.address2 }}<br></span>
                                                            @{{ organization.city }}, @{{ organization.state }} @{{ organization.zipcode }}<br>
                                                        </address>
                                                    </div>
                                                </div>
                                                {{-- <div class="mb-3">
                                                    <strong>{{ __('REWARD') }}</strong>
                                                    <div>{{ __('Water Bottle') }}</div>
                                                </div> --}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <h3 class="aleo">{{ __('Tell your friends:') }}</h3>
                                    @include('partials.common.social-share-preview')
                                </div>
                            </div>
                            <div class="text-center text-grey-5">{!! __('&copy; 2019 RocketJar. All rights reserved.') !!}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
