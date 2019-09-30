@extends('layouts.app')

@section('title')
{{ __('Account Setup') }}
@endsection

@section('title', "RocketJar | Accounts")

@section('content')
    <section class="section--def section--primary">
        <h2 class="aleo mb-5">{{ __('Edit Organization')}}</h2>
        <org-edit
            :organization="currentOrganization"
            inline-template v-if="currentOrganization">
            <div id="account-setup" class="row justify-content-start" v-if="active">
                <div class="col-12 col-md-3">
                    @include('organization.partials.setup-account-tabs')
                </div>
                <div class="col-12 col-md-9 pl-md-5">
                    @include('organization.partials.profile-form', ['action' => 'edit'])
                    @include('organization.partials.pagedesign-form', ['action' => 'edit'])
                    @include('organization.partials.donorprofile-form', ['action' => 'edit'])
                    @include('organization.partials.accounts', ['action' => 'edit'])
                   {{-- @include('organization.partials.account-deactivate') --}}
                </div>
            </div>
        </org-edit>
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
