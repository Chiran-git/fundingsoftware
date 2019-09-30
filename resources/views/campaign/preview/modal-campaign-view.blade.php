<div class="modal fade pr-0" id="{{ $modalId }}" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-xl m-w-100 w-100">
        <div class="modal-content grayscale">
            <div class="container-fluid">
                <div class="row preview-bar py-3 align-items-center">
                    <div class="col-12 col-lg-auto">
                        <a href="#" class='btn btn--redoutline rounded-pill assistant btn--lightborder f-16'
                                data-dismiss="modal"><i class="fas fa-arrow-left"></i> {{ __('Go Back')}}</a>
                    </div>
                    <div class="col-12 col-lg-6 mr-lg-auto">
                        <p class="f-14 py-3">
                            {{ __('Below is what your supporters see when they visit your campaign page.') }}
                        </p>
                    </div>
                    <div class="col-12 col-lg-4 text-lg-right">
                        {{-- <a class='btn btn--redoutline btn--size9 rounded-pill mt-4 mt-md-0 ml-lg-3'>
                            <span>{{ __('Share Campaign')}}</span>
                        </a> --}}
                        <button type="submit" class="btn btn--redoutline rounded-pill btn--lightborder f-16 mr-xl-3 mb-1 assistant showShareModal" data-modal-id="campaign-share-modal2">
                            {{ __('Share Campaign') }}
                        </button>
                        <a :href="`${$root.rj.baseUrl}/campaign/${campaign.id}/edit`" class='btn btn--redoutline rounded-pill btn--lightborder f-16 mb-1 assistant'><i class="fas fa-pencil-alt"></i> {{ __('Edit Campaign')}}</a>
                    </div>
                </div>
            </div>

            <nav class="navbar nav-inverse--light d-flex flex-md-row align-items-center px-4 py-2 bg-white border-bottom shadow-sm">
                @php $href = !empty($organization->slug) ? url("/{$organization->slug}") : url("/"); @endphp
                <a class="navbar-brand my-0 mr-md-auto donor-logo" href="{{ $href }}"></a>
                <ul class="d-flex align-items-center aleo">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle dropdown-toggle--modified f-20 cursor-pointer showShareModal" id="dropdown01" data-modal-id="campaign-share-modal2">Share</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link f-20" :href="`${$root.rj.baseUrl}/organization/${organization.id}/donation/create`" id="dropdown02">{{ __('Donate') }}</a>
                    </li>
                </ul>
            </nav>

            <share-modal inline-template
                modal-id="campaign-share-modal2"
                modal-title="{{ __('Share this Campaign') }}"
                :modal-subtitle="campaign.name"
                :share-url="'{{ url('/') }}/' + organization.slug + '/' + campaign.slug"
                :share-headline="campaign.name"
                :share-text="'{{ __('Fund projects that matter') }} {{ url('/') }}' + organization.slug + '/' + campaign.slug">
                @include('partials.modals.modal-share')
            </share-modal>

            <iframe :src="`${$root.rj.baseUrl}/${organization.slug}/${campaign.slug}`" height="600px" frameBorder="0" v-if="campaign.slug"></iframe>
        </div>
    </div>
</div>
