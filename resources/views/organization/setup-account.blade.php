@extends('layouts.app')

@section('title')
{{ __('Account Setup') }}
@endsection

@section('title', "RocketJar | Accounts")

@section('content')
    <section class="section--def">
        @if (auth()->user()->isSuperAdmin() || auth()->user()->isAppAdmin())
            <h2>{{ __('New Organization')}}</h2>
            <org-create :organization="''" inline-template>
        @else
            <h2>{{ __('Account Setup')}}</h2>
            <org-setup :organization="currentOrganization" inline-template v-if="currentOrganization">
        @endif
                <div id="account-setup">
                    @include('organization.partials.setup-account-tabs')
                    @include('organization.partials.profile-form')
                    @include('organization.partials.pagedesign-form')
                    @include('organization.partials.donorprofile-form')
                    @include('organization.partials.completed')
                </div>
        @if (auth()->user()->isSuperAdmin() || auth()->user()->isAppAdmin())
            </org-create>
        @else
            </org-setup>
        @endif

    </section>
    @include('partials.modals.modal-message', [
        'modalId' => 'modal-account-setup-required',
        'modalTitle' => __('Welcome to RocketJar!'),
        'modalImage' => asset('images/account_sign-up-welcome.jpg'),
        'modalBody' => __('Let\'s finish setting up your account so you can start accepting donations.'),
        'buttons' => [
            'close' => [
                'title' => __('Set Up Account'),
                'class' => 'btn-default',
            ]
        ]
    ])
    @if (request()->input('modal'))
        @section('footer-scripts')
            $('#modal-account-setup-required').modal('show');
        @endsection
    @endif
@endsection
@section('head-tags')
<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.5/cropper.min.css" rel="stylesheet">
@endsection
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.5/cropper.min.js"></script>
