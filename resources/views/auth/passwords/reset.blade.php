@extends('layouts.auth')

@section('title')
{{ __('Reset Password') }}
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card pt-4 rounded form-container form_wrapper form_wrapper--shadow form_wrapper--alt">
            <div class="form__head">
                <h4 class="mb-0"><img src="{{ asset('images/RocketJar_auth.png') }}" alt="logo" class="img-fluid"></h4>
                <p class="text-uppercase">fund projects that <strong>matter</strong></p>
            </div>

            <div class="form__body form_fields">
                <form method="POST" action="{{ route('password.update') }}">
                    @csrf

                    <input type="hidden" name="token" value="{{ $token }}">

                    <div class="form-group row">
                        <label for="email" class="col-md-4 col-form-label field_label text-md-right">{{ __('E-Mail Address') }}</label>

                        <div class="col">
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" placeholder="{{ __('E-Mail Address') }}" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>

                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="password" class="col-md-4 col-form-label field_label text-md-right">{{ __('Password') }}</label>

                        <div class="col form--reset">
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror password-text" placeholder="{{ __('Password') }}" name="password" required autocomplete="new-password">

                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            <div class="password-messages"></div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="password-confirm" class="col-md-4 col-form-label field_label text-md-right">{{ __('Confirm Password') }}</label>

                        <div class="col">
                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="{{ __('Confirm Password') }}" required autocomplete="new-password">
                        </div>
                    </div>

                    <div class="form-group row mb-5">
                        <div class="col">
                            <button type="submit" class="btn rounded-pill w-100">
                                {{ __('Reset Password') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
