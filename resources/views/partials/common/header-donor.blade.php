<nav class="navbar nav-inverse--light d-flex flex-md-row align-items-center px-4 py-2 bg-white border-bottom shadow-sm">
    @php $href = !empty($organization->slug) ? url("/{$organization->slug}") : url("/"); @endphp
    <a class="navbar-brand my-0 mr-md-auto donor-logo ml-3" href="{{ $href }}"></a>
    <ul class="d-flex align-items-center aleo mr-3">
        @if (! request()->is('terms-of-service', 'privacy'))
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle dropdown-toggle--modified f-20 cursor-pointer showShareModal" data-modal-id="organization-share-modal" id="dropdown01">Share</a>
            {{-- <div class="dropdown-menu  dropdown-menu-right" aria-labelledby="dropdown01">
                <a class="dropdown-item" href="#">Share</a>
                <a class="dropdown-item" href="#">Record Donation</a>
                <a class="dropdown-item" href="#">Deactivate</a>
            </div> --}}
        </li>
        @endif
        @if (!empty($campaign))
            <li class="nav-item ml-4">
                <a href="{{ route('campaign.donate', [
                    'orgSlug' => $organization->slug,
                    'campSlug' => $campaign->slug,
                ]) }}" class="nav-link f-20">{{ __('Donate') }}</a>
            </li>
        @endif
    </ul>

</nav>
