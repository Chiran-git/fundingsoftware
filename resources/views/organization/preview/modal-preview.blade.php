<div class="modal fade pr-0" id="{{ $modalId }}" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-xl m-w-100 w-100">
        <div class="modal-content grayscale">
            <div class="container-fluid">
                <div class="row preview-bar py-3 align-items-center">
                    <div class="col-12 col-lg-1">
                        <h5 class="text-uppercase preview-bar__title font-weight-bolder">{{ __('PREVIEW') }}</h5>
                    </div>
                    <div class="col-12 col-lg-7">
                        <p class="f-14 my-3 my-lg-0">{{ __('Your unsaved changes are shown below. Save your changes or continue editing your Organization Page.') }}</p>
                    </div>
                    <div class="col-12 col-lg-4 text-lg-right">
                        <a href="#" class='btn btn--redoutline btn--lightborder btn--lightborder--ft rounded-pill f-16 mr-xl-3 mb-1 assistant' data-dismiss="modal"
                        ><i class="fas fa-pencil-alt"></i> {{ __('Continue Editing')}}</a>
                        <a href="#" class='btn btn--redoutline btn--lightborder btn--lightborder--ft rounded-pill f-16 mb-1 assistant'
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
                        {{-- <a class="nav-link dropdown-toggle dropdown-toggle--modified f-20" href="#" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Share</a> --}}
                        <a class="nav-link dropdown-toggle dropdown-toggle--modified f-20 cursor-pointer showShareModal" id="dropdown01" data-modal-id="organization-share-modal2">Share</a>
                    </li>
                </ul>
            </nav>
            @php
                $organization = request()->user()->organization;
            @endphp
            @if ($organization)
            <share-modal inline-template
                modal-id="organization-share-modal2"
                modal-title="{{ __('Share this Page') }}"
                modal-subtitle="{{ $organization->name }}"
                share-url="{{ route('organization.show', ['orgSlug' => $organization->slug]) }}"
                share-headline="{{ $organization->name }}"
                share-text="{{ __('Fund projects that matter') }} {{ route('organization.show', ['orgSlug' => $organization->slug]) }}">
                @include('partials.modals.modal-share')
            </share-modal>
            @endif
            <header class="header header--light">
                <div class="img-cover-container" v-if="organization.cover_image">
                    <div class="img-cover bg-auto d-block" :style="imageStyles.cover_image"></div>
                </div>
                <template v-if="! organization.cover_image">
                    <div class="img-rounded-left bg-light-gray d-flex justify-content-center align-items-center h-320">
                        <span class="mb-2 d-block font-weight-bold f-34">{{ __("No Image")}}</span>
                    </div>
                </template>
                <section class="header-filter" :style="orgBackgroundColor">
                    <div class="container">
                        <div class="row">
                            <div class="col-12">
                                <div class="header-filter__body">
                                    <div class="row">
                                        <div class="col-12 col-md-3">
                                            <div class="p-2 p-md-3 mb-3 mb-md-0 border-hr ml-2">
                                                <template v-if="organization.logo">
                                                    <img :src="organization.logo" alt="school" class="img-fluid float-left float-md-none mr-2" width="">
                                                </template>
                                                <address>
                                                    <p class="mb-2 f-16" v-if="form.name">@{{ form.name }}</p>
                                                    <p class="mb-2 f-16" v-else-if="newOrganization.name">@{{ newOrganization.name }}</p>
                                                    <p class="mb-2 f-16" v-else>@{{ organization.name }}</p>
                                                    <p class="mb-0 f-14">
                                                        <span class="d-block" v-if="form.address1"> @{{ form.address1 }}</span>
                                                        <span class="d-block" v-else-if="newOrganization.address1"> @{{ newOrganization.address1 }}</span>
                                                        <span class="d-block" v-else> @{{ organization.address1 }}</span>
                                                        <span class="d-block" v-if="form.address2"> @{{ form.address2 }}</span>
                                                        <span class="d-block" v-else-if="newOrganization.address2"> @{{ newOrganization.address2 }}</span>
                                                        <span class="d-block" v-else> @{{ organization.address2 }}</span>
                                                        <span v-if="form.city">@{{form.city}}</span>
                                                        <span v-else-if="newOrganization.city">@{{ newOrganization.city }}</span>
                                                        <span v-else>@{{ organization.city }}</span>,
                                                        <span v-if="form.state">@{{form.state}}</span>
                                                        <span v-else-if="newOrganization.state">@{{ newOrganization.state }}</span>
                                                        <span v-else>@{{ organization.state }}</span>,
                                                        <span v-if="form.zipcode">@{{form.zipcode}}</span>
                                                        <span v-else-if="newOrganization.zipcode">@{{ newOrganization.zipcode }}</span>
                                                        <span v-else>@{{ organization.zipcode }}</span>
                                                    </p>
                                                </address>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-9">
                                            <div class="px-2 pr-md-4 pl-md-2 left d-flex align-content-between flex-column h-100 break-word">
                                                <h2 class="mb-3" v-if="form.appeal_headline">@{{ form.appeal_headline }}</h2>
                                                <h2 class="mb-3" v-else="form.appeal_headline">@{{ organization.appeal_headline }}</h2>
                                                <p class="mb-4 mb-md-auto">
                                                    <template v-if="organization.appeal_photo">
                                                        <div class="img-container float-right rounded-circle" :style="imageStyles.appeal_photo"></div>
                                                    </template>
                                                    <span v-if="form.appeal_message" v-html="$root.renderMd(form.appeal_message)"></span>
                                                    <span v-else-if="organization.appeal_message" v-html="$root.renderMd(organization.appeal_message)"></span>
                                                    <span v-else></span>
                                                </p>
                                                <p>@include('partials.common.social-share-preview')</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </header>
            <section class="my-5 pt-4">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <h3>Our Campaigns</h3>
                            <div class="row no-gutters border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-250-x position-relative"
                                v-for="(campaign, index) in campaigns.data">
                                <div class="col-lg-3">
                                    <img :src="campaign.image" class="image-cover" alt="" v-if="campaign.image">
                                    <div class="img-rounded-left bg-light-gray d-flex justify-content-center align-items-center h-320" v-else="campaign.image">
                                        <span class="mb-2 d-block font-weight-bold f-34">{{ __("No Image")}}</span>
                                    </div>
                                </div>
                                <div class="col-md-8 col-lg-7 p-4 d-flex flex-column position-static">
                                    <h3 class="mb-0 aleo mt-lg-2 break-word">@{{ campaign.name }}</h3>
                                    <!-- <p class="card-text mb-0 mt-2">@{{ campaign.description }}</p> -->
                                    <p class="card-text mb-0 mt-auto preview--html break-word" v-html="$root.renderMd(campaign.description)"></p>
                                    <p class="mb-auto"><a href="/"class="dark-link stretched-link">{{ __("Read More") }} <i class="fas fa-chevron-right f-8"></i></a></p>
                                    <div class="d-flex progressbar-container progressbar-container--medium progressbar-container--org-edit mt-2">
                                        <div class="w-85">
                                            <div class="d-flex justify-content-between mb-1">
                                                <div class="aleo"><span class="font-weight-bold">@{{ campaign.funds_raised }}</span> of @{{ campaign.fundraising_goal }}</div>
                                                <div class="f-14 mt-1 aleo" v-if="campaign.end_date">@{{ campaign.days_left > 0 ? campaign.days_left + ' days left' : 'Campaign Ended' }}</div>
                                            </div>
                                            <div class="position-relative">
                                                <div class="progress rounded-pill">
                                                    <div role="progressbar" :style="'width: ' + campaign.donation_percent + '%'" :aria-valuenow="campaign.donation_percent" aria-valuemin="0" aria-valuemax="100" class="progress-bar"></div>
                                                </div>
                                                <div class="ref aleo font-weight-light pl-2 f-34">@{{ campaign.donation_percent }}%</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @if ($organization)
                                <div class="col-md-4 col-lg-2 p-4 pl-lg-2 mt-lg-2 d-flex flex-column position-static">
                                    <a href="{{ route('donation.create', ['id' => auth()->user()->organization->id]) }}"
                                        class="btn rounded-pill btn--size4"
                                        v-if="campaign.days_left > 0">{{ __('Donate') }}</a>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
