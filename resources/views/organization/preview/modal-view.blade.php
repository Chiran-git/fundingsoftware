<div class="modal fade pr-0" id="view_page" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
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
                            {{ __('Below is what your supporters see when they visit your organization page.') }}
                        </p>
                    </div>
                    <div class="col-12 col-lg-4 text-lg-right">
                        {{-- <a href="#" class='btn btn--redoutline rounded-pill mt-4 mt-md-0 mr-lg-3 assistant btn--lightborder f-16'>
                            <span>{{ __('Share Page')}}</span>
                        </a> --}}
                        <button type='submit' class='btn btn--redoutline rounded-pill btn--lightborder f-16 mr-xl-3 mb-1 assistant showShareModal' data-modal-id="organization-share-modal">{{ __('Share Page')}}</button>
                        <a href="{{ route('organization.edit') }}" class='btn btn--redoutline rounded-pill mb-1 assistant btn--lightborder f-16'><i class="fas fa-pencil-alt mr-3"></i> {{ __('Edit Page')}}</a>
                    </div>
                </div>
            </div>
            <nav
                class="navbar nav-inverse--light d-flex flex-md-row align-items-center px-4 py-2 bg-white border-bottom shadow-sm">
                @php $href = !empty($organization->slug) ? url("/{$organization->slug}") : url("/"); @endphp
                <a class="navbar-brand my-0 mr-md-auto donor-logo" href="{{ $href }}"></a>
                <ul class="d-flex align-items-center aleo">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle dropdown-toggle--modified f-20 cursor-pointer showShareModal" data-modal-id="organization-share-modal" id="dropdown01">Share</a>
                    </li>
                </ul>
            </nav>
            <iframe data-src="{{ route('organization.show', ['orgSlug' => request()->user()->organization->slug]) }}" height="600px" frameBorder="0"></iframe>
            @php
                $organization = request()->user()->organization;
            @endphp
            <share-modal inline-template
                modal-id="organization-share-modal"
                modal-title="{{ __('Share this Page') }}"
                modal-subtitle="{{ $organization->name }}"
                share-url="{{ route('organization.show', ['orgSlug' => $organization->slug]) }}"
                share-headline="{{ $organization->name }}"
                share-text="{{ __('Fund projects that matter') }} {{ route('organization.show', ['orgSlug' => $organization->slug]) }}">
                @include('partials.modals.modal-share')
            </share-modal>
        </div>
    </div>
</div>
