@extends('layouts.app')

@section('title')
{{ __('Sign Up') }}
@endsection

@section('title', "RocketJar")

@section('content')
<section class="section">
    <div class="section__title">
        <div class="row">
            <div class="col-12">
                <h2>{{ __('Sign up') }}</h2>
            </div>
            <!-- /.col-12 -->
        </div>
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-12 col-md-6 order-md-2">
            <div class="form-container spacer-tp--30">
                <div class='form_wrapper'>
                    <org-sign-up inline-template>
                        <form method='post' @submit.prevent="signup">
                            <div class='form_body'>
                                <ul class='form_fields'>
                                    <li class='field size2 align-top'><label class='field_label field_label--asterisk'>{{ __('Organization Name')}}</label>
                                        <div class='input_container input_container_text'>
                                            <input type='text'
                                                tabindex='1'
                                                placeholder='{{ __("Organization Name")}}'
                                                v-model="signupForm.name"
                                                @keyup="validate"
                                                :class="{'is-invalid': signupForm.errors.has('name')}">
                                        </div>
                                        <span class="invalid-feedback" v-show="signupForm.errors.has('name')">
                                            @{{ signupForm.errors.get('name') }}
                                        </span>
                                    </li>
                                    <li class='field size1 align-top'><label class='field_label field_label--asterisk'>{{ __('Your first name')}}</label>
                                        <div class='input_container input_container_text'>
                                            <input type='text'
                                                tabindex='2'
                                                placeholder='{{ __("Your first name")}}'
                                                v-model="signupForm.first_name"
                                                @keyup="validate"
                                                :class="{'is-invalid': signupForm.errors.has('first_name')}">
                                        </div>
                                        <span class="invalid-feedback" v-show="signupForm.errors.has('first_name')">
                                            @{{ signupForm.errors.get('first_name') }}
                                        </span>
                                    </li>
                                    <li class='field size1 align-top'><label class='field_label field_label--asterisk'>{{ __('Your last name')}}</label>
                                        <div class='input_container input_container_text'>
                                            <input type='text'
                                                tabindex='3'
                                                placeholder='{{ __("Your last name")}}'
                                                v-model="signupForm.last_name"
                                                @keyup="validate"
                                                :class="{'is-invalid': signupForm.errors.has('last_name')}">
                                        </div>
                                        <span class="invalid-feedback" v-show="signupForm.errors.has('last_name')">
                                            @{{ signupForm.errors.get('last_name') }}
                                        </span>
                                    </li>
                                    <li class='field size2 align-top'><label class='field_label field_label--asterisk'>{{ __('Your email address')}}</label>
                                        <div class='input_container input_container_text'>
                                            <input type='text'
                                                tabindex='4'
                                                placeholder='{{ __("Your email address")}}'
                                                v-model="signupForm.email"
                                                @keyup="validate"
                                                :class="{'is-invalid': signupForm.errors.has('email')}">
                                        </div>
                                        <span class="invalid-feedback" v-show="signupForm.errors.has('email')">
                                            @{{ signupForm.errors.get('email') }}
                                        </span>
                                    </li>
                                    <li class='field size1 align-top'><label class='field_label field_label--asterisk'>{{ __("Password")}}</label>
                                        <input type='password'
                                            id="password"
                                            tabindex='5'
                                            class="password-text"
                                            placeholder='{{ __("Password")}}'
                                            v-model="signupForm.password"
                                            @keyup="validate"
                                            :class="{'is-invalid': signupForm.errors.has('password')}">
                                        <span class="invalid-feedback" v-show="signupForm.errors.has('password')">
                                            @{{ signupForm.errors.get('password') }}
                                        </span>
                                    </li>
                                    <li class='field size1 align-top'><label class='field_label field_label--asterisk'>{{ __     ("Confirm Password")}}</label>
                                        <div class='input_container input_container_text'>
                                            <input type='password'
                                                class="password-text"
                                                tabindex='6'
                                                placeholder='{{ __('Confirm Password')}}'
                                                v-model="signupForm.password_confirmation"
                                                @keyup="validate"
                                                :class="{'is-invalid': signupForm.errors.has('password_confirmation')}">
                                        </div>
                                    </li>
                                    <li class="field size2 align-top password-messages p-0"></li>
                                </ul>
                            </div>
                            <div class='form_footer d-lg-flex flex-column flex-md-row justify-content-between align-items-start'>
                                @include('partials.common.button-with-loading', [
                                    'title' => __('Sign Up'),
                                    'buttonClass' => 'mr-md-4',
                                    'busyCondition' => 'signupForm.busy',
                                    'disabledCondition' => 'signupForm.busy || ! signupFormIsValid',
                                    'submitMethod' => 'signup'
                                ])
                                <p>{{ __('By clicking this button, you agree to RocketJar’s')}} <a class="text-link" href="{{ route('terms-of-service') }}">{{ __('Terms and Conditions') }}</a> {{ __('of Use.') }}</p>
                            </div>
                        </form>
                    </div>
                </org-sign-up>
            </div><!-- /.form-def -->
        </div><!-- /.col-6 -->
        <div class="col-12 col-md-6 order-md-1">
            {{-- <div class="section__title section__title--alt">
                <h2><span>FREE</span> for your first month</h2>
            </div>
            <div class="section__inner mb-5">
                <p>{{ __("Then just $25 / month after that. Plus we'll donate your monthly fee back to you every month you raise $1,000.") }}
                    <a href="#" class="text-link"><span>{{ __('See full pricing')}}</span>
                        <svg class="ml-1" width="25" height="12" viewBox="0 0 25 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M24.5303 6.53033C24.8232 6.23744 24.8232 5.76257 24.5303 5.46967L19.7574 0.696701C19.4645 0.403807 18.9896 0.403807 18.6967 0.696701C18.4038 0.989594 18.4038 1.46447 18.6967 1.75736L22.9393 6L18.6967 10.2426C18.4038 10.5355 18.4038 11.0104 18.6967 11.3033C18.9896 11.5962 19.4645 11.5962 19.7574 11.3033L24.5303 6.53033ZM-6.55671e-08 6.75L24 6.75L24 5.25L6.55671e-08 5.25L-6.55671e-08 6.75Z" fill="#3490dc"></path>
                        </svg>
                    </a>
                </p>
            </div> --}}
            <div class="section__title section__title--alt">
                <h2><span>{{ __('7.9%')}}</span>{{ __('+ $0.30 per transaction')}}</h2>
            </div>
            <div class="section__inner">
                <p>{{ __("Deposited directly into your bank account, no waiting. We support all checking account types, including 501(c)(3). We'll even send you tax info at the end of the year.")}}</p>
            </div>
        </div><!-- /.col-6 -->
    </div><!-- /.row -->
</section>
@include('partials.modals.modal-message', [
    'modalId' => 'signup-success-modal',
    'modalTitle' => __('Thank You!'),
    'modalBody' => __('You have signed up successfully. Please login to your new account.'),
    'buttons' => [
        'action' => [
            'title' => __('Go To Login'),
            'url' => route('login'),
        ]
    ]
])
@endsection
