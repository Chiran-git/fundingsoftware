@extends('layouts.app')

@section('title', __('Campaigns'))

@section('content')
<campaign-list :organization="currentOrganization" inline-template v-if="currentOrganization">
    <div>
    <section class="section">
        <div class="row mb-5">
            <div class="col-12 col-sm-6 col-md-3">
                <h2 class="aleo">{{ __('Campaigns')}}</h2>
            </div>
            @if (in_array(auth()->user()->currentRole(), ['owner', 'admin']))
                <div class="col-12 col-sm-6 ml-auto col-md-4">
                    <a href="{{ route('campaign.create') }}" class="btn btn--outline rounded-pill float-sm-right mt-2 mt-md-0">{{ __('New campaign') }}</a>
                </div>
            @endif
        </div>
        <div class="row no-gutters border rounded flex-md-row mb-4 shadow-sm h-md-250 position-relative" v-if="!campaigns.data.length">
            <div class="col-12 p-3"><p>{{ __('No campaign found') }}</p></div>
        </div>
        <div class="row no-gutters border rounded flex-md-row mb-4 shadow-sm h-md-250 position-relative"
            v-for="(campaign, index) in campaigns.data"
            v-if="showCampaignList" :class="{'not-published': campaign.status == 'unpublished', 'inactive': campaign.status == 'inactive'}">
                <div class="col-12 col-lg-3 p-2 rounded-left">
                    <img :src="campaign.image" class="img-rounded-left" alt="" v-if="campaign.image">
                    <div class="img-rounded-left d-flex justify-content-center align-items-center" v-if="! campaign.image">
                        <a :href="`${$root.rj.baseUrl}/campaign/${campaign.id}/edit`" class="stretched-link text-center text-link--grey">
                            <img src="{{ asset('images/icons/add-a-photo-small.png') }}" alt="">
                        <span class="mb-2 d-block font-weight-bold">{{ __("Add a Photo")}}</span></a>
                    </div>
                    <a :href="`${$root.rj.baseUrl}/campaign/${campaign.id}/details`" class="stretched-link"></a>
                </div>
                <div class="col-12 col-md-10 col-lg-7 p-3 pb-5 pb-sm-3 pl-md-4 d-flex flex-column position-static order-3 order-md-2 justify-content-between">
                    <h3 class="mb-0 aleo break-word"><a :href="`${$root.rj.baseUrl}/campaign/${campaign.id}/details`">@{{ campaign.name }}</a>
                        <a v-if="campaign.status == 'inactive'" href="#" class="btn disabled btn-sm f-14 assistant p-1 text-tranform text-uppercase" role="button">{{ __('Inactive') }}</a>
                        <a v-else-if="campaign.status == 'unpublished'" href="#" class="btn disabled btn-sm f-14 assistant p-1 text-tranform text-uppercase" role="button">{{ __('Not Published') }}</a>
                    </h3>
                    <div class="d-flex progressbar-container progressbar-container--medium mb-2">
                        <div class="w-85">
                            <div class="d-flex justify-content-between mb-1">
                                <div class="aleo"><span class="font-weight-bold f-24 pr-1">@{{ fundsRaised(campaign, organization) }}</span> of @{{ fundRaisingGoal(campaign, organization) }}</div>
                                <div class="aleo f-14 mt-1">@{{ endsAt(campaign) }}</div>
                            </div>
                            <div class="position-relative">
                                <div class="progress rounded-pill">
                                    <div role="progressbar" :style="'width: ' + progress(campaign) + '%'" :aria-valuenow="progress(campaign)" aria-valuemin="0" aria-valuemax="100" class="progress-bar"></div>
                                </div>
                                <div class="ref assistant font-weight-normal pl-1 camlist">@{{ progress(campaign) }}<div class="f-18 d-inline pl-1">%</div></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-auto ml-md-auto p-3 pl-md-0 d-flex flex-column position-static order-1 order-md-3 text-grey-5">
                        <ul class="list-inline link-inline">
                            <li class="list-inline-item mb-2 text-md-right">{{ __('Details ') }}<a :href="`${$root.rj.baseUrl}/campaign/${campaign.id}/details`" class="btn btn--xs-rounded rounded"><i class="fas fa-signal text-muted"></i></a></li>
                            <li class="list-inline-item mb-2 text-md-right">{{ __('Edit') }}
                                <a v-if="campaign.published_at" :href="`${$root.rj.baseUrl}/campaign/${campaign.id}/edit`" class="btn btn--xs-rounded rounded"><i class="fas fa-pen text-muted pl-1"></i></a>
                                <a v-else="campaign.published_at" :href="`${$root.rj.baseUrl}/campaign/create?step=2&id=${campaign.id}`" class="btn btn--xs-rounded rounded"><i class="fas fa-pen text-muted pl-1"></i></a></li>
                            </li>
                            <li class="list-inline-item mb-2 text-md-right">{{ __('View') }}<a @click.prevent="showCampaignViewModal(index)" class="btn btn--xs-rounded rounded cursor-pointer"><i class="fas fa-search text-muted pl-1"></i></a></li>
                            <li class="list-inline-item mb-2 text-md-right btn-group dropleft btn--more">{{ __('More') }}
                                <button class="btn btn--xs-rounded rounded dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-h text-muted"></i></button>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item cursor-pointer showShareModal" :data-modal-id="'campaign-share-modal-' + campaign.id">{{ __('Share') }}</a>
                                    <a class="dropdown-item" :href="`${$root.rj.baseUrl}/organization/${organization.id}/donation/create?campaign=${campaign.id}`">{{ __('Record Donation') }}</a>
                                    <a class="dropdown-item" href="#"
                                        @click.prevent="deactivateCampaign(campaign.id)"
                                        v-if="campaign.status == 'active'">{{ __('Deactivate') }}</a>
                                </div>
                            </li>
                        </ul>
                </div>
                <share-modal inline-template
                    :modal-id="'campaign-share-modal-'+campaign.id"
                    modal-title="{{ __('Share this Campaign') }}"
                    :modal-subtitle="campaign.name"
                    :share-url="'{{ url('/') }}/' + organization.slug + '/' + campaign.slug"
                    :share-headline="campaign.name"
                    :share-text="'{{ __('Fund projects that matter') }} {{ url('/') }}' + organization.slug + '/' + campaign.slug">
                    @include('partials.modals.modal-share')
                </share-modal>
            </div>

            <pagination :data="campaigns"
                :limit=$root.rj.pagination.pages_limit
                :show-disabled=$root.rj.pagination.show_disabled
                @pagination-change-page="setCampaignList">
                <span slot="prev-nav"><i class="fas fa-caret-left"></i></span>
                <span slot="next-nav"><i class="fas fa-caret-right"></i></span>
            </pagination>
    </section>
    @include('campaign.preview.modal-campaign-view', [
        'modalId' => 'campaign-view',
    ])
    </div>
</campaign-list>
@endsection
