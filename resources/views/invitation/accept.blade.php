@extends('layouts.auth')

@section('title', __('Accept Invitation'))

@section('content')

<accept-invitation inline-template :code="'{{ $code }}'" :organization="{{ $organization }}" :set_password="'{{$setPassword }}'">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card pt-4 rounded form-container form_wrapper form_wrapper--shadow form_wrapper--alt">
                <div class="form__head">
                    <h4 class="mb-0"><img src="{{ asset('images/RocketJar_auth.png') }}" alt="logo" class="img-fluid"></h4>
                    <p class="text-uppercase">fund projects that <strong>matter</strong></p>
                </div>
                <div class="row text-center">
                    <div class="col-12">
                        @if (!empty($user))
                            <h6 class="f-16 mb-0">{{ __("You have been assigned as $invitation->role of $organization->name") }}</h6>

                            <p class="mb-5">{{ __("Please accept invitation to manage campaign.") }} </p>
                        @else
                            <h6 class="f-16 mb-0">{{ __("You have been invited to join $organization->name")}}</h6>

                            <p class="mb-5">{{ __("Please set your password and accept the invitation.") }} </p>
                        @endif
                    </div>
                </div>
                <div class="form__body form_fields">
                    <form method="POST" action="">
                        @csrf
                        @if ($setPassword === true)
                            <div >
                                <div class="form-group row">
                                    <label for="email" class="col-md-4 col-form-label field_label text-md-right">{{ __('E-Mail Address') }}</label>

                                    <div class="col">
                                        <input disabled id="email" type="email" class="form-control" name="email" value="{{ $invitation->email }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="password" class="col-md-4 col-form-label field_label text-md-right">{{ __('Password') }}</label>

                                    <div class="col form--reset" >
                                        <input id="password" type="password" class="form-control password-text" :class="{'invalid': form.errors.has('password')}" name="password" required autocomplete="new-password" v-model="form.password">

                                        <span class="invalid-feedback" v-show="form.errors.has('password')">
                                            @{{ form.errors.get('password') }}
                                        </span>

                                        <div class="password-messages"></div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="password-confirm" class="col-md-4 col-form-label field_label text-md-right">{{ __('Confirm Password') }}</label>

                                    <div class="col">
                                        <input id="password-confirm" type="password" class="form-control" :class="{'is-invalid': form.errors.has('password_confirmation')}" name="password_confirmation" required autocomplete="new-password" v-model="form.password_confirmation">

                                        <span class="invalid-feedback" v-show="form.errors.has('password_confirmation')">
                                            @{{ form.errors.get('password_confirmation') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="form-group row mb-5">
                            <div class="col">
                                <button type="submit"  class="btn rounded-pill w-100" @click.prevent="acceptInvitation"
                                :disabled="form.busy">
                                    {{ __('Accept') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</accept-invitation>
@endsection
